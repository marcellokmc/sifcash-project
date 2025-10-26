@extends('backoffice.layouts.app')

@section('title', 'Gestion des Rôles')
@section('page-title', 'Liste des Rôles')

@section('content')
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Rôles</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roles->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roles->sum('users_count') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Permissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roles->sum(function($role) { return $role->permissions->count(); }) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-key fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rôle le Plus Utilisé</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $roles->sortByDesc('users_count')->first()->name ?? '-' }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-crown fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-shield"></i> Rôles ({{ $roles->count() }})
                </h6>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouveau Rôle
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Barre de recherche -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un rôle...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="sortBy" class="form-select">
                    <option value="name">Trier par nom</option>
                    <option value="users">Trier par utilisateurs</option>
                    <option value="permissions">Trier par permissions</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="viewMode" class="form-select">
                    <option value="table">Vue tableau</option>
                    <option value="cards">Vue cartes</option>
                </select>
            </div>
        </div>

        <!-- Vue Tableau -->
        <div id="tableView" class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="20%">Nom</th>
                        <th width="35%">Description</th>
                        <th width="15%" class="text-center">Utilisateurs</th>
                        <th width="15%" class="text-center">Permissions</th>
                        <th width="15%" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody">
                    @foreach($roles as $role)
                    <tr class="role-row" 
                        data-name="{{ $role->name }}" 
                        data-description="{{ $role->description ?? '' }}"
                        data-users="{{ $role->users_count ?? 0 }}"
                        data-permissions="{{ $role->permissions->count() }}">
                        <td>
                            <strong class="text-primary">{{ ucfirst($role->name) }}</strong>
                            @if($role->users_count > 0)
                                <span class="badge bg-success ms-1">Actif</span>
                            @else
                                <span class="badge bg-secondary ms-1">Inactif</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $role->description ?? 'Aucune description' }}</small></td>
                        <td class="text-center">
                            @if($role->users_count > 0)
                                <span class="badge bg-primary">{{ $role->users_count }} utilisateur(s)</span>
                            @else
                                <span class="badge bg-secondary">Aucun</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $role->permissions->count() }} permission(s)</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->users_count == 0)
                                <form action="{{ route('admin.roles.destroy', $role) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce rôle ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary btn-sm" disabled title="Rôle assigné">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Vue Cartes -->
        <div id="cardsView" class="d-none">
            <div class="row" id="rolesCardsContainer">
                @foreach($roles as $role)
                <div class="col-md-6 col-lg-4 mb-4 role-card" 
                     data-name="{{ $role->name }}" 
                     data-description="{{ $role->description ?? '' }}"
                     data-users="{{ $role->users_count ?? 0 }}"
                     data-permissions="{{ $role->permissions->count() }}">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-user-shield"></i> 
                                {{ ucfirst($role->name) }}
                                @if($role->users_count > 0)
                                    <span class="badge bg-light text-dark float-end">{{ $role->users_count }}</span>
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><small class="text-muted">{{ $role->description ?? 'Aucune description' }}</small></p>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <i class="fas fa-users text-primary"></i>
                                    <small>{{ $role->users_count ?? 0 }} utilisateur(s)</small>
                                </div>
                                <div>
                                    <i class="fas fa-key text-info"></i>
                                    <small>{{ $role->permissions->count() }} permission(s)</small>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Voir détails
                                </a>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div id="noResults" class="alert alert-info d-none">
            <i class="fas fa-info-circle me-2"></i>Aucun rôle trouvé.
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const sortBy = document.getElementById('sortBy');
    const viewMode = document.getElementById('viewMode');
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');
    const noResults = document.getElementById('noResults');
    
    // Basculer entre vue tableau et cartes
    viewMode.addEventListener('change', function() {
        if (this.value === 'cards') {
            tableView.classList.add('d-none');
            cardsView.classList.remove('d-none');
        } else {
            tableView.classList.remove('d-none');
            cardsView.classList.add('d-none');
        }
        filterAndSort();
    });
    
    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const sortValue = sortBy.value;
        
        let visibleCount = 0;
        
        // Filtrer et trier vue tableau
        const tableRows = Array.from(document.querySelectorAll('.role-row'));
        tableRows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const description = row.dataset.description.toLowerCase();
            
            if (searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Trier tableau
        if (sortValue !== 'name') {
            tableRows.sort((a, b) => {
                const valA = parseInt(a.dataset[sortValue]) || 0;
                const valB = parseInt(b.dataset[sortValue]) || 0;
                return valB - valA;
            });
            const tbody = document.getElementById('rolesTableBody');
            tableRows.forEach(row => tbody.appendChild(row));
        }
        
        // Filtrer et trier vue cartes
        const cards = Array.from(document.querySelectorAll('.role-card'));
        cards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const description = card.dataset.description.toLowerCase();
            
            if (searchTerm === '' || name.includes(searchTerm) || description.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Trier cartes
        if (sortValue !== 'name') {
            cards.sort((a, b) => {
                const valA = parseInt(a.dataset[sortValue]) || 0;
                const valB = parseInt(b.dataset[sortValue]) || 0;
                return valB - valA;
            });
            const container = document.getElementById('rolesCardsContainer');
            cards.forEach(card => container.appendChild(card));
        }
        
        // Afficher message "aucun résultat"
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
            tableView.classList.add('d-none');
        } else {
            noResults.classList.add('d-none');
            if (viewMode.value === 'table') {
                tableView.classList.remove('d-none');
            }
        }
    }
    
    searchInput.addEventListener('input', filterAndSort);
    sortBy.addEventListener('change', filterAndSort);
});
</script>
@endpush
@endsection
