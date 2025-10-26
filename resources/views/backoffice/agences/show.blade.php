@extends('backoffice.layouts.app')

@section('title', 'Détails Agence')
@section('page-title', 'Détails de l\'Agence')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informations de l'Agence</h6>
                <a href="{{ route('admin.agences.edit', $agence) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Code:</th>
                                <td><strong>{{ $agence->code }}</strong></td>
                            </tr>
                            <tr>
                                <th>Nom:</th>
                                <td>{{ $agence->nom }}</td>
                            </tr>
                            <tr>
                                <th>Province:</th>
                                <td>{{ $agence->province }}</td>
                            </tr>
                            <tr>
                                <th>Département:</th>
                                <td>{{ $agence->departement ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Contact:</th>
                                <td>{{ $agence->contact }}</td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    <span class="badge bg-{{ $agence->active ? 'success' : 'secondary' }}">
                                        {{ $agence->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Créée le:</th>
                                <td>{{ $agence->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Modifiée le:</th>
                                <td>{{ $agence->updated_at->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-3">
                    <h6 class="font-weight-bold">Adresse complète:</h6>
                    <p class="mb-0">{{ $agence->adresse }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-building fa-3x text-primary"></i>
                    </div>
                    <h5>Activité de l'agence</h5>
                    <div class="mt-3">
                        <p class="mb-1">
                            <i class="fas fa-users text-info me-2"></i>
                            Utilisateurs: {{ $agence->users_count }}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-user-tie text-success me-2"></i>
                            Agents: {{ $agence->users->where('role', 'agent')->count() }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-user-check text-warning me-2"></i>
                            Adhérents: {{ $agence->users->where('role', 'adherent')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.agences.toggle-status', $agence) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-{{ $agence->active ? 'warning' : 'success' }} btn-sm">
                            <i class="fas fa-{{ $agence->active ? 'pause' : 'play' }} me-1"></i>
                            {{ $agence->active ? 'Désactiver' : 'Activer' }} l'agence
                        </button>
                    </form>
                    <a href="{{ route('admin.users.index') }}?agence={{ $agence->id }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-list me-1"></i>Voir les utilisateurs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Utilisateurs de l'Agence</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agence->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'agent' ? 'primary' : 'success') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->active ? 'success' : 'secondary' }}">
                                {{ $user->active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucun utilisateur dans cette agence</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection