<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CreditController extends Controller
{
    public function create()
    {
        $this->authorize('create', Credit::class);
        return view('adherent.credits.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Credit::class);
        $validated = $request->validate([
            'montant' => ['required','numeric','min:1000'],
            'duree' => ['required','integer','min:1','max:60'],
            'motif' => ['required','string','max:1000'],
            'garanties' => ['nullable','string','max:1000'],
            'date_debut_remboursement' => ['nullable','date','after_or_equal:today'],
            'documents.*' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        ]);
        $adherent = $request->user()?->adherent;
        if (!$adherent) return redirect()->back()->with('error','Profil adhérent introuvable.');
        if ($adherent->credits()->whereIn('statut',["en_attente","en_cours"])->exists()) {
            return redirect()->back()->with('error','Vous avez déjà une demande de crédit en cours de traitement.');
        }
        $tauxInteret = $this->calculateInterestRate($validated['duree']);
        $fraisDossier = max(5000, $validated['montant'] * 0.02);
        $credit = new Credit([
            'adherent_id' => $adherent->id,
            'montant_demande' => $validated['montant'],
            'montant_accorde' => 0,
            'duree' => $validated['duree'],
            'taux' => $tauxInteret,
            'type_credit' => 'personnel',
            'periodicite' => 'mensuel',
            'frais_dossier' => $fraisDossier,
            'statut' => 'en_attente',
            'etat' => 'soumis',
            'date_demande' => now()->toDateString(),
            'date_debut_remboursement' => $validated['date_debut_remboursement'] ?? null,
            'motif' => $validated['motif'],
            'garanties' => $validated['garanties'] ?? null,
        ]);
        $credit->save();
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('documents/credits/' . $credit->id, 'public');
                $document = new \App\Models\Document([
                    'nom' => $file->getClientOriginalName(),
                    'chemin' => $path,
                    'type' => $file->getClientMimeType(),
                    'taille' => $file->getSize(),
                    'documentable_id' => $credit->id,
                    'documentable_type' => Credit::class,
                ]);
                $credit->documents()->save($document);
            }
        }
        Notification::create([
            'user_id' => 1,
            'titre' => 'Nouvelle demande de crédit',
            'message' => "L'adhérent {$adherent->nom_complet} a soumis une demande de crédit de {$validated['montant']} FCFA.",
            'lu' => false,
            'type' => 'info',
        ]);
        return redirect()->route('adherent.credits.index')
            ->with('success','Votre demande de crédit a été soumise avec succès. Vous serez notifié de son évolution.');
    }

    private function calculateInterestRate($dureeMois)
    {
        if ($dureeMois <= 12) return 5.0;
        if ($dureeMois <= 24) return 12.5;
        if ($dureeMois <= 36) return 15.0;
        return 18.0;
    }

    public function approve(Request $request, Credit $credit)
    {
        $this->authorize('approve', $credit);
        $data = $request->validate([
            'montant_accorde' => ['required','numeric','min:0.01'],
            'taux' => ['nullable','numeric','min:0'],
        ]);
        if (!in_array($credit->etat, ['en_examen','soumis'])) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Crédit non éligible pour approbation.'], 422)
                : redirect()->back()->with('error', 'Crédit non éligible pour approbation.');
        }
        $credit->montant_accorde = $data['montant_accorde'];
        if (array_key_exists('taux',$data) && $data['taux'] !== null) $credit->taux = $data['taux'];
        $credit->statut = 'approuvé';
        $credit->etat = 'approuve';
        $credit->date_validation = now()->toDateString();
        $credit->validated_by_agent_id = $request->user()->id ?? null;
        $credit->save();
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backoffice.credits.exports.contract-pdf', [
                'credit' => $credit->fresh('adherent'),
                'date' => now()->format('d/m/Y')
            ]);
            $filename = 'contrats/credit_'.$credit->id.'_'.now()->format('Ymd_His').'.pdf';
            \Storage::disk('public')->put($filename, $pdf->output());
            $credit->contract_path = $filename;
            $credit->save();
        } catch (\Throwable $e) { \Log::error('Erreur génération contrat #'.$credit->id.': '.$e->getMessage()); }
        if ($credit->adherent?->user) {
            Notification::create([
                'user_id' => $credit->adherent->user->id,
                'titre' => 'Crédit approuvé',
                'message' => "Votre demande de crédit a été approuvée. Merci de vous rendre à votre agence pour signer le contrat.",
                'lu' => false,
                'type' => 'info',
            ]);
        }
        return $request->wantsJson()
            ? response()->json(['message' => 'Crédit approuvé. Contrat généré et notification envoyée.'])
            : redirect()->route('admin.credits.show', $credit)->with('success','Crédit approuvé. Contrat généré et notification envoyée.');
    }

    public function reject(Request $request, Credit $credit)
    {
        $this->authorize('reject', $credit);
        $data = $request->validate(['motif_rejet' => ['required','string']]);
        if (!in_array($credit->etat, ['en_examen','soumis'])) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Crédit non éligible pour rejet.'], 422)
                : redirect()->back()->with('error', 'Crédit non éligible pour rejet.');
        }
        $credit->statut = 'rejeté';
        $credit->etat = 'rejete';
        $credit->motif_rejet = $data['motif_rejet'];
        $credit->validated_by_agent_id = $request->user()->id ?? null;
        $credit->save();
        if ($credit->adherent?->user) {
            Notification::create([
                'user_id' => $credit->adherent->user->id,
                'titre' => 'Demande de crédit rejetée',
                'message' => 'Votre demande de crédit a été rejetée. Motif: '.$data['motif_rejet'],
                'lu' => false,
                'type' => 'alert',
            ]);
        }
        return $request->wantsJson()
            ? response()->json(['message' => 'Crédit rejeté. Notification envoyée.'])
            : redirect()->route('admin.credits.show', $credit)->with('success','Crédit rejeté.');
    }

    public function markRepaid(Request $request, Credit $credit)
    {
        $this->authorize('approve', $credit);
        // Marquer le crédit comme remboursé / clôturé
        $credit->statut = 'remboursé';
        $credit->etat = 'cloture';
        $credit->save();

        if ($credit->adherent?->user) {
            Notification::create([
                'user_id' => $credit->adherent->user->id,
                'titre' => 'Crédit remboursé',
                'message' => "Votre crédit #{$credit->id} a été marqué comme remboursé. Merci pour votre confiance.",
                'lu' => false,
                'type' => 'success',
            ]);
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'Crédit marqué comme remboursé.'])
            : redirect()->route('admin.credits.show', $credit)->with('success','Crédit marqué comme remboursé.');
    }

    public function exportContract(Credit $credit, Request $request)
    {
        $this->authorize('view', $credit);
        $credit->load('adherent.user','adherent');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backoffice.credits.exports.contract-pdf', [
            'credit' => $credit,
            'date' => now()->format('d/m/Y')
        ]);
        return $pdf->download('contrat_credit_'.$credit->id.'_'.now()->format('Y-m-d').'.pdf');
    }

    public function downloadStoredContract(Credit $credit)
    {
        $this->authorize('view', $credit);
        if (!$credit->contract_path || !\Storage::disk('public')->exists($credit->contract_path)) {
            return redirect()->back()->with('error', 'Contrat introuvable. Veuillez régénérer le PDF.');
        }
        return \Storage::disk('public')->download($credit->contract_path);
    }

    public function index(Request $request)
    {
        $query = Credit::with(['adherent']);
        if ($request->has('statut')) $query->where('statut',$request->statut);
        if ($request->has('etat')) $query->where('etat',$request->etat);
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id','like',"%{$search}%")
                  ->orWhere('montant_demande','like',"%{$search}%")
                  ->orWhere('statut','like',"%{$search}%")
                  ->orWhereHas('adherent', function($q) use ($search) {
                      $q->where('nom','like',"%{$search}%")
                        ->orWhere('prenom','like',"%{$search}%")
                        ->orWhere('telephone','like',"%{$search}%");
                  });
            });
        }
        $items = $query->latest()->paginate(20);
        return $request->wantsJson() ? response()->json($items) : view('backoffice.credits.index', compact('items'));
    }

    public function show(Credit $credit)
    {
        $credit->load(['adherent.user']);
        return view('backoffice.credits.show', compact('credit'));
    }

    public function indexForAdherent()
    {
        $adherent = auth()->user()->adherent;
        if (!$adherent) return redirect()->route('adherent.inscription');
        $credits = $adherent->credits()->latest()->paginate(10);
        return view('adherent.credits.index', compact('credits'));
    }

    public function showForAdherent(Credit $credit)
    {
        $this->authorize('view', $credit);
        $paymentsEnabled = Schema::hasTable('paiement_credits');
        if ($paymentsEnabled) {
            $credit->load(['paiements.preuves']);
        }
        return view('adherent.credits.show', compact('credit','paymentsEnabled'));
    }
}
