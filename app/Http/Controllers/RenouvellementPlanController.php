<?php

namespace App\Http\Controllers;

use App\Models\RenouvellementPlan;
use App\Models\Adhesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RenouvellementPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $renouvellements = RenouvellementPlan::with(['adhesion.adherent', 'adhesion.plan', 'effectueParAgent'])
            ->latest()
            ->paginate(20);

        return view('backoffice.renouvellements.index', compact('renouvellements'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RenouvellementPlan $renouvellementPlan)
    {
        $renouvellementPlan->load([
            'adhesion.adherent.user', 
            'adhesion.plan', 
            'effectueParAgent'
        ]);

        return view('backoffice.renouvellements.show', compact('renouvellementPlan'));
    }

    /**
     * Marquer un renouvellement comme terminé
     */
    public function markAsCompleted(RenouvellementPlan $renouvellementPlan)
    {
        if ($renouvellementPlan->isTermine()) {
            return redirect()->back()
                ->with('error', 'Ce renouvellement est déjà terminé.');
        }

        $renouvellementPlan->update([
            'statut' => 'termine',
            'interets_generes' => $renouvellementPlan->getInteretsTheoriquesAttribute(),
        ]);

        return redirect()->back()
            ->with('success', 'Renouvellement marqué comme terminé.');
    }

    /**
     * Annuler un renouvellement
     */
    public function cancel(Request $request, RenouvellementPlan $renouvellementPlan)
    {
        $validator = Validator::make($request->all(), [
            'commentaire' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $renouvellementPlan->update([
            'statut' => 'annule',
            'commentaire' => $request->commentaire,
        ]);

        return redirect()->back()
            ->with('success', 'Renouvellement annulé.');
    }

    /**
     * Liste des renouvellements pour une adhésion spécifique
     */
    public function forAdhesion(Adhesion $adhesion)
    {
        $this->authorize('view', $adhesion);

        $renouvellements = RenouvellementPlan::with('effectueParAgent')
            ->where('adhesion_id', $adhesion->id)
            ->orderBy('date_renouvellement', 'desc')
            ->get();

        return view('backoffice.renouvellements.adhesion', compact('adhesion', 'renouvellements'));
    }
}