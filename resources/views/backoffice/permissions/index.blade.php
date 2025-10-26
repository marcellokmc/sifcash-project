@extends('backoffice.layouts.app')

@section('title', 'Gestion des Permissions')
@section('page-title', 'Liste des Permissions')

@section('content')
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Permissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $permissions->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-key fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Permissions Assignées</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $permissions->where('roles_count', '>', 0)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Non Assignées</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $permissions->where('roles_count', 0)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Catégories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="categoryCount">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-layer-group fa-2x text-gray-300"></i>
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
                <h6 class="m-0 font-weight-bold text-primary">Permissions ({{ $permissions->count() }})</h6>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Nouvelle Permission
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Barre de recherche et filtres -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une permission...">
                </div>
            </div>
            <div class="col-md-3">
                <select id="categoryFilter" class="form-select">
                    <option value="">Toutes les catégories</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="assigned">Assignées</option>
                    <option value="unassigned">Non assignées</option>
                </select>
            </div>
        </div>

        <!-- Affichage par catégorie -->
        <div id="permissionsContainer">
            @php
                $grouped = $permissions->groupBy(function($item) {
                    $parts = explode('_', $item->name, 2);
                    return $parts[1] ?? 'autres';
                });
            @endphp

            @foreach($grouped->sortKeys() as $category => $categoryPermissions)
            <div class="permission-category mb-4" data-category="{{ $category }}">
                <h5 class="mb-3">
                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                    <small class="text-muted ms-2">({{ $categoryPermissions->count() }} permissions)</small>
                </h5>
                
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th width="30%">Nom</th>
                                <th width="40%">Description</th>
                                <th width="15%" class="text-center">Rôles</th>
                                <th width="15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryPermissions as $permission)
                            <tr class="permission-row" 
                                data-name="{{ $permission->name }}" 
                                data-description="{{ $permission->description ?? '' }}"
                                data-roles="{{ $permission->roles_count }}"
                                data-status="{{ $permission->roles_count > 0 ? 'assigned' : 'unassigned' }}">
                                <td>
                                    <code class="text-primary">{{ $permission->name }}</code>
                                    @php
                                        $parts = explode('_', $permission->name, 2);
                                        $action = $parts[0] ?? '';
                                    @endphp
                                    @if(in_array($action, ['view', 'create', 'edit', 'delete', 'validate', 'approve', 'reject']))
                                        <br><small class="text-muted">
                                            <i class="fas fa-tag"></i> 
                                            @switch($action)
                                                @case('view') Lecture @break
                                                @case('create') Création @break
                                                @case('edit') Modification @break
                                                @case('delete') Suppression @break
                                                @case('validate') Validation @break
                                                @case('approve') Approbation @break
                                                @case('reject') Rejet @break
                                            @endswitch
                                        </small>
                                    @endif
                                </td>
                                <td><small>{{ $permission->description ?? 'Aucune description' }}</small></td>
                                <td class="text-center">
                                    @if($permission->roles_count > 0)
                                        <span class="badge bg-success">{{ $permission->roles_count }} rôle(s)</span>
                                    @else
                                        <span class="badge bg-secondary">Non assignée</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.permissions.show', $permission) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                           class="btn btn-warning btn-sm"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($permission->roles_count == 0)
                                        <form action="{{ route('admin.permissions.destroy', $permission) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Supprimer cette permission ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Permission assignée">
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
            </div>
            @endforeach
        </div>
        
        <div id="noResults" class="alert alert-info d-none">
            <i class="fas fa-info-circle me-2"></i>Aucune permission trouvée.
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Guide des Permissions</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <h6><i class="fas fa-book text-primary"></i> Convention de nommage :</h6>
                <ul class="list-unstyled">
                    <li><code>view_</code> - Permission de visualisation</li>
                    <li><code>create_</code> - Permission de création</li>
                    <li><code>edit_</code> - Permission de modification</li>
                    <li><code>delete_</code> - Permission de suppression</li>
                    <li><code>validate_</code> - Permission de validation</li>
                    <li><code>approve_</code> - Permission d'approbation</li>
                    <li><code>reject_</code> - Permission de rejet</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6><i class="fas fa-lightbulb text-warning"></i> Exemples :</h6>
                <ul class="list-unstyled">
                    <li><code>view_users</code> - Voir les utilisateurs</li>
                    <li><code>create_credits</code> - Créer des crédits</li>
                    <li><code>validate_payments</code> - Valider les paiements</li>
                    <li><code>approve_credits</code> - Approuver les crédits</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6><i class="fas fa-shield-alt text-success"></i> Bonnes pratiques :</h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Utilisez des noms explicites</li>
                    <li><i class="fas fa-check text-success"></i> Respectez le format action_ressource</li>
                    <li><i class="fas fa-check text-success"></i> Ajoutez toujours une description</li>
                    <li><i class="fas fa-check text-success"></i> Testez avant d'assigner</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const permissionRows = document.querySelectorAll('.permission-row');
    const permissionCategories = document.querySelectorAll('.permission-category');
    const noResults = document.getElementById('noResults');
    const categoryCount = document.getElementById('categoryCount');
    
    // Compter les catégories
    categoryCount.textContent = permissionCategories.length;
    
    // Remplir le filtre de catégories
    const categories = new Set();
    permissionCategories.forEach(cat => {
        const categoryName = cat.dataset.category;
        categories.add(categoryName);
        const option = document.createElement('option');
        option.value = categoryName;
        option.textContent = categoryName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        categoryFilter.appendChild(option);
    });
    
    function filterPermissions() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedStatus = statusFilter.value;
        
        let visibleCount = 0;
        let visibleCategoriesCount = 0;
        
        permissionCategories.forEach(category => {
            const categoryName = category.dataset.category;
            let categoryHasVisibleRows = false;
            
            // Si un filtre de catégorie est sélectionné et ne correspond pas, masquer toute la catégorie
            if (selectedCategory && categoryName !== selectedCategory) {
                category.style.display = 'none';
                return;
            }
            
            const rows = category.querySelectorAll('.permission-row');
            rows.forEach(row => {
                const name = row.dataset.name.toLowerCase();
                const description = row.dataset.description.toLowerCase();
                const status = row.dataset.status;
                
                let showRow = true;
                
                // Filtre de recherche
                if (searchTerm && !name.includes(searchTerm) && !description.includes(searchTerm)) {
                    showRow = false;
                }
                
                // Filtre de statut
                if (selectedStatus && status !== selectedStatus) {
                    showRow = false;
                }
                
                if (showRow) {
                    row.style.display = '';
                    categoryHasVisibleRows = true;
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Afficher/masquer la catégorie selon si elle a des lignes visibles
            if (categoryHasVisibleRows) {
                category.style.display = '';
                visibleCategoriesCount++;
            } else {
                category.style.display = 'none';
            }
        });
        
        // Afficher le message "aucun résultat" si nécessaire
        if (visibleCount === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
    
    // Événements
    searchInput.addEventListener('input', filterPermissions);
    categoryFilter.addEventListener('change', filterPermissions);
    statusFilter.addEventListener('change', filterPermissions);
});
</script>
@endpush
@endsection
