<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueRetrait;
use Illuminate\Http\Request;

class HistoriqueRetraitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = HistoriqueRetrait::query()->latest()->paginate(20);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Non applicable'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Création non autorisée'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoriqueRetrait $historiqueRetrait)
    {
        return response()->json($historiqueRetrait);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriqueRetrait $historiqueRetrait)
    {
        return response()->json(['message' => 'Non applicable'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriqueRetrait $historiqueRetrait)
    {
        return response()->json(['message' => 'Mise à jour non autorisée'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriqueRetrait $historiqueRetrait)
    {
        return response()->json(['message' => 'Suppression non autorisée'], 405);
    }
}
