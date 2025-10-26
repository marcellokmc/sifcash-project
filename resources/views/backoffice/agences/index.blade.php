@extends('backoffice.layouts.app')

@section('title', 'Gestion des Agences')
@section('page-title', 'Liste des Agences')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Agences</h6>
        <a href="{{ route('admin.agences.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nouvelle Agence
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Province</th>
                        <th>Département</th>
                        <th>Contact</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agences as $agence)
                    <tr>
                        <td>{{ $agence->id }}</td>
                        <td><strong>{{ $agence->code }}</strong></td>
                        <td>{{ $agence->nom }}</td>
                        <td>{{ $agence->province }}</td>
                        <td>{{ $agence->departement ?? 'N/A' }}</td>
                        <td>{{ $agence->contact }}</td>
                        <td>
                            <span class="badge bg-{{ $agence->active ? 'success' : 'secondary' }}">
                                {{ $agence->active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.agences.show', $agence) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.agences.edit', $agence) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.agences.toggle-status', $agence) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $agence->active ? 'warning' : 'success' }}">
                                        <i class="fas fa-{{ $agence->active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.agences.destroy', $agence) }}" method="POST" 
                                      onsubmit="return confirm('Supprimer cette agence?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
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
@endsection