<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\TypeDocument;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
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

        $documents = $adherent->documents()->with('typeDocument')->get();
        $typesDocuments = TypeDocument::where('actif', true)->get();
        
        return view('adherent.documents.index', compact('documents', 'typesDocuments', 'adherent'));
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

        $typesDocuments = TypeDocument::where('actif', true)->get();
        
        return view('adherent.documents.create', compact('typesDocuments', 'adherent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $adherent = $user->adherent;

        $validator = Validator::make($request->all(), [
            'type_document_id' => 'required|exists:type_documents,id',
            'fichier_recto' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            'fichier_verso' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $typeDocument = TypeDocument::findOrFail($request->type_document_id);

        // Vérifier si un document de ce type existe déjà
        $existingDocument = Document::where('adherent_id', $adherent->id)
            ->where('type_document_id', $request->type_document_id)
            ->first();

        $version = $existingDocument ? $existingDocument->version + 1 : 1;

        // Upload des fichiers
        $rectoPath = $request->file('fichier_recto')->store('documents', 'public');
        $versoPath = $request->file('fichier_verso') ? $request->file('fichier_verso')->store('documents', 'public') : null;

        // Vérification des champs requis
        if ($typeDocument->verso_requis && !$versoPath) {
            return redirect()->back()
                ->with('error', 'Le verso est requis pour ce type de document.')
                ->withInput();
        }

        $document = Document::create([
            'adherent_id' => $adherent->id,
            'type_document_id' => $request->type_document_id,
            'fichier_recto' => $rectoPath,
            'fichier_verso' => $versoPath,
            'statut' => 'soumis',
            'version' => $version,
        ]);

        return redirect()->route('adherent.documents.index')
            ->with('success', 'Document uploadé avec succès. En attente de validation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);
        
        return view('adherent.documents.show', compact('document'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        // Supprimer les fichiers
        Storage::disk('public')->delete($document->fichier_recto);
        if ($document->fichier_verso) {
            Storage::disk('public')->delete($document->fichier_verso);
        }

        $document->delete();

        return redirect()->route('adherent.documents.index')
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Valider un document (backoffice agent)
     */
    public function validateDocument(Document $document)
    {
        $document->update([
            'statut' => 'validé',
            'commentaire' => null
        ]);

        return redirect()->back()
            ->with('success', 'Document validé avec succès.');
    }

    /**
     * Rejeter un document (backoffice agent)
     */
    public function rejectDocument(Request $request, Document $document)
    {
        $validator = Validator::make($request->all(), [
            'commentaire' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $document->update([
            'statut' => 'rejeté',
            'commentaire' => $request->commentaire
        ]);

        return redirect()->back()
            ->with('success', 'Document rejeté avec succès.');
    }

    /**
     * Télécharger un document
     */
    public function download(Document $document, $type = 'recto')
    {
        $this->authorize('view', $document);

        $filePath = $type === 'verso' ? $document->fichier_verso : $document->fichier_recto;
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->download($filePath);
    }

    /**
     * Liste des documents en attente (backoffice)
     */
    public function pendingValidation()
    {
        $documents = Document::with(['adherent.user', 'typeDocument'])
            ->where('statut', 'soumis')
            ->latest()
            ->get();

        return view('backoffice.validation.documents', compact('documents'));
    }
}