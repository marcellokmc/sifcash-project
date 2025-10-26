<?php

namespace App\Http\Controllers;

use App\Models\TypeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typesDocuments = TypeDocument::latest()->get();
        
        return view('backoffice.types-documents.index', compact('typesDocuments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backoffice.types-documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:type_documents,nom',
            'description' => 'nullable|string',
            'recto_requis' => 'boolean',
            'verso_requis' => 'boolean',
            'actif' => 'boolean',
            'obligatoire' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        TypeDocument::create([
            'nom' => $request->string('nom'),
            'description' => $request->input('description'),
            'recto_requis' => $request->boolean('recto_requis'),
            'verso_requis' => $request->boolean('verso_requis'),
            'actif' => $request->boolean('actif'),
            'obligatoire' => $request->boolean('obligatoire'),
        ]);

        return redirect()->route('admin.types-documents.index')
            ->with('success', 'Type de document créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeDocument $typesDocument)
    {
        $typesDocument->load('documents.adherent');
        
        return view('backoffice.types-documents.show', compact('typesDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeDocument $typesDocument)
    {
        return view('backoffice.types-documents.edit', compact('typesDocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeDocument $typesDocument)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:type_documents,nom,' . $typesDocument->id,
            'description' => 'nullable|string',
            'recto_requis' => 'boolean',
            'verso_requis' => 'boolean',
            'actif' => 'boolean',
            'obligatoire' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $typesDocument->update([
            'nom' => $request->string('nom'),
            'description' => $request->input('description'),
            'recto_requis' => $request->boolean('recto_requis'),
            'verso_requis' => $request->boolean('verso_requis'),
            'actif' => $request->boolean('actif'),
            'obligatoire' => $request->boolean('obligatoire'),
        ]);

        return redirect()->route('admin.types-documents.index')
            ->with('success', 'Type de document modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeDocument $typesDocument)
    {
        // Vérifier s'il y a des documents associés
        if ($typesDocument->documents()->count() > 0) {
            return redirect()->route('admin.types-documents.index')
                ->with('error', 'Impossible de supprimer ce type de document car il est utilisé par des documents.');
        }

        $typesDocument->delete();

        return redirect()->route('admin.types-documents.index')
            ->with('success', 'Type de document supprimé avec succès.');
    }

    /**
     * Activer un type de document
     */
    public function activate(TypeDocument $typesDocument)
    {
        $typesDocument->update(['actif' => true]);

        return redirect()->back()
            ->with('success', 'Type de document activé avec succès.');
    }

    /**
     * Désactiver un type de document
     */
    public function deactivate(TypeDocument $typesDocument)
    {
        $typesDocument->update(['actif' => false]);

        return redirect()->back()
            ->with('success', 'Type de document désactivé avec succès.');
    }
}