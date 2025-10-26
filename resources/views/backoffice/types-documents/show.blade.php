@extends('backoffice.layouts.app')

@section('title', 'Détail Type de Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail : {{ $typesDocument->nom }}</h5>
                    <div>
                        <a href="{{ route('admin.types-documents.edit', $typesDocument) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('admin.types-documents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom:</th>
                                    <td>{{ $typesDocument->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $typesDocument->description ?? 'Aucune description' }}</td>
                                </tr>
                                <tr>
                                    <th>Recto requis:</th>
                                    <td>
                                        @if($typesDocument->recto_requis)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Verso requis:</th>
                                    <td>
                                        @if($typesDocument->verso_requis)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut:</th>
                                    <td>
                                        @if($typesDocument->isActif())
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Documents uploadés:</th>
                                    <td><span class="badge bg-info">{{ $typesDocument->documents->count() }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($typesDocument->documents->count() > 0)
                    <div class="mt-4">
                        <h6>Documents associés</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Adhérent</th>
                                        <th>Version</th>
                                        <th>Statut</th>
                                        <th>Date Upload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($typesDocument->documents as $document)
                                    <tr>
                                        <td>{{ $document->adherent->nom_complet }}</td>
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
                                        <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection