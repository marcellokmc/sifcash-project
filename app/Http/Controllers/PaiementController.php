<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Adhesion;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource (Backoffice).
     */
    public function index(Request $request)
    {
        $paiements = Paiement::with(['adherent', 'adhesion.plan', 'validatedByAgent'])
            ->latest()
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($paiements);
        }

        return view('backoffice.paiements.index', compact('paiements'));
    }

    /**
     * Display the specified resource (Backoffice).
     */
    public function show(Request $request, Paiement $paiement)
    {
        $paiement->load(['adherent', 'adhesion.plan', 'validatedByAgent']);

        if ($request->wantsJson()) {
            return response()->json($paiement);
        }

        return view('backoffice.paiements.show', compact('paiement'));
    }

    /**
     * Validate a payment (Backoffice).
     */
    public function validatePayment(Request $request, Paiement $paiement)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $paiement->update([
            'statut' => 'validé',
            'date_validation' => now(),
            'validated_by_agent_id' => Auth::id(),
        ]);

        // Créer une notification pour l'adhérent
        Notification::create([
            'user_id' => $paiement->adherent->user_id,
            'titre' => 'Paiement validé',
            'message' => 'Votre paiement de ' . number_format($paiement->montant, 0, ',', ' ') . ' FCFA a été validé.',
            'lu' => false,
            'type' => 'success',
            'action_by_user_id' => Auth::id(),
            'action' => 'validation',
            'entity_type' => 'paiement',
            'entity_id' => $paiement->id,
        ]);

        return redirect()->back()
            ->with('success', 'Paiement validé avec succès.');
    }

    /**
     * Reject a payment (Backoffice).
     */
    public function reject(Request $request, Paiement $paiement)
    {
        $validator = Validator::make($request->all(), [
            'motif_rejet' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $paiement->update([
            'statut' => 'rejeté',
            'motif_rejet' => $request->motif_rejet,
            'date_validation' => now(),
            'validated_by_agent_id' => Auth::id(),
        ]);

        // Créer une notification pour l'adhérent
        Notification::create([
            'user_id' => $paiement->adherent->user_id,
            'titre' => 'Paiement rejeté',
            'message' => 'Votre paiement de ' . number_format($paiement->montant, 0, ',', ' ') . ' FCFA a été rejeté. Motif: ' . $request->motif_rejet,
            'lu' => false,
            'type' => 'error',
            'action_by_user_id' => Auth::id(),
            'action' => 'rejet',
            'entity_type' => 'paiement',
            'entity_id' => $paiement->id,
        ]);

        return redirect()->back()
            ->with('success', 'Paiement rejeté avec succès.');
    }

    /**
     * Download payment proof (Backoffice).
     */
    public function download(Paiement $paiement)
    {
        if (!$paiement->preuve || !Storage::exists($paiement->preuve)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::download($paiement->preuve);
    }

    // ==================== ESPACE ADHÉRENT ====================

    /**
     * Display a listing of the resource (Adhérent).
     */
    public function indexForAdherent(Request $request)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $paiements = Paiement::where('adherent_id', $adherent->id)
            ->with(['adhesion.plan'])
            ->latest()
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($paiements);
        }

        return view('adherent.paiements.index', compact('paiements'));
    }

    /**
     * Show the form for creating a new resource (Adhérent).
     */
    public function createForAdherent()
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $adhesions = Adhesion::where('adherent_id', $adherent->id)
            ->where('statut', 'actif')
            ->with('plan')
            ->get();

        return view('adherent.paiements.create', compact('adhesions'));
    }

    /**
     * Store a newly created resource in storage (Adhérent).
     */
    public function storeForAdherent(Request $request)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $validator = Validator::make($request->all(), [
            'adhesion_id' => 'required|exists:adhesions,id',
            'categorie' => 'required|in:ouverture,cotisation,credit,autre',
            'montant' => 'required|numeric|min:0.01',
            'mode_paiement' => 'required|in:mobile_money,virement,cheque,especes',
            'preuve' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'reference_paiement' => 'nullable|string|max:255',
            'numero_compte_beneficiaire' => 'nullable|string|max:255',
            'banque_emetteur' => 'nullable|string|max:255',
            'reference_cheque' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'adhésion appartient à l'adhérent
        $adhesion = Adhesion::where('id', $request->adhesion_id)
            ->where('adherent_id', $adherent->id)
            ->first();

        if (!$adhesion) {
            return redirect()->back()
                ->with('error', 'Adhésion non trouvée.')
                ->withInput();
        }

        // Upload de la preuve
        $preuvePath = $request->file('preuve')->store('paiements_preuves');

        $paiement = Paiement::create([
            'adhesion_id' => $request->adhesion_id,
            'adherent_id' => $adherent->id,
            'montant' => $request->montant,
            'categorie' => $request->categorie,
            'mode_paiement' => $request->mode_paiement,
            'preuve' => $preuvePath,
            'reference_paiement' => $request->reference_paiement,
            'numero_compte_beneficiaire' => $request->numero_compte_beneficiaire,
            'banque_emetteur' => $request->banque_emetteur,
            'reference_cheque' => $request->reference_cheque,
            'statut' => 'soumis',
            'date_soumission' => now(),
        ]);

        return redirect()->route('adherent.paiements.show', $paiement)
            ->with('success', 'Paiement soumis avec succès. Il sera validé dans les 24-48h.');
    }

    /**
     * Display the specified resource (Adhérent).
     */
    public function showForAdherent(Request $request, Paiement $paiement)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $paiement->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        $paiement->load(['adhesion.plan', 'validatedByAgent']);

        if ($request->wantsJson()) {
            return response()->json($paiement);
        }

        return view('adherent.paiements.show', compact('paiement'));
    }

    /**
     * Show the form for editing the specified resource (Adhérent).
     */
    public function editForAdherent(Paiement $paiement)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $paiement->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($paiement->statut !== 'brouillon') {
            return redirect()->route('adherent.paiements.show', $paiement)
                ->with('error', 'Ce paiement ne peut pas être modifié.');
        }

        $adhesions = Adhesion::where('adherent_id', $adherent->id)
            ->where('statut', 'actif')
            ->with('plan')
            ->get();

        return view('adherent.paiements.edit', compact('paiement', 'adhesions'));
    }

    /**
     * Update the specified resource in storage (Adhérent).
     */
    public function updateForAdherent(Request $request, Paiement $paiement)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $paiement->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($paiement->statut !== 'brouillon') {
            return redirect()->route('adherent.paiements.show', $paiement)
                ->with('error', 'Ce paiement ne peut pas être modifié.');
        }

        $validator = Validator::make($request->all(), [
            'adhesion_id' => 'required|exists:adhesions,id',
            'categorie' => 'required|in:ouverture,cotisation,credit,autre',
            'montant' => 'required|numeric|min:0.01',
            'mode_paiement' => 'required|in:mobile_money,virement,cheque,especes',
            'preuve' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'reference_paiement' => 'nullable|string|max:255',
            'numero_compte_beneficiaire' => 'nullable|string|max:255',
            'banque_emetteur' => 'nullable|string|max:255',
            'reference_cheque' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'adhésion appartient à l'adhérent
        $adhesion = Adhesion::where('id', $request->adhesion_id)
            ->where('adherent_id', $adherent->id)
            ->first();

        if (!$adhesion) {
            return redirect()->back()
                ->with('error', 'Adhésion non trouvée.')
                ->withInput();
        }

        $data = [
            'adhesion_id' => $request->adhesion_id,
            'montant' => $request->montant,
            'categorie' => $request->categorie,
            'mode_paiement' => $request->mode_paiement,
            'reference_paiement' => $request->reference_paiement,
            'numero_compte_beneficiaire' => $request->numero_compte_beneficiaire,
            'banque_emetteur' => $request->banque_emetteur,
            'reference_cheque' => $request->reference_cheque,
        ];

        // Upload de la nouvelle preuve si fournie
        if ($request->hasFile('preuve')) {
            // Supprimer l'ancienne preuve
            if ($paiement->preuve && Storage::exists($paiement->preuve)) {
                Storage::delete($paiement->preuve);
            }
            $data['preuve'] = $request->file('preuve')->store('paiements_preuves');
        }

        $paiement->update($data);

        return redirect()->route('adherent.paiements.show', $paiement)
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    /**
     * Download payment proof (Adhérent).
     */
    public function downloadForAdherent(Paiement $paiement)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $paiement->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        if (!$paiement->preuve || !Storage::exists($paiement->preuve)) {
            abort(404, 'Fichier non trouvé');
        }

        return Storage::download($paiement->preuve);
    }
}
