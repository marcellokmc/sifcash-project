<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agences = Agence::withCount('users')->latest()->get();
        return view('backoffice.agences.index', compact('agences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backoffice.agences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:agences,code|max:20',
            'nom' => 'required|max:100',
            'province' => 'required|max:50',
            'departement' => 'nullable|max:50',
            'adresse' => 'required',
            'contact' => 'required|max:20',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Agence::create($request->all());

        return redirect()->route('agences.index')
            ->with('success', 'Agence créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agence $agence)
    {
        $agence->load('users');
        return view('backoffice.agences.show', compact('agence'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agence $agence)
    {
        return view('backoffice.agences.edit', compact('agence'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agence $agence)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:agences,code,' . $agence->id . '|max:20',
            'nom' => 'required|max:100',
            'province' => 'required|max:50',
            'departement' => 'nullable|max:50',
            'adresse' => 'required',
            'contact' => 'required|max:20',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $agence->update($request->all());

        return redirect()->route('agences.index')
            ->with('success', 'Agence modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agence $agence)
    {
        if ($agence->users()->count() > 0) {
            return redirect()->route('agences.index')
                ->with('error', 'Impossible de supprimer cette agence car elle contient des utilisateurs.');
        }

        $agence->delete();

        return redirect()->route('agences.index')
            ->with('success', 'Agence supprimée avec succès.');
    }

    /**
     * Activer/Désactiver une agence
     */
    public function toggleStatus(Agence $agence)
    {
        $agence->update(['active' => !$agence->active]);

        $status = $agence->active ? 'activée' : 'désactivée';
        return redirect()->back()
            ->with('success', "Agence {$status} avec succès.");
    }
}