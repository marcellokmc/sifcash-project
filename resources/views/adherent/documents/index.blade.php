@extends('layouts.adherent-modern')

@section('title', 'Mes Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Documents</h5>
                    <a href="{{ route('adherent.documents.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Uploader un Document
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($documents->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun document uploadé</p>
                            <a href="{{ route('adherent.documents.create') }}" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Uploader mon premier document
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type de Document</th>
                                        <th>Version</th>
                                        <th>Statut</th>
                                        <th>Date Upload</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $document)
                                    <tr>
                                        <td>
                                            <strong>{{ $document->typeDocument->nom }}</strong>
                                            @if($document->typeDocument->description)
                                                <br><small class="text-muted">{{ $document->typeDocument->description }}</small>
                                            @endif
                                        </td>
                                        <td>v{{ $document->version }}</td>
                                        <td>
                                            @if($document->isValide())
                                                <span class="badge bg-success">Validé</span>
                                            @elseif($document->isRejete())
                                                <span class="badge bg-danger">Rejeté</span>
                                            @else
                                                <span class="badge bg-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('adherent.documents.download', ['document' => $document, 'type' => 'recto']) }}" 
                                                   class="btn btn-outline-primary" title="Télécharger recto">
                                                    <i class="fas fa-download"></i> Recto
                                                </a>
                                                @if($document->fichier_verso)
                                                <a href="{{ route('adherent.documents.download', ['document' => $document, 'type' => 'verso']) }}" 
                                                   class="btn btn-outline-secondary" title="Télécharger verso">
                                                    <i class="fas fa-download"></i> Verso
                                                </a>
                                                @endif
                                                @if($document->isSoumis())
                                                <form action="{{ route('adherent.documents.destroy', $document) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @if($document->isRejete() && $document->commentaire)
                                    <tr>
                                        <td colspan="5" class="bg-light">
                                            <small class="text-danger">
                                                <strong>Motif du rejet :</strong> {{ $document->commentaire }}
                                            </small>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($typesDocuments->isNotEmpty())
                    <div class="mt-4">
                        <h6>Types de documents requis</h6>
                        <div class="row">
                            @foreach($typesDocuments as $typeDoc)
                            <div class="col-md-4 mb-2">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>{{ $typeDoc->nom }}</span>
                                            <div>
                                                @if($typeDoc->obligatoire)
                                                    <span class="badge bg-danger me-2">Obligatoire</span>
                                                @else
                                                    <span class="badge bg-secondary me-2">Facultatif</span>
                                                @endif
                                                @php
                                                    $userDoc = $documents->where('type_document_id', $typeDoc->id)->first();
                                                @endphp
                                                @if($userDoc && $userDoc->isValide())
                                                    <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                @elseif($userDoc && $userDoc->isSoumis())
                                                    <span class="badge bg-warning"><i class="fas fa-clock"></i></span>
                                                @else
                                                    <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
