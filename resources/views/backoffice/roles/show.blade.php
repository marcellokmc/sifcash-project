@extends('backoffice.layouts.app')

@section('title', 'Détails Rôle')
@section('page-title', 'Détails du Rôle')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Rôles</a></li>
        <li class="breadcrumb-item active">{{ ucfirst($role->name) }}</li>
    </ol>
</nav>

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $role->users_count }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $role->permissions->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-key fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Statut</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @if($role->users_count > 0)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-shield"></i> Informations du Rôle
                </h6>
                <div>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Modifier
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%"><i class="fas fa-tag text-primary"></i> Nom:</th>
                        <td><strong class="text-primary">{{ ucfirst($role->name) }}</strong></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-info-circle text-info"></i> Description:</th>
                        <td>{{ $role->description ?? 'Non renseignée' }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-users text-success"></i> Utilisateurs:</th>
                        <td>
                            @if($role->users_count > 0)
                                <span class="badge bg-primary">{{ $role->users_count }} utilisateur(s)</span>
                            @else
                                <span class="badge bg-secondary">Aucun utilisateur</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-key text-warning"></i> Permissions:</th>
                        <td>
                            <span class="badge bg-info">{{ $role->permissions->count() }} permission(s)</span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar-plus text-success"></i> Créé le:</th>
                        <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar-check text-warning"></i> Modifié le:</th>
                        <td>{{ $role->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users"></i> Utilisateurs avec ce rôle
                    <span class="badge bg-primary ms-2">{{ $role->users_count }}</span>
                </h6>
            </div>
            <div class="card-body">
                @if($role->users_count > 0)
                    <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                        @foreach($role->users as $user)
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user text-primary"></i>
                                <strong>{{ $user->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                </small>
                                @if($user->agence)
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-building"></i> {{ $user->agence->nom }}
                                </small>
                                @endif
                            </div>
                            <div>
                                @if($user->active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Aucun utilisateur n'a ce rôle actuellement. Ce rôle peut être supprimé.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-key"></i> Permissions Accordées
            <span class="badge bg-info ms-2">{{ $role->permissions->count() }}</span>
        </h6>
        <div class="input-group" style="width: 300px;">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchPermission" class="form-control form-control-sm" placeholder="Rechercher...">
        </div>
    </div>
    <div class="card-body">
        @if($role->permissions->count() > 0)
            @php
                $grouped = $role->permissions->groupBy(function($item) {
                    $parts = explode('_', $item->name, 2);
                    return $parts[1] ?? 'autres';
                });
            @endphp

            <div id="permissionsContainer">
                @foreach($grouped->sortKeys() as $category => $categoryPermissions)
                <div class="permission-category mb-4" data-category="{{ $category }}">
                    <h6 class="mb-3">
                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                        <small class="text-muted ms-2">({{ $categoryPermissions->count() }} permissions)</small>
                    </h6>
                    
                    <div class="row">
                        @foreach($categoryPermissions as $permission)
                        <div class="col-md-6 mb-2 permission-item" 
                             data-name="{{ $permission->name }}" 
                             data-description="{{ $permission->description ?? '' }}">
                            <div class="card border-left-info shadow-sm h-100">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <code class="text-primary">{{ $permission->name }}</code>
                                            <br>
                                            <small class="text-muted">{{ $permission->description ?? 'Aucune description' }}</small>
                                        </div>
                                        <a href="{{ route('admin.permissions.show', $permission) }}" 
                                           class="btn btn-sm btn-outline-info ms-2"
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            
            <div id="noPermissionsFound" class="alert alert-info d-none">
                <i class="fas fa-info-circle me-2"></i>Aucune permission trouvée.
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Ce rôle n'a aucune permission associée. Les utilisateurs de ce rôle n'auront aucune autorisation.
            </div>
        @endif
    </div>
</div>

<!-- Actions -->
<div class="card shadow mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-cogs"></i> Actions
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier ce rôle
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                @if($role->users_count == 0)
                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" 
                      onsubmit="return confirm('Voulez-vous vraiment supprimer ce rôle ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-trash me-2"></i>Supprimer ce rôle
                    </button>
                </form>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>Ce rôle peut être supprimé car il n'est assigné à aucun utilisateur.</small>
                </div>
                @else
                <button class="btn btn-secondary w-100" disabled title="Rôle assigné à des utilisateurs">
                    <i class="fas fa-lock me-2"></i>Suppression bloquée
                </button>
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <small>Pour supprimer ce rôle, vous devez d'abord réassigner les {{ $role->users_count }} utilisateur(s) à un autre rôle.</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPermission');
    const noPermissionsFound = document.getElementById('noPermissionsFound');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const permissionItems = document.querySelectorAll('.permission-item');
            const categories = document.querySelectorAll('.permission-category');
            let visibleCount = 0;
            
            categories.forEach(category => {
                let categoryHasVisible = false;
                const items = category.querySelectorAll('.permission-item');
                
                items.forEach(item => {
                    const name = item.dataset.name.toLowerCase();
                    const description = item.dataset.description.toLowerCase();
                    
                    if (searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm)) {
                        item.style.display = '';
                        categoryHasVisible = true;
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                category.style.display = categoryHasVisible ? '' : 'none';
            });
            
            if (visibleCount === 0 && searchTerm !== '') {
                noPermissionsFound.classList.remove('d-none');
            } else {
                noPermissionsFound.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
@endsection
