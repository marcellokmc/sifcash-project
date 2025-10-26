<?php

namespace App\Http\Controllers;

use App\Models\EcheanceCredit;
use App\Models\Credit;
use Illuminate\Http\Request;

class EcheanceCreditController extends Controller
{
    /**
     * List installments for a credit.
     */
    public function indexByCredit(Credit $credit)
    {
        $items = EcheanceCredit::where('credit_id', $credit->id)
            ->orderBy('date_echeance')
            ->get();
        return response()->json($items);
    }

    // Forms not used in API context

    // Schedule is created by service; no direct store here

    /**
     * Display a single installment.
     */
    public function show(EcheanceCredit $echeanceCredit)
    {
        return response()->json($echeanceCredit);
    }

    // Forms not used in API context

    /**
     * Update limited fields of an installment (status/date).
     */
    public function update(Request $request, EcheanceCredit $echeanceCredit)
    {
        $data = $request->validate([
            'statut' => ['nullable','in:en_attente,payé,en_retard,impayé'],
            'date_paiement' => ['nullable','date'],
        ]);
        $echeanceCredit->fill($data);
        $echeanceCredit->save();
        return response()->json(['message' => 'Échéance mise à jour.']);
    }

    /**
     * Deletion not allowed: schedule managed by service
     */
    public function destroy(EcheanceCredit $echeanceCredit)
    {
        return response()->json(['message' => 'Suppression non autorisée'], 405);
    }
}
