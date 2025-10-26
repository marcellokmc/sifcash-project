@extends('backoffice.layouts.app')

@section('title', 'Validation des Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Documents en Attente de Validation</h5>
                    <div>
                        <span class="badge bg-warning">{{ $documents->count() }} document(s) en attente</span>
                        <a href="{{ route('admin.adherents.index') }}" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="fas fa-arrow-left"></i> Retour aux adhérents
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($documents->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Aucun document en attente de validation</p>
                            <a href="{{ route('admin.adherents.index') }}" class="btn btn-primary">
                                <i class="fas fa-users"></i> Voir tous les adhérents
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Adhérent</th>
                                        <th>Type Document</th>
                                        <th>Version</th>
                                        <th>Date Upload</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                    <tr>
                                        <td>
                                            <strong>
                                                <a href="{{ route('admin.adherents.show', $document->adherent) }}">
                                                    {{ $document->adherent->membre_id }}
                                                </a>
                                            </strong>
                                            <br>
                                            <small>{{ $document->adherent->nom_complet }}</small>
                                            <br>
                                            <small class="text-muted">{{ $document->adherent->email }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $document->typeDocument->nom }}</strong>
                                            @if($document->typeDocument->description)
                                                <br><small class="text-muted">{{ $document->typeDocument->description }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">v{{ $document->version }}</span>
                                            @if($document->version > 1)
                                                <br><small class="text-warning">Nouvelle version</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $document->created_at->format('d/m/Y') }}
                                            <br>
                                            <small class="text-muted">{{ $document->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.documents.download', ['document' => $document, 'type' => 'recto']) }}" 
                                                   class="btn btn-outline-primary" target="_blank" title="Voir recto">
                                                    <i class="fas fa-eye"></i> Recto
                                                </a>
                                                @if($document->fichier_verso)
                                                <a href="{{ route('admin.documents.download', ['document' => $document, 'type' => 'verso']) }}" 
                                                   class="btn btn-outline-secondary" target="_blank" title="Voir verso">
                                                    <i class="fas fa-eye"></i> Verso
                                                </a>
                                                @endif
                                                <form action="{{ route('admin.documents.validate', $document) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Valider">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" 
                                                        data-bs-target="#rejectDocumentModal{{ $document->id }}" title="Rejeter">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>

                                            <!-- Modal de rejet -->
                                            <div class="modal fade" id="rejectDocumentModal{{ $document->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rejet du document</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="commentaire" class="form-label">Motif du rejet *</label>
                                                                    <textarea class="form-control" id="commentaire" name="commentaire" 
                                                                              rows="4" required 
                                                                              placeholder="Expliquez précisément le motif du rejet...
Ex: 
- Photo floue
- Document expiré
- Informations illisibles
- Mauvais format
- ..."></textarea>
                                                                    <div class="form-text">
                                                                        Ce commentaire sera visible par l'adhérent.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Affichage de {{ $documents->count() }} document(s) en attente de validation
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection