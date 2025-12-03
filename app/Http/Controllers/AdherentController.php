<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\User;
use App\Models\Agence;
use App\Models\Commercial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdherentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Adherent::with([
                'user.agence', 
                'agence',
                'agentGestionnaire',
                'agents',
                'ayantsDroit', 
                'documents.typeDocument',
                'adhesions.plan',
                'suspendedByUser'
            ])
            ->withCount(['ayantsDroit', 'documents', 'adhesions']);

        // Filtre par agent si l'utilisateur est un agent (middleware FilterAdherentsByAgent)
        if ($request->filled('agent_filter')) {
            $query->forAgent($request->agent_filter);
        }
        // Filtre par agence si chef de service (périmètre agence)
        if ($request->filled('agence_filter')) {
            $agenceId = (int) $request->integer('agence_filter');
            $query->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        } elseif (auth()->user() && auth()->user()->isChefService() && auth()->user()->agence_id) {
            // Fallback automatique si le middleware n'a pas injecté le filtre
            $agenceId = (int) auth()->user()->agence_id;
            $query->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        }

        // Recherche par nom/prénom
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$search}%"])
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('membre_id', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par téléphone
        if ($request->filled('phone')) {
            $query->where('telephone', 'LIKE', '%' . $request->get('phone') . '%');
        }

        // Filtre par email
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
        }

        // Filtre par statut
        if ($request->filled('status')) {
            $status = $request->get('status');
            switch($status) {
                case 'actif':
                    $query->where('statut_compte', 'actif');
                    break;
                case 'en_attente':
                    $query->where('statut_compte', 'en_attente');
                    break;
                case 'inactif':
                    $query->where('statut_compte', 'inactif');
                    break;
            }
        }

        // Filtre par profession
        if ($request->filled('profession')) {
            $query->where('profession', 'LIKE', '%' . $request->get('profession') . '%');
        }

        // Filtre par agence (filtre explicite)
        if ($request->filled('agence')) {
            $agenceId = (int) $request->get('agence');
            $query->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        }

        // Filtre par affectation
        if ($request->filled('affectation')) {
            $affectation = $request->get('affectation');
            if ($affectation === 'non_affecte') {
                $query->where(function($q) {
                    $q->whereNull('agence_id')
                      ->orWhereNull('agent_gestionnaire_id');
                });
            } elseif ($affectation === 'affecte') {
                $query->whereNotNull('agence_id')
                      ->whereNotNull('agent_gestionnaire_id');
            }
        }

        $adherents = $query->latest()->paginate(15)->withQueryString();

        // Statistiques pour les badges (scopées au périmètre agent/agence)
        $scope = Adherent::query();
        if ($request->filled('agent_filter')) {
            $scope->forAgent((int) $request->agent_filter);
        }
        if ($request->filled('agence_filter')) {
            $agenceId = (int) $request->integer('agence_filter');
            $scope->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        } elseif (auth()->user() && auth()->user()->isChefService() && auth()->user()->agence_id) {
            $agenceId = (int) auth()->user()->agence_id;
            $scope->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        }
        $stats = [
            'total' => (clone $scope)->count(),
            'actifs' => (clone $scope)->where('statut_compte', 'actif')->count(),
            'en_attente' => (clone $scope)->where('statut_compte', 'en_attente')->count(),
            'inactifs' => (clone $scope)->where('statut_compte', 'inactif')->count(),
            'documents_attente' => (clone $scope)->whereHas('documents', function($q) {
                $q->where('statut', 'soumis');
            })->count(),
            'ayants_droit_attente' => (clone $scope)->whereHas('ayantsDroit', function($q) {
                $q->where('statut_validation', 'en_attente');
            })->count()
        ];

        // Liste des agences pour le filtre
        if (auth()->user()->isChefService()) {
            $agences = \App\Models\Agence::where('active', true)
                ->where('id', auth()->user()->agence_id)
                ->orderBy('nom')->get();
        } else {
            $agences = \App\Models\Agence::where('active', true)->orderBy('nom')->get();
        }

        // Liste des professions pour le filtre
        $professions = Adherent::select('profession')
            ->whereNotNull('profession')
            ->where('profession', '!=', '')
            ->distinct()
            ->orderBy('profession')
            ->pluck('profession');

        // Gestion de l'export
        if ($request->has('export')) {
            return $this->export($request);
        }

        return view('backoffice.adherents.index', compact('adherents', 'stats', 'agences', 'professions'));
    }

    /**
     * Export des adhérents
     */
    public function export(Request $request)
    {
        $query = Adherent::with(['user.agence', 'ayantsDroit', 'documents', 'adhesions']);

        // Périmètre agent/agence
        if ($request->filled('agent_filter')) {
            $query->forAgent((int) $request->agent_filter);
        }
        if ($request->filled('agence_filter')) {
            $agenceId = (int) $request->integer('agence_filter');
            $query->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        } elseif (auth()->user() && auth()->user()->isChefService() && auth()->user()->agence_id) {
            $agenceId = (int) auth()->user()->agence_id;
            $query->where(function($q) use ($agenceId) {
                $q->where('agence_id', $agenceId)
                  ->orWhereHas('user', function($sub) use ($agenceId) {
                      $sub->where('agence_id', $agenceId);
                  });
            });
        }

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$search}%"])
                  ->orWhere('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('membre_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('phone')) {
            $query->where('telephone', 'LIKE', '%' . $request->get('phone') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->get('email') . '%');
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            switch($status) {
                case 'actif':
                    $query->where('statut_compte', 'actif');
                    break;
                case 'en_attente':
                    $query->where('statut_compte', 'en_attente');
                    break;
                case 'inactif':
                    $query->where('statut_compte', 'inactif');
                    break;
            }
        }

        if ($request->filled('profession')) {
            $query->where('profession', 'LIKE', '%' . $request->get('profession') . '%');
        }

        $adherents = $query->get();
        $format = $request->get('export', 'excel');

        if ($format === 'pdf') {
            return $this->exportPDF($adherents);
        }

        return $this->exportExcel($adherents);
    }

    /**
     * Export Excel (CSV)
     */
    private function exportExcel($adherents)
    {
        $filename = 'adherents_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($adherents) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers CSV
            fputcsv($file, [
                'ID Membre',
                'Nom',
                'Prénom',
                'Date de Naissance',
                'Téléphone',
                'Email',
                'Profession',
                'Résidence',
                'Statut',
                'Ayants Droit',
                'Documents',
                'Adhésions',
                'Date d\'inscription'
            ]);
            
            foreach ($adherents as $adherent) {
                fputcsv($file, [
                    $adherent->membre_id ?? 'N/A',
                    $adherent->nom,
                    $adherent->prenom,
                    $adherent->date_naissance ? $adherent->date_naissance->format('d/m/Y') : 'N/A',
                    $adherent->telephone,
                    $adherent->email ?? 'N/A',
                    $adherent->profession ?? 'N/A',
                    $adherent->residence ?? 'N/A',
                    ucfirst($adherent->statut_compte ?? 'en_attente'),
                    $adherent->ayantsDroit->count(),
                    $adherent->documents->count(),
                    $adherent->adhesions->count(),
                    $adherent->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export PDF
     */
    private function exportPDF($adherents)
    {
        $pdf = Pdf::loadView('backoffice.adherents.export-pdf', [
            'adherents' => $adherents,
            'date' => now()->format('d/m/Y H:i')
        ]);
        
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('adherents_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'adherent')
            ->whereDoesntHave('adherent')
            ->with('agence')
            ->get();
        $agences = Agence::where('active', true)
            ->orderBy('nom')
            ->get();

        return view('backoffice.adherents.create', compact('users', 'agences'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:adherents,user_id',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email',
            // Contact d'urgence principal
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_lien' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20',
            // Contact d'urgence secondaire
            'contact_urgence_2_nom' => 'nullable|string|max:255',
            'contact_urgence_2_lien' => 'nullable|string|max:255',
            'contact_urgence_2_telephone' => 'nullable|string|max:20',
            'residence' => 'required|string|max:255',
            'secteur_numero' => 'nullable|integer|min:1',
            'profession_exercee' => 'nullable|string|max:255',
            'situation_famille' => 'required|in:marié,celibataire,veuf/veuve,divorcé',
            'profession' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mapper les données vers les vrais noms de colonnes
        $adherentData = [
            'user_id' => $request->user_id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'residence' => $request->residence,
            'secteur_numero' => $request->secteur_numero,
            'profession_exercee' => $request->profession_exercee,
            'situation_famille' => $request->situation_famille,
            'profession' => $request->profession,
            // Mapping des champs contact d'urgence
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_lien_parente' => $request->contact_urgence_lien,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'contact_urgence_secondaire_nom' => $request->contact_urgence_2_nom,
            'contact_urgence_secondaire_lien_parente' => $request->contact_urgence_2_lien,
            'contact_urgence_secondaire_telephone' => $request->contact_urgence_2_telephone,
        ];

        $adherent = Adherent::create($adherentData);

        return redirect()->route('admin.adherents.show', $adherent)
            ->with('success', 'Adhérent créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Adherent $adherent)
    {
        $adherent->load([
            'user.agence', 
            'ayantsDroit' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'documents.typeDocument', 
            'adhesions.plan',
            'adhesions.renouvellements',
'credits' => function($query) {
                $query->latest();
            }
        ]);

        return view('backoffice.adherents.show', compact('adherent'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Adherent $adherent)
    {
        $agences = Agence::where('active', true)->get();
        $commercials = Commercial::where('actif', true)->orderBy('nom')->get();

        return view('backoffice.adherents.edit', compact('adherent', 'agences', 'commercials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Adherent $adherent)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email',
            // Contact d'urgence principal
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_lien' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20',
            // Contact d'urgence secondaire
            'contact_urgence_2_nom' => 'nullable|string|max:255',
            'contact_urgence_2_lien' => 'nullable|string|max:255',
            'contact_urgence_2_telephone' => 'nullable|string|max:20',
            'residence' => 'required|string|max:255',
            'secteur_numero' => 'nullable|integer|min:1',
            'profession_exercee' => 'nullable|string|max:255',
            'situation_famille' => 'required|in:marié,celibataire,veuf/veuve,divorcé',
            'profession' => 'required|string|max:255',
            'statut_compte' => 'required|in:actif,inactif,en_attente_de_verification',
            'commercial_id' => 'nullable|exists:commercials,id',
            'date_affectation_commercial' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mapper les données vers les vrais noms de colonnes
        $adherentData = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'residence' => $request->residence,
            'secteur_numero' => $request->secteur_numero,
            'profession_exercee' => $request->profession_exercee,
            'situation_famille' => $request->situation_famille,
            'profession' => $request->profession,
            'statut_compte' => $request->statut_compte,
            'commercial_id' => $request->commercial_id,
            'date_affectation_commercial' => $request->date_affectation_commercial,
            // Mapping des champs contact d'urgence
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_lien_parente' => $request->contact_urgence_lien,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'contact_urgence_secondaire_nom' => $request->contact_urgence_2_nom,
            'contact_urgence_secondaire_lien_parente' => $request->contact_urgence_2_lien,
            'contact_urgence_secondaire_telephone' => $request->contact_urgence_2_telephone,
        ];

        $adherent->update($adherentData);

        return redirect()->route('admin.adherents.show', $adherent)
            ->with('success', 'Adhérent modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Adherent $adherent)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->canManageAdherent($adherent)) {
            abort(403);
        }

        // Vérifier s'il y a des relations avant suppression
        if ($adherent->ayantsDroit()->count() > 0 || $adherent->documents()->count() > 0) {
            return redirect()->route('admin.adherents.index')
                ->with('error', 'Impossible de supprimer cet adhérent car il a des ayants droit ou documents associés.');
        }

        $adherent->delete();

        return redirect()->route('admin.adherents.index')
            ->with('success', 'Adhérent supprimé avec succès.');
    }

    /**
     * Activer un compte adhérent
     */
    public function activate(Adherent $adherent)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->canManageAdherent($adherent)) {
            abort(403);
        }

        $adherent->update([
            'statut_compte' => 'actif',
            'date_activation' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Compte adhérent activé avec succès.');
    }

    /**
     * Désactiver un compte adhérent
     */
    public function deactivate(Adherent $adherent)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->canManageAdherent($adherent)) {
            abort(403);
        }

        $adherent->update([
            'statut_compte' => 'inactif'
        ]);

        return redirect()->back()
            ->with('success', 'Compte adhérent désactivé avec succès.');
    }

    /**
     * Afficher le profil adhérent (frontend)
     */
    public function showProfile()
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('info', 'Veuillez compléter votre profil d\'adhérent.');
        }

        $adherent->load([
            'ayantsDroit' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'documents.typeDocument',
            'adhesions.plan',
            'credits' => function($query) {
                $query->latest()->limit(5);
            },
            'commercial'
        ]);

        return view('adherent.profile.show', compact('adherent'));
    }

    /**
     * Afficher le formulaire d'édition du profil (frontend)
     */
    public function editProfile()
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription');
        }

        return view('adherent.profile.edit', compact('adherent'));
    }

    /**
     * Mettre à jour le profil adhérent (frontend)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            // Contact d'urgence principal
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_lien' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20',
            // Contact d'urgence secondaire
            'contact_urgence_2_nom' => 'nullable|string|max:255',
            'contact_urgence_2_lien' => 'nullable|string|max:255',
            'contact_urgence_2_telephone' => 'nullable|string|max:20',
            'residence' => 'required|string|max:255',
            'secteur_numero' => 'nullable|integer|min:1',
            'profession_exercee' => 'nullable|string|max:255',
            'situation_famille' => 'required|in:marié,celibataire,veuf/veuve,divorcé',
            'profession' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mapper les données vers les vrais noms de colonnes
        $adherentData = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
            'telephone_secondaire' => $request->telephone_secondaire,
            'email' => $request->email,
            'residence' => $request->residence,
            'secteur_numero' => $request->secteur_numero,
            'profession_exercee' => $request->profession_exercee,
            'situation_famille' => $request->situation_famille,
            'profession' => $request->profession,
            // Mapping des champs contact d'urgence
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_lien_parente' => $request->contact_urgence_lien,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'contact_urgence_secondaire_nom' => $request->contact_urgence_2_nom,
            'contact_urgence_secondaire_lien_parente' => $request->contact_urgence_2_lien,
            'contact_urgence_secondaire_telephone' => $request->contact_urgence_2_telephone,
        ];

        $adherent->update($adherentData);

        return redirect()->route('adherent.profile')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Télécharger le contrat d'adhésion PDF (frontend)
     */
    public function downloadContract()
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        // Vérifier que l'adhérent est approuvé
        if (!$adherent->isActif()) {
            return redirect()->back()
                ->with('error', 'Votre compte doit être activé pour télécharger le contrat.');
        }

        // Charger les relations nécessaires
        $adherent->load('adhesions.plan', 'user.agence', 'agence', 'agentGestionnaire');

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.contrat-adhesion', compact('adherent'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'contrat-adhesion-' . $adherent->membre_id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Télécharger le contrat d'adhésion PDF depuis le backoffice
     */
    public function downloadContractAdmin(Adherent $adherent)
    {
        // Charger les relations nécessaires
        $adherent->load('adhesions.plan', 'user.agence', 'agence', 'agentGestionnaire');

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.contrat-adhesion', compact('adherent'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'contrat-adhesion-' . $adherent->membre_id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Afficher le formulaire d'affectation d'agence et d'agent
     */
    public function showAffectationForm(Adherent $adherent)
    {
        $auth = auth()->user();
        if ($auth->isChefService() && $adherent->agence_id && $adherent->agence_id !== $auth->agence_id) {
            abort(403);
        }

        if ($auth->isChefService()) {
            $agences = Agence::where('active', true)
                ->where('id', $auth->agence_id)
                ->orderBy('nom')->get();
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->where('agence_id', $auth->agence_id)
                ->with('agence')
                ->orderBy('name')
                ->get();
        } else {
            $agences = Agence::where('active', true)->orderBy('nom')->get();
            
            // Récupérer les agents (utilisateurs avec rôle agent ou chef_service)
            $agents = User::whereIn('role', ['agent', 'chef_service'])
                ->where('active', true)
                ->with('agence')
                ->orderBy('name')
                ->get();
        }
        
        $adherent->load('agence', 'agentGestionnaire.agence');
        
        return view('backoffice.adherents.affectation', compact('adherent', 'agences', 'agents'));
    }

    /**
     * Affecter une agence et/ou un agent à un adhérent
     */
    public function affecterAgenceAgent(Request $request, Adherent $adherent)
    {
        $validator = Validator::make($request->all(), [
            'agence_id' => 'nullable|exists:agences,id',
            'agent_gestionnaire_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'agent appartient bien à un rôle autorisé
        if ($request->filled('agent_gestionnaire_id')) {
            $agent = User::find($request->agent_gestionnaire_id);
            if (!in_array($agent->role, ['agent', 'chef_service', 'admin'])) {
                return redirect()->back()
                    ->with('error', 'L\'utilisateur sélectionné n\'est pas un agent valide.');
            }
        }

        $adherent->update([
            'agence_id' => $request->agence_id,
            'agent_gestionnaire_id' => $request->agent_gestionnaire_id,
        ]);

        return redirect()->route('admin.adherents.show', $adherent)
            ->with('success', 'Affectation mise à jour avec succès.');
    }

    /**
     * Affectation en masse d'adhérents à une agence/agent
     */
    public function affectationMasse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adherent_ids' => 'required|array',
            'adherent_ids.*' => 'exists:adherents,id',
            'agence_id' => 'nullable|exists:agences,id',
            'agent_gestionnaire_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (!$request->filled('agence_id') && !$request->filled('agent_gestionnaire_id')) {
            return redirect()->back()
                ->with('error', 'Vous devez sélectionner au moins une agence ou un agent.');
        }

        $updateData = [];
        if ($request->filled('agence_id')) {
            $updateData['agence_id'] = $request->agence_id;
        }
        if ($request->filled('agent_gestionnaire_id')) {
            $updateData['agent_gestionnaire_id'] = $request->agent_gestionnaire_id;
        }

        DB::beginTransaction();
        try {
            Adherent::whereIn('id', $request->adherent_ids)
                ->update($updateData);
            
            DB::commit();
            
            $count = count($request->adherent_ids);
            return redirect()->back()
                ->with('success', "{$count} adhérent(s) affecté(s) avec succès.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'affectation.');
        }
    }

    /**
     * Liste des adhérents d'un agent
     */
    public function adherentsParAgent(User $agent)
    {
        if (!in_array($agent->role, ['agent', 'chef_service'])) {
            abort(403, 'Cet utilisateur n\'est pas un agent.');
        }

        $adherents = Adherent::where('agent_gestionnaire_id', $agent->id)
            ->with(['agence', 'user'])
            ->withCount(['ayantsDroit', 'documents', 'adhesions'])
            ->latest()
            ->paginate(20);

        return view('backoffice.agents.adherents', compact('agent', 'adherents'));
    }
}
