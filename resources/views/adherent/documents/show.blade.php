@extends('layouts.adherent-modern')

@section('title', 'Détail Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail du Document</h5>
                    <a href="{{ route('adherent.documents.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Type:</th>
                                    <td>{{ $document->typeDocument->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $document->typeDocument->description ?? 'Aucune description' }}</td>
                                </tr>
                                <tr>
                                    <th>Version:</th>
                                    <td>v{{ $document->version }}</td>
                                </tr>
                                <tr>
                                    <th>Date d'upload:</th>
                                    <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Statut:</th>
                                    <td>
                                        @if($document->isValide())
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($document->isRejete())
                                            <span class="badge bg-danger">Rejeté</span>
                                        @else
                                            <span class="badge bg-warning">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($document->isRejete() && $document->commentaire)
                                <tr>
                                    <th>Motif rejet:</th>
                                    <td class="text-danger">{{ $document->commentaire }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6>Fichier Recto</h6>
                            <a href="{{ route('adherent.documents.download', ['document' => $document, 'type' => 'recto']) }}" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-download"></i> Télécharger Recto
                            </a>
                        </div>
                        @if($document->fichier_verso)
                        <div class="col-md-6">
                            <h6>Fichier Verso</h6>
                            <a href="{{ route('adherent.documents.download', ['document' => $document, 'type' => 'verso']) }}" 
                               class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-download"></i> Télécharger Verso
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($document->isSoumis())
                    <div class="mt-4">
                        <form action="{{ route('adherent.documents.destroy', $document) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
