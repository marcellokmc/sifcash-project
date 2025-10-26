<?php

namespace App\Http\Controllers;

use App\Models\PenaliteRetraitAnticipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenaliteRetraitAnticipeController extends Controller
{
    /**
     * Afficher la liste des pénalités de retrait anticipé
     */
    public function index()
    {
        $penalites = PenaliteRetraitAnticipe::latest()->paginate(10);
        return view('backoffice.penalites.index', compact('penalites'));
    }

    /**
     * Afficher le formulaire de modification d'une pénalité
     */
    public function edit(PenaliteRetraitAnticipe $penalite)
    {
        return view('backoffice.penalites.edit', compact('penalite'));
    }

    /**
     * Mettre à jour une pénalité
     */
    public function update(Request $request, PenaliteRetraitAnticipe $penalite)
    {
        $validator = Validator::make($request->all(), [
            'pourcentage_capital_requis' => 'required|numeric|min:0|max:100',
            'duree_preavis_jours' => 'required|integer|min:0',
            'taux_penalite' => 'required|numeric|min:0|max:100',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $penalite->update([
            'pourcentage_capital_requis' => $request->pourcentage_capital_requis,
            'duree_preavis_jours' => $request->duree_preavis_jours,
            'taux_penalite' => $request->taux_penalite,
            'actif' => $request->has('actif'),
        ]);

        return redirect()->route('admin.penalites.index')
            ->with('success', 'Pénalité mise à jour avec succès');
    }

    /**
     * Activer une pénalité
     */
    public function activate(PenaliteRetraitAnticipe $penalite)
    {
        $penalite->update(['actif' => true]);
        return redirect()->back()->with('success', 'Pénalité activée avec succès');
    }

    /**
     * Désactiver une pénalité
     */
    public function deactivate(PenaliteRetraitAnticipe $penalite)
    {
        $penalite->update(['actif' => false]);
        return redirect()->back()->with('success', 'Pénalité désactivée avec succès');
    }
}