<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AyantDroitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $adherent = $user->adherent;
        
        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('info', 'Veuillez compléter votre profil d\'adhérent.');
        }

        $ayantsDroit = $adherent->ayantsDroit;
        
        return view('adherent.ayants-droit.index', compact('ayantsDroit', 'adherent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $adherent = $user->adherent;
        
        if (!$adherent) {
            return redirect()->route('adherent.inscription');
        }

        // Vérifier la limite de 2 ayants droit (ne pas compter les ayants droit rejetés)
        if ($adherent->ayantsDroit()->where('statut_validation', '!=', 'rejeté')->count() >= 2) {
            return redirect()->route('adherent.ayants-droit.index')
                ->with('error', 'Vous avez atteint la limite maximale de 2 ayants droit.');
        }

        return view('adherent.ayants-droit.create', compact('adherent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        // Vérifier la limite (ne pas compter les ayants droit rejetés)
        if ($adherent->ayantsDroit()->where('statut_validation', '!=', 'rejeté')->count() >= 2) {
            return redirect()->route('adherent.ayants-droit.index')
                ->with('error', 'Vous avez atteint la limite maximale de 2 ayants droit.');
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lien_parente' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'type_beneficiaire' => 'required|in:vie,deces',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['adherent_id'] = $adherent->id;
        $data['statut_validation'] = 'en_attente'; // Soumis à validation

        AyantDroit::create($data);

        return redirect()->route('adherent.ayants-droit.index')
            ->with('success', 'Ayant droit ajouté avec succès. En attente de validation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AyantDroit $ayantDroit)
    {
        $this->authorize('view', $ayantDroit);
        
        return view('adherent.ayants-droit.show', compact('ayantDroit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AyantDroit $ayantDroit)
    {
        $this->authorize('update', $ayantDroit);

        return view('adherent.ayants-droit.edit', compact('ayantDroit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AyantDroit $ayantDroit)
    {
        $this->authorize('update', $ayantDroit);

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lien_parente' => 'required|string|max:255',
            'contact' => 'nullable|string|max:20',
            'type_beneficiaire' => 'required|in:vie,deces',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Remettre en attente de validation après modification
        $data = $request->all();
        $data['statut_validation'] = 'en_attente';
        $data['validated_by_agent_id'] = null;

        $ayantDroit->update($data);

        return redirect()->route('adherent.ayants-droit.index')
            ->with('success', 'Ayant droit modifié avec succès. En attente de validation.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AyantDroit $ayantDroit)
    {
        $this->authorize('delete', $ayantDroit);

        $ayantDroit->delete();

        return redirect()->route('adherent.ayants-droit.index')
            ->with('success', 'Ayant droit supprimé avec succès.');
    }

    /**
     * Valider un ayant droit (backoffice agent)
     */
    public function validateAyantDroit(AyantDroit $ayantDroit)
    {
        $ayantDroit->update([
            'statut_validation' => 'validé',
            'validated_by_agent_id' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'Ayant droit validé avec succès.');
    }

    /**
     * Rejeter un ayant droit (backoffice agent)
     */
    public function rejectAyantDroit(Request $request, AyantDroit $ayantDroit)
    {
        $validator = Validator::make($request->all(), [
            'motif_rejet' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ayantDroit->update([
            'statut_validation' => 'rejeté',
            'validated_by_agent_id' => auth()->id(),
            'motif_rejet' => $request->motif_rejet,
        ]);

        return redirect()->back()
            ->with('success', 'Ayant droit rejeté avec succès.');
    }

    /**
     * Liste des ayants droit en attente (backoffice)
     */
    public function pendingValidation()
    {
        $ayantsDroit = AyantDroit::with(['adherent.user', 'validateur'])
            ->where('statut_validation', 'en_attente')
            ->latest()
            ->get();

        return view('backoffice.validation.ayants-droit', compact('ayantsDroit'));
    }
}