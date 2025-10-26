@extends('backoffice.layouts.app')

@section('title', 'Types de Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des Types de Documents</h5>
                    <a href="{{ route('admin.types-documents.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nouveau Type
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped" id="types-documents-table">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Recto requis</th>
                                    <th>Verso requis</th>
                                    <th>Statut</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($typesDocuments as $typeDoc)
                                <tr>
                                    <td>
                                        <strong>{{ $typeDoc->nom }}</strong>
                                    </td>
                                    <td>
                                        @if($typeDoc->description)
                                            {{ Str::limit($typeDoc->description, 50) }}
                                        @else
                                            <span class="text-muted">Aucune description</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($typeDoc->recto_requis)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($typeDoc->verso_requis)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($typeDoc->isActif())
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $typeDoc->documents_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.types-documents.show', $typeDoc) }}" 
                                               class="btn btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.types-documents.edit', $typeDoc) }}" 
                                               class="btn btn-outline-secondary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($typeDoc->isActif())
                                            <form action="{{ route('admin.types-documents.deactivate', $typeDoc) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning" 
                                                        title="Désactiver">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('admin.types-documents.activate', $typeDoc) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success" 
                                                        title="Activer">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.types-documents.destroy', $typeDoc) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" 
                                                        onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#types-documents-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            order: [[0, 'asc']]
        });
    });
</script>
@endsection