<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commercial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommercialController extends Controller
{
    public function index(Request $request)
    {
        // Exportation Excel si demandé
        if ($request->get('export') === 'excel') {
            return $this->exportCommercials($request);
        }

        $query = Commercial::withCount('adherents');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('code_commercial', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }
        
        $commercials = $query->orderBy('nom')->paginate(10);
        
        return view('backoffice.commercials.index', compact('commercials'));
    }

    public function create()
    {
        return view('backoffice.commercials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => 'required|string|unique:commercials,telephone|max:20',
            'code_commercial' => 'required|string|unique:commercials,code_commercial|max:10',
            'actif' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        $validated['actif'] = $request->has('actif');
        
        Commercial::create($validated);
        
        return redirect()
            ->route('admin.commercials.index')
            ->with('success', 'Commercial créé avec succès.');
    }

    public function show(Commercial $commercial, Request $request)
    {
        // Exportation Excel si demandé
        if ($request->get('export') === 'excel') {
            return $this->exportAdherents($commercial, $request);
        }

        // Charger les adhérents avec filtres multicritères
        $query = $commercial->adherents()->getQuery();
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut_compte', $request->statut);
        }
        
        // Filtre par nom/prénom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
        
        // Filtre par date d'inscription
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        // Filtre par résidence
        if ($request->filled('residence')) {
            $query->where('residence', 'like', "%{$request->residence}%");
        }
        
        // Filtre par profession
        if ($request->filled('profession')) {
            $query->where('profession', 'like', "%{$request->profession}%");
        }
        
        // Trier les résultats
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $adherents = $query->paginate(15)->withQueryString();
        
        // Statistiques pour les filtres
        $stats = [
            'total' => $commercial->adherents()->count(),
            'actifs' => $commercial->adherents()->where('statut_compte', 'actif')->count(),
            'en_attente' => $commercial->adherents()->where('statut_compte', 'en_attente_de_verification')->count(),
            'suspendus' => $commercial->adherents()->where('statut_compte', 'suspendu')->count(),
        ];
        
        return view('backoffice.commercials.show', compact('commercial', 'adherents', 'stats'));
    }

    /**
     * Exporter les adhérents d'un commercial en Excel
     */
    private function exportAdherents(Commercial $commercial, Request $request)
    {
        // Appliquer les mêmes filtres que dans la méthode show
        $query = $commercial->adherents()->getQuery();
        
        if ($request->filled('statut')) {
            $query->where('statut_compte', $request->statut);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        if ($request->filled('residence')) {
            $query->where('residence', 'like', "%{$request->residence}%");
        }
        
        if ($request->filled('profession')) {
            $query->where('profession', 'like', "%{$request->profession}%");
        }
        
        // Trier les résultats
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $adherents = $query->get();
        
        // Créer le fichier CSV
        $filename = "adherents_commercial_{$commercial->code_commercial}_" . date('Y-m-d_H-i') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($adherents) {
            $file = fopen('php://output', 'w');
            
            // Ajouter le BOM pour l'encodage UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénom',
                'Téléphone',
                'Email',
                'Résidence',
                'Secteur',
                'Profession',
                'Date de naissance',
                'Lieu de naissance',
                'Situation familiale',
                'Date d\'inscription',
                'Statut du compte',
                'Commercial',
                'Code Commercial'
            ]);
            
            // Données
            foreach ($adherents as $adherent) {
                fputcsv($file, [
                    $adherent->id,
                    $adherent->nom ?? '',
                    $adherent->prenom ?? '',
                    $adherent->telephone ?? '',
                    $adherent->email ?? '',
                    $adherent->residence ?? '',
                    $adherent->secteur_numero ?? '',
                    $adherent->profession ?? '',
                    $adherent->date_naissance ? $adherent->date_naissance->format('d/m/Y') : '',
                    $adherent->lieu_naissance ?? '',
                    $adherent->situation_famille ?? '',
                    $adherent->created_at ? $adherent->created_at->format('d/m/Y H:i') : '',
                    $adherent->statut_compte ?? '',
                    $adherent->commercial ? $adherent->commercial->nom_complet : '',
                    $adherent->commercial ? $adherent->commercial->code_commercial : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function edit(Commercial $commercial)
    {
        return view('backoffice.commercials.edit', compact('commercial'));
    }

    public function update(Request $request, Commercial $commercial)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'telephone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('commercials', 'telephone')->ignore($commercial->id)
            ],
            'code_commercial' => [
                'required',
                'string',
                'max:10',
                Rule::unique('commercials', 'code_commercial')->ignore($commercial->id)
            ],
            'actif' => 'boolean',
            'notes' => 'nullable|string|max:1000'
        ]);

        $validated['actif'] = $request->has('actif');
        
        $commercial->update($validated);
        
        return redirect()
            ->route('admin.commercials.index')
            ->with('success', 'Commercial mis à jour avec succès.');
    }

    public function destroy(Commercial $commercial)
    {
        if ($commercial->adherents()->exists()) {
            return redirect()
                ->route('admin.commercials.index')
                ->with('error', 'Impossible de supprimer ce commercial car il a des adhérents associés.');
        }
        
        $commercial->delete();
        
        return redirect()
            ->route('admin.commercials.index')
            ->with('success', 'Commercial supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un commercial
     */
    public function toggle(Commercial $commercial)
    {
        $commercial->actif = !$commercial->actif;
        $commercial->save();
        
        $status = $commercial->actif ? 'activé' : 'désactivé';
        
        return redirect()
            ->route('admin.commercials.index')
            ->with('success', "Commercial {$status} avec succès.");
    }

    /**
     * Exporter la liste des commerciaux en Excel
     */
    private function exportCommercials(Request $request)
    {
        $query = Commercial::withCount('adherents');
        
        // Appliquer les mêmes filtres que dans la méthode index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('code_commercial', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }
        
        $commercials = $query->orderBy('nom')->get();
        
        // Créer le fichier CSV
        $filename = "commercials_" . date('Y-m-d_H-i') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($commercials) {
            $file = fopen('php://output', 'w');
            
            // Ajouter le BOM pour l'encodage UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Nom Complet',
                'Téléphone',
                'Code Commercial',
                'Statut',
                'Nombre d\'adhérents',
                'Notes',
                'Date de création',
                'Dernière modification'
            ]);
            
            // Données
            foreach ($commercials as $commercial) {
                fputcsv($file, [
                    $commercial->id,
                    $commercial->nom ?? '',
                    $commercial->prenoms ?? '',
                    $commercial->nom_complet ?? '',
                    $commercial->telephone ?? '',
                    $commercial->code_commercial ?? '',
                    $commercial->actif ? 'Actif' : 'Inactif',
                    $commercial->adherents_count ?? 0,
                    $commercial->notes ?? '',
                    $commercial->created_at ? $commercial->created_at->format('d/m/Y H:i') : '',
                    $commercial->updated_at ? $commercial->updated_at->format('d/m/Y H:i') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
