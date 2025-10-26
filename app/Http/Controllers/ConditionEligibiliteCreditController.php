<?php

namespace App\Http\Controllers;

use App\Models\ConditionEligibiliteCredit;
use Illuminate\Http\Request;

class ConditionEligibiliteCreditController extends Controller
{
    /**
     * List all active/inactive eligibility conditions.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ConditionEligibiliteCredit::class);
        $items = ConditionEligibiliteCredit::orderBy('created_at','desc')->paginate(20);
        if ($request->wantsJson()) {
            return response()->json($items);
        }
        return view('backoffice.credits.eligibilites.index', compact('items'));
    }

    // Forms not used in API context

    /**
     * Create a new eligibility condition.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ConditionEligibiliteCredit::class);
        $data = $request->validate([
            'type_cotisation_requise' => ['required','in:journalier,hebdomadaire,mensuel'],
            'duree_minimum_anciennete' => ['required','integer','min:0'],
            'montant_epargne_minimum' => ['nullable','numeric','min:0'],
            'actif' => ['nullable','boolean'],
        ]);
        $cond = ConditionEligibiliteCredit::create($data);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Condition créée.','id' => $cond->id], 201);
        }
        return redirect()->back()->with('success', 'Condition créée.');
    }

    /**
     * Display a single condition.
     */
    public function show(ConditionEligibiliteCredit $conditionEligibiliteCredit)
    {
        $this->authorize('view', $conditionEligibiliteCredit);
        return response()->json($conditionEligibiliteCredit);
    }

    // Forms not used in API context

    /**
     * Update an eligibility condition.
     */
    public function update(Request $request, ConditionEligibiliteCredit $conditionEligibiliteCredit)
    {
        $this->authorize('update', $conditionEligibiliteCredit);
        $data = $request->validate([
            'type_cotisation_requise' => ['nullable','in:journalier,hebdomadaire,mensuel'],
            'duree_minimum_anciennete' => ['nullable','integer','min:0'],
            'montant_epargne_minimum' => ['nullable','numeric','min:0'],
            'actif' => ['nullable','boolean'],
        ]);
        $conditionEligibiliteCredit->fill($data);
        $conditionEligibiliteCredit->save();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Condition mise à jour.']);
        }
        return redirect()->back()->with('success', 'Condition mise à jour.');
    }

    /**
     * Soft delete not implemented; we deny deletion to keep history.
     */
    public function destroy(ConditionEligibiliteCredit $conditionEligibiliteCredit)
    {
        return response()->json(['message' => 'Suppression non autorisée'], 405);
    }

    /**
     * Toggle active flag for a condition.
     */
    public function toggleActive(Request $request, ConditionEligibiliteCredit $conditionEligibiliteCredit)
    {
        $this->authorize('toggleActive', $conditionEligibiliteCredit);
        $conditionEligibiliteCredit->actif = !$conditionEligibiliteCredit->actif;
        $conditionEligibiliteCredit->save();
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Statut actif basculé.','actif' => (bool)$conditionEligibiliteCredit->actif]);
        }
        return redirect()->back()->with('success', 'Statut actif basculé.');
    }
}
