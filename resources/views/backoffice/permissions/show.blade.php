@extends('backoffice.layouts.app')

@section('title', 'Détails Permission')
@section('page-title', 'Détails de la Permission')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
        <li class="breadcrumb-item active">{{ $permission->name }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-key"></i> Informations de la Permission
                </h6>
                <div>
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%"><i class="fas fa-tag text-primary"></i> Nom:</th>
                        <td><code class="text-primary">{{ $permission->name }}</code></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-info-circle text-info"></i> Description:</th>
                        <td>{{ $permission->description ?? 'Non renseignée' }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar-plus text-success"></i> Créée le:</th>
                        <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar-check text-warning"></i> Modifiée le:</th>
                        <td>{{ $permission->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users-cog"></i> Rôles Associés
                    <span class="badge bg-primary ms-2">{{ $permission->roles->count() }}</span>
                </h6>
            </div>
            <div class="card-body">
                @if($permission->roles->count() > 0)
                    <div class="list-group">
                        @foreach($permission->roles as $role)
                        <a href="{{ route('admin.roles.show', $role) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-shield text-primary"></i>
                                <strong>{{ ucfirst($role->name) }}</strong>
                                <br>
                                <small class="text-muted">{{ $role->description ?? 'Aucune description' }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $role->users_count }} utilisateur(s)</span>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cette permission n'est associée à aucun rôle. Elle ne sera pas effective tant qu'elle n'est pas assignée.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie"></i> Analyse de la Permission
                </h6>
            </div>
            <div class="card-body">
                @php
                    $parts = explode('_', $permission->name, 2);
                    $action = $parts[0] ?? '';
                    $resource = $parts[1] ?? '';
                @endphp
                
                <div class="mb-4">
                    <h6><i class="fas fa-puzzle-piece text-info"></i> Décomposition :</h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th width="30%">Action</th>
                            <td><code class="text-primary">{{ $action }}</code></td>
                        </tr>
                        <tr>
                            <th>Ressource</th>
                            <td><code class="text-success">{{ $resource }}</code></td>
                        </tr>
                    </table>
                </div>
                
                <div>
                    <h6><i class="fas fa-lightbulb text-warning"></i> Signification :</h6>
                    <div class="alert alert-info">
                        Cette permission permet à l'utilisateur de 
                        <strong class="text-primary">
                            @switch($action)
                                @case('view') visualiser @break
                                @case('create') créer @break
                                @case('edit') modifier @break
                                @case('delete') supprimer @break
                                @case('validate') valider @break
                                @case('approve') approuver @break
                                @case('reject') rejeter @break
                                @case('toggle') activer/désactiver @break
                                @case('export') exporter @break
                                @case('download') télécharger @break
                                @case('upload') uploader @break
                                @default gérer @break
                            @endswitch
                        </strong>
                        les éléments de type <strong class="text-success">{{ str_replace('_', ' ', $resource) }}</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cogs"></i> Actions
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier cette permission
                    </a>
                    
                    @if($permission->roles->count() == 0)
                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" 
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette permission ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer cette permission
                        </button>
                    </form>
                    @else
                    <button class="btn btn-secondary" disabled title="Cette permission est assignée à des rôles">
                        <i class="fas fa-lock me-2"></i>Suppression bloquée (assignée)
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Retour à la liste
                    </a>
                </div>
                
                @if($permission->roles->count() > 0)
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Pour supprimer cette permission, vous devez d'abord la retirer de tous les rôles auxquels elle est assignée.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
