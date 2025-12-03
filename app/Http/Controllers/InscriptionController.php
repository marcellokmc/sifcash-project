<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\AyantDroit;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class InscriptionController extends Controller
{
    /**
     * Afficher le formulaire d'inscription étape par étape
     */
    public function showInscriptionForm(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;
        
        $step = $request->get('step', 1);
        $typesDocuments = TypeDocument::where('actif', true)->get();
        
        // Charger les commerciaux pour l'étape 1
        $commercials = [];
        if ($step == 1) {
            $commercials = \App\Models\Commercial::actifs()
                ->orderBy('nom')
                ->get(['id', 'nom', 'prenoms', 'code_commercial', 'telephone']);
        }
        
        return view('adherent.inscription.steps.step' . $step, compact('adherent', 'typesDocuments', 'step', 'commercials'));
    }

    /**
     * Sauvegarder le profil adhérent (Étape 1)
     */
    public function saveProfile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'adresse' => 'required|string',
            'telephone' => 'required|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'commercial_id' => 'nullable|exists:commercials,id',
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
            return redirect()->route('adherent.inscription', ['step' => 1])
                ->withErrors($validator)
                ->withInput();
        }

        // Mapper les données du formulaire vers les noms de colonnes de la base de données
        $adherentData = [
            'user_id' => $user->id,
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
            'statut_compte' => 'en_attente_de_verification',
            'commercial_id' => $request->commercial_id,
            // Mapping des champs contact d'urgence vers les vrais noms de colonnes
            'contact_urgence_nom' => $request->contact_urgence_nom,
            'contact_urgence_lien_parente' => $request->contact_urgence_lien,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
            'contact_urgence_secondaire_nom' => $request->contact_urgence_2_nom,
            'contact_urgence_secondaire_lien_parente' => $request->contact_urgence_2_lien,
            'contact_urgence_secondaire_telephone' => $request->contact_urgence_2_telephone,
        ];

        if ($user->adherent) {
            $user->adherent->update($adherentData);
        } else {
            Adherent::create($adherentData);
        }

        return redirect()->route('adherent.inscription', ['step' => 2])
            ->with('success', 'Profil enregistré avec succès.');
    }

    /**
     * Sauvegarder les ayants droit (Étape 2)
     */
    public function saveAyantsDroit(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription', ['step' => 1])
                ->with('error', 'Veuillez d\'abord compléter votre profil.');
        }

        $ayantsDroitData = $request->get('ayants_droit', []);

        // Supprimer les anciens ayants droit
        $adherent->ayantsDroit()->delete();

        // Ajouter les nouveaux ayants droit
        foreach ($ayantsDroitData as $ayantData) {
            if (!empty($ayantData['nom']) && !empty($ayantData['prenom'])) {
                AyantDroit::create([
                    'adherent_id' => $adherent->id,
                    'nom' => $ayantData['nom'],
                    'prenom' => $ayantData['prenom'],
                    'date_naissance' => $ayantData['date_naissance'] ?? null,
                    'lien_parente' => $ayantData['lien_parente'],
                    'contact' => $ayantData['contact'] ?? null,
                    'type_beneficiaire' => $ayantData['type_beneficiaire'],
                    'statut_validation' => 'en_attente',
                ]);
            }
        }

        return redirect()->route('adherent.inscription', ['step' => 3])
            ->with('success', 'Ayants droit enregistrés avec succès.');
    }
    /**
     * Sauvegarder les documents (Étape 3)
     */
    public function saveDocuments(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription', ['step' => 1])
                ->with('error', 'Veuillez d\'abord compléter votre profil.');
        }

        $documentsData = $request->file('documents', []);

        // Validation des fichiers uploadés
        $validatedDocuments = [];
        foreach ($documentsData as $typeDocumentId => $files) {
            $rectoFile = $files['recto'] ?? null;
            $versoFile = $files['verso'] ?? null;
            
            // Vérifier si au moins un fichier est présent pour ce type de document
            if (!$rectoFile && !$versoFile) {
                continue; // Ignorer ce type de document si aucun fichier n'est uploadé
            }
            
            // Valider le fichier recto
            if ($rectoFile && $rectoFile->isValid()) {
                // Vérifier la taille du fichier (max 5MB)
                if ($rectoFile->getSize() > 5 * 1024 * 1024) {
                    return redirect()->route('adherent.inscription', ['step' => 3])
                        ->withInput()
                        ->with('error', "Le fichier recto est trop volumineux (max 5MB).");
                }
                
                // Vérifier le type de fichier
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!in_array($rectoFile->getMimeType(), $allowedMimes)) {
                    return redirect()->route('adherent.inscription', ['step' => 3])
                        ->withInput()
                        ->with('error', "Le fichier recto n'est pas dans un format valide (JPG, PNG, PDF uniquement).");
                }
                
                $validatedDocuments[$typeDocumentId]['recto'] = $rectoFile;
            } elseif ($rectoFile) {
                return redirect()->route('adherent.inscription', ['step' => 3])
                    ->withInput()
                    ->with('error', "Le fichier recto n'est pas valide ou est corrompu.");
            }
            
            // Valider le fichier verso
            if ($versoFile && $versoFile->isValid()) {
                // Vérifier la taille du fichier (max 5MB)
                if ($versoFile->getSize() > 5 * 1024 * 1024) {
                    return redirect()->route('adherent.inscription', ['step' => 3])
                        ->withInput()
                        ->with('error', "Le fichier verso est trop volumineux (max 5MB).");
                }
                
                // Vérifier le type de fichier
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!in_array($versoFile->getMimeType(), $allowedMimes)) {
                    return redirect()->route('adherent.inscription', ['step' => 3])
                        ->withInput()
                        ->with('error', "Le fichier verso n'est pas dans un format valide (JPG, PNG, PDF uniquement).");
                }
                
                $validatedDocuments[$typeDocumentId]['verso'] = $versoFile;
            } elseif ($versoFile) {
                return redirect()->route('adherent.inscription', ['step' => 3])
                    ->withInput()
                    ->with('error', "Le fichier verso n'est pas valide ou est corrompu.");
            }
        }

        if (empty($validatedDocuments)) {
            return redirect()->route('adherent.inscription', ['step' => 3])
                ->withInput()
                ->with('error', 'Veuillez sélectionner au moins un fichier à uploader.');
        }

        // Détection des types "document d'identité" par mots-clés (sans changer le schéma DB)
        $allTypesActifs = TypeDocument::where('actif', true)->get();
        $identityTypeIds = $allTypesActifs->filter(function($t){
            $name = mb_strtolower($t->nom);
            return str_contains($name, 'identit') || str_contains($name, 'cni') || str_contains($name, 'passeport') || str_contains($name, 'permis');
        })->pluck('id')->all();

        // Vérifier que l'utilisateur ne choisit qu'un seul document d'identité et au moins un si des types existent
        if (!empty($identityTypeIds)) {
            $selectedIdentityIds = [];
            foreach ($validatedDocuments as $typeId => $files) {
                if (in_array((int)$typeId, $identityTypeIds, true)) {
                    $hasRecto = !empty($files['recto']);
                    $hasVerso = !empty($files['verso']);
                    if ($hasRecto || $hasVerso) {
                        $selectedIdentityIds[] = (int)$typeId;
                    }
                }
            }
            $selectedIdentityIds = array_values(array_unique($selectedIdentityIds));
            if (count($selectedIdentityIds) === 0) {
                return redirect()->route('adherent.inscription', ['step' => 3])
                    ->withInput()
                    ->with('error', 'Veuillez choisir un seul document d\'identité et téléverser le(s) fichier(s) requis (recto et verso si nécessaire).');
            }
            if (count($selectedIdentityIds) > 1) {
                return redirect()->route('adherent.inscription', ['step' => 3])
                    ->withInput()
                    ->with('error', 'Vous avez sélectionné plusieurs documents d\'identité. Veuillez n\'en choisir qu\'un seul.');
            }
        }

        foreach ($validatedDocuments as $typeDocumentId => $files) {
            $typeDocument = TypeDocument::find($typeDocumentId);
            
            if (!$typeDocument) continue;

            $rectoFile = $files['recto'] ?? null;
            $versoFile = $files['verso'] ?? null;

            if ($rectoFile) {
                // Vérifier si un document de ce type existe déjà
                $existingDocument = $adherent->documents()
                    ->where('type_document_id', $typeDocumentId)
                    ->first();

                $version = $existingDocument ? $existingDocument->version + 1 : 1;

                try {
                    // Upload des fichiers avec validation supplémentaire
                    $rectoPath = $rectoFile->store('documents', 'public');
                    if (!$rectoPath) {
                        throw new \Exception('Erreur lors du stockage du fichier recto.');
                    }
                    
                    $versoPath = null;
                    if ($versoFile) {
                        $versoPath = $versoFile->store('documents', 'public');
                        if (!$versoPath) {
                            throw new \Exception('Erreur lors du stockage du fichier verso.');
                        }
                    }

                    // Vérification des champs requis
                    if ($typeDocument->verso_requis && !$versoPath) {
                        continue; // Passer au document suivant
                    }

                    // Créer le document
                    $adherent->documents()->create([
                        'type_document_id' => $typeDocumentId,
                        'fichier_recto' => $rectoPath,
                        'fichier_verso' => $versoPath,
                        'statut' => 'soumis',
                        'version' => $version,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'upload du document: ' . $e->getMessage());
                    return redirect()->route('adherent.inscription', ['step' => 3])
                        ->withInput()
                        ->with('error', 'Erreur lors du téléversement du fichier. Veuillez réessayer.');
                }
            }
        }

        // Vérifier la présence des documents OBLIGATOIRES (au moins soumis)
        $typesObligatoires = TypeDocument::where('actif', true)->where('obligatoire', true)->get();
        $manquants = [];
        foreach ($typesObligatoires as $type) {
            $existe = $adherent->documents()
                ->where('type_document_id', $type->id)
                ->whereIn('statut', ['soumis','validé'])
                ->exists();
            if (!$existe) {
                $manquants[] = ['id' => $type->id, 'nom' => $type->nom];
            }
        }

        if (!empty($manquants)) {
            $noms = array_map(function($t){ return $t['nom']; }, $manquants);
            return redirect()->route('adherent.inscription', ['step' => 3])
                ->with('error', 'Certains documents obligatoires manquent: '.implode(', ', $noms).'. Veuillez les téléverser pour finaliser votre inscription.')
                ->with('missing_types', $manquants);
        }

        return redirect()->route('adherent.dashboard')
            ->with('success', 'Inscription terminée ! Vos documents sont en attente de validation.');
    }
}