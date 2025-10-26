<?php

namespace App\Http\Controllers;

use App\Models\SuspensionCompte;
use Illuminate\Http\Request;

class SuspensionCompteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = SuspensionCompte::query()->latest()->paginate(20);
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
    public function show(SuspensionCompte $suspensionCompte)
    {
        return response()->json($suspensionCompte);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuspensionCompte $suspensionCompte)
    {
        return response()->json(['message' => 'Non applicable'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuspensionCompte $suspensionCompte)
    {
        return response()->json(['message' => 'Mise à jour non autorisée'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuspensionCompte $suspensionCompte)
    {
        return response()->json(['message' => 'Suppression non autorisée'], 405);
    }
}
