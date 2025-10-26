@extends('backoffice.layouts.app')

@section('title', 'Détails Utilisateur')
@section('page-title', 'Détails de l\'Utilisateur')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informations Personnelles</h6>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nom complet:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $user->phone ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <th>Rôle:</th>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'agent' ? 'primary' : 'success') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Matricule:</th>
                                <td>{{ $user->matricule ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <th>Date d'embauche:</th>
                                <td>{{ $user->date_embauche ? $user->date_embauche->format('d/m/Y') : 'Non renseignée' }}</td>
                            </tr>
                            <tr>
                                <th>Agence:</th>
                                <td>{{ $user->agence->nom ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    <span class="badge bg-{{ $user->active ? 'success' : 'secondary' }}">
                                        {{ $user->active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
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
                        <i class="fas fa-user fa-3x text-primary"></i>
                    </div>
                    <h5>Activité de l'utilisateur</h5>
                    <div class="mt-3">
                        <p class="mb-1">
                            <i class="fas fa-sign-in-alt text-success me-2"></i>
                            Connexions: {{ $user->logsConnexions->where('action', 'login')->count() }}
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-sign-out-alt text-warning me-2"></i>
                            Déconnexions: {{ $user->logsConnexions->where('action', 'logout')->count() }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            Échecs: {{ $user->logsConnexions->where('action', 'failed_login')->count() }}
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
                    <a href="{{ route('admin.users.logs', $user) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-history me-1"></i>Voir les logs
                    </a>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-{{ $user->active ? 'warning' : 'success' }} btn-sm">
                            <i class="fas fa-{{ $user->active ? 'pause' : 'play' }} me-1"></i>
                            {{ $user->active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @can('update', $user)
        <div class="card shadow mt-3">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-shield-alt me-2"></i>Gestion du Compte
                </h6>
            </div>
            <div class="card-body">
                @if($user->isSuspended())
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-ban me-2"></i>
                        <strong>Compte Suspendu</strong>
                        <hr>
                        <p class="mb-1"><strong>Depuis:</strong> {{ $user->suspended_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Raison:</strong> {{ $user->suspension_reason }}</p>
                        @if($user->suspendedByUser)
                            <p class="mb-0"><strong>Par:</strong> {{ $user->suspendedByUser->name }}</p>
                        @endif
                    </div>
                @endif
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning btn-sm" 
                            onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->name }}', false)">
                        <i class="fas fa-key me-1"></i>Réinitialiser le mot de passe
                    </button>
                    
                    @if($user->isSuspended())
                        <button type="button" class="btn btn-success btn-sm" 
                                onclick="openActivateModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->suspended_at?->format('d/m/Y H:i') }}', '{{ $user->suspension_reason }}', '{{ $user->suspendedByUser?->name }}', false)">
                            <i class="fas fa-check-circle me-1"></i>Réactiver le compte
                        </button>
                    @elseif(!$user->isAdmin())
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="openSuspendModal({{ $user->id }}, '{{ $user->name }}', false)">
                            <i class="fas fa-ban me-1"></i>Suspendre le compte
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Dernières Connexions</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date/Heure</th>
                        <th>Action</th>
                        <th>Adresse IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->logsConnexions->take(5) as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action === 'login' ? 'success' : ($log->action === 'logout' ? 'warning' : 'danger') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>{{ $log->ip_address }}</td>
                        <td><small>{{ Str::limit($log->user_agent, 50) }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Aucun log de connexion</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($user->logsConnexions->count() > 5)
        <div class="text-center mt-2">
            <a href="{{ route('admin.users.logs', $user) }}" class="btn btn-sm btn-outline-primary">
                Voir tous les logs
            </a>
        </div>
        @endif
    </div>
</div>

@include('backoffice.partials.account-management-modals')
@endsection
