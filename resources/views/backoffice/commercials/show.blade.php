@extends('backoffice.layouts.app')

@section('title', 'Détails du Commercial')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Détails du Commercial
                        </h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                            <a href="{{ route('admin.commercials.edit', $commercial) }}" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <a href="{{ route('admin.commercials.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informations Personnelles</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nom :</strong></td>
                                    <td>{{ $commercial->nom }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Prénoms :</strong></td>
                                    <td>{{ $commercial->prenoms }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nom Complet :</strong></td>
                                    <td>{{ $commercial->nom_complet }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Téléphone :</strong></td>
                                    <td>
                                        <a href="tel:{{ $commercial->telephone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $commercial->telephone }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Code Commercial :</strong></td>
                                    <td><span class="badge bg-info">{{ $commercial->code_commercial }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Statut :</strong></td>
                                    <td>
                                        @if($commercial->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informations Système</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Date de création :</strong></td>
                                    <td>{{ $commercial->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dernière modification :</strong></td>
                                    <td>{{ $commercial->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($commercial->notes)
                                    <tr>
                                        <td><strong>Notes :</strong></td>
                                        <td>{{ $commercial->notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Section Adhérents avec filtres multicritères -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Adhérents associés ({{ $adherents->total() }})
                                </h6>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="toggleFilters()">
                                        <i class="fas fa-filter me-1"></i>Filtres
                                        @if(request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']))
                                            <span class="badge bg-primary ms-1">{{ collect(request()->only(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']))->filter()->count() }}</span>
                                        @endif
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </button>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="toggleView('table')" id="tableViewBtn">
                                            <i class="fas fa-table"></i>
                                        </button>
                                        <button class="btn btn-outline-info" onclick="toggleView('cards')" id="cardsViewBtn">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtres multicritères -->
                            <div class="mb-4" id="filterCollapse" style="display: {{ request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']) ? 'block' : 'none' }};">
                                <div class="card border-secondary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-filter me-2"></i>
                                            Filtres multicritères
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form method="GET" action="{{ route('admin.commercials.show', $commercial) }}" id="filterForm">
                                            <div class="row g-3">
                                                <!-- Recherche par nom/prénom -->
                                                <div class="col-md-4">
                                                    <label for="search" class="form-label">Recherche (Nom/Prénom)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        <input type="text" class="form-control" id="search" name="search" 
                                                               value="{{ request('search') }}" placeholder="Rechercher un adhérent..." 
                                                               onchange="submitFilters()" onkeyup="handleSearchKeyup(event)">
                                                    </div>
                                                </div>

                                                <!-- Filtre par statut -->
                                                <div class="col-md-3">
                                                    <label for="statut" class="form-label">Statut</label>
                                                    <select class="form-select" id="statut" name="statut" onchange="submitFilters()">
                                                        <option value="">Tous les statuts</option>
                                                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                                        <option value="en_attente_de_verification" {{ request('statut') == 'en_attente_de_verification' ? 'selected' : '' }}>En attente</option>
                                                        <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                                    </select>
                                                </div>

                                                <!-- Filtre par résidence -->
                                                <div class="col-md-3">
                                                    <label for="residence" class="form-label">Résidence</label>
                                                    <input type="text" class="form-control" id="residence" name="residence" 
                                                           value="{{ request('residence') }}" placeholder="Filtrer par résidence..." 
                                                           onchange="submitFilters()">
                                                </div>

                                                <!-- Filtre par profession -->
                                                <div class="col-md-2">
                                                    <label for="profession" class="form-label">Profession</label>
                                                    <input type="text" class="form-control" id="profession" name="profession" 
                                                           value="{{ request('profession') }}" placeholder="Profession..." 
                                                           onchange="submitFilters()">
                                                </div>

                                                <!-- Filtre par dates -->
                                                <div class="col-md-3">
                                                    <label for="date_debut" class="form-label">Date début</label>
                                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                                           value="{{ request('date_debut') }}" onchange="submitFilters()">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="date_fin" class="form-label">Date fin</label>
                                                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                                           value="{{ request('date_fin') }}" onchange="submitFilters()">
                                                </div>

                                                <!-- Tri -->
                                                <div class="col-md-3">
                                                    <label for="sort_by" class="form-label">Trier par</label>
                                                    <select class="form-select" id="sort_by" name="sort_by" onchange="submitFilters()">
                                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                                                        <option value="nom" {{ request('sort_by') == 'nom' ? 'selected' : '' }}>Nom</option>
                                                        <option value="prenom" {{ request('sort_by') == 'prenom' ? 'selected' : '' }}>Prénom</option>
                                                        <option value="statut_compte" {{ request('sort_by') == 'statut_compte' ? 'selected' : '' }}>Statut</option>
                                                        <option value="residence" {{ request('sort_by') == 'residence' ? 'selected' : '' }}>Résidence</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="sort_order" class="form-label">Ordre</label>
                                                    <select class="form-select" id="sort_order" name="sort_order" onchange="submitFilters()">
                                                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <div class="d-flex align-items-center">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-search me-2"></i>Appliquer les filtres
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="clearFilters()">
                                                            <i class="fas fa-times me-2"></i>Effacer
                                                        </button>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="autoFilter" checked>
                                                            <label class="form-check-label" for="autoFilter">
                                                                Filtrage automatique
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques améliorées -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                            <small>Total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0">{{ $stats['actifs'] }}</h4>
                                            <small>Actifs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0">{{ $stats['en_attente'] }}</h4>
                                            <small>En attente</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0">{{ $stats['suspendus'] }}</h4>
                                            <small>Suspendus</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0">{{ $adherents->count() > 0 ? number_format(($stats['actifs'] / $adherents->total()) * 100, 1) : 0 }}%</h4>
                                            <small>Taux d'activation</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtres actifs -->
                            @if(request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']))
                                <div class="alert alert-info mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-filter me-2"></i>
                                            <strong>Filtres appliqués:</strong>
                                            @if(request('search'))<span class="badge bg-secondary me-1">Recherche: {{ request('search') }}</span>@endif
                                            @if(request('statut'))<span class="badge bg-secondary me-1">Statut: {{ request('statut') }}</span>@endif
                                            @if(request('residence'))<span class="badge bg-secondary me-1">Résidence: {{ request('residence') }}</span>@endif
                                            @if(request('profession'))<span class="badge bg-secondary me-1">Profession: {{ request('profession') }}</span>@endif
                                            @if(request('date_debut'))<span class="badge bg-secondary me-1">Du: {{ request('date_debut') }}</span>@endif
                                            @if(request('date_fin'))<span class="badge bg-secondary me-1">Au: {{ request('date_fin') }}</span>@endif
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            @if($adherents->count() > 0)
                                <!-- Vue Tableau -->
                                <div id="tableView">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="adherentsTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Nom Complet</th>
                                                    <th>Contact</th>
                                                    <th>Localisation</th>
                                                    <th>Profession</th>
                                                    <th>Date d'inscription</th>
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($adherents as $adherent)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input adherent-checkbox" value="{{ $adherent->id }}">
                                                        </td>
                                                        <td>{{ $adherent->id }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    {{ strtoupper(substr($adherent->nom, 0, 1)) }}
                                                                </div>
                                                                <div>
                                                                    <strong>{{ $adherent->nom }} {{ $adherent->prenom }}</strong>
                                                                    @if($adherent->commercial_id)
                                                                        <br><small class="text-muted">Ref: {{ $adherent->commercial->code_commercial }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <div><i class="fas fa-phone me-1"></i>{{ $adherent->telephone }}</div>
                                                                <div><i class="fas fa-envelope me-1"></i>{{ $adherent->email }}</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <div><i class="fas fa-map-marker-alt me-1"></i>{{ $adherent->residence ?? '-' }}</div>
                                                                @if($adherent->secteur_numero)
                                                                    <div><i class="fas fa-hashtag me-1"></i>Secteur {{ $adherent->secteur_numero }}</div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>{{ $adherent->profession ?? '-' }}</td>
                                                        <td>
                                                            <div class="small">
                                                                <div>{{ $adherent->created_at->format('d/m/Y') }}</div>
                                                                <div class="text-muted">{{ $adherent->created_at->format('H:i') }}</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $adherent->statut_compte == 'actif' ? 'success' : ($adherent->statut_compte == 'en_attente_de_verification' ? 'warning' : 'danger') }}">
                                                                {{ $adherent->statut_compte }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('admin.adherents.show', $adherent) }}" class="btn btn-outline-primary" title="Voir">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('admin.adherents.edit', $adherent) }}" class="btn btn-outline-secondary" title="Modifier">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                @if($adherent->statut_compte == 'actif')
                                                                    <form method="POST" action="{{ route('admin.adherents.deactivate', $adherent) }}" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-outline-warning" title="Désactiver" onclick="return confirm('Désactiver cet adhérent?')">
                                                                            <i class="fas fa-pause"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <form method="POST" action="{{ route('admin.adherents.activate', $adherent) }}" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-outline-success" title="Activer" onclick="return confirm('Activer cet adhérent?')">
                                                                            <i class="fas fa-play"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Vue Cartes -->
                                <div id="cardsView" style="display: none;">
                                    <div class="row">
                                        @foreach($adherents as $adherent)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                                {{ strtoupper(substr($adherent->nom, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <strong>{{ $adherent->nom }} {{ $adherent->prenom }}</strong>
                                                                <br><small class="text-muted">#{{ $adherent->id }}</small>
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-{{ $adherent->statut_compte == 'actif' ? 'success' : ($adherent->statut_compte == 'en_attente_de_verification' ? 'warning' : 'danger') }}">
                                                            {{ $adherent->statut_compte }}
                                                        </span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="small">
                                                            <div class="mb-2">
                                                                <i class="fas fa-phone me-2 text-muted"></i>{{ $adherent->telephone }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <i class="fas fa-envelope me-2 text-muted"></i>{{ $adherent->email }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $adherent->residence ?? '-' }}
                                                            </div>
                                                            @if($adherent->profession)
                                                                <div class="mb-2">
                                                                    <i class="fas fa-briefcase me-2 text-muted"></i>{{ $adherent->profession }}
                                                                </div>
                                                            @endif
                                                            <div class="mb-2">
                                                                <i class="fas fa-calendar me-2 text-muted"></i>{{ $adherent->created_at->format('d/m/Y H:i') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="btn-group btn-group-sm w-100">
                                                            <a href="{{ route('admin.adherents.show', $adherent) }}" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Voir
                                                            </a>
                                                            <a href="{{ route('admin.adherents.edit', $adherent) }}" class="btn btn-outline-secondary">
                                                                <i class="fas fa-edit"></i> Modifier
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Actions groupées -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <span class="text-muted me-3">
                                                    <span id="selectedCount">0</span> sélectionné(s)
                                                </span>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-success" onclick="bulkAction('activate')" id="bulkActivate" disabled>
                                                        <i class="fas fa-play me-1"></i>Activer
                                                    </button>
                                                    <button class="btn btn-outline-warning" onclick="bulkAction('deactivate')" id="bulkDeactivate" disabled>
                                                        <i class="fas fa-pause me-1"></i>Désactiver
                                                    </button>
                                                    <button class="btn btn-outline-info" onclick="bulkAction('export')" id="bulkExport" disabled>
                                                        <i class="fas fa-download me-1"></i>Exporter
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-muted">
                                                    Affichage de {{ $adherents->firstItem() }} à {{ $adherents->lastItem() }} 
                                                    sur {{ $adherents->total() }} résultats
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $adherents->links() }}
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun adhérent trouvé avec les critères de filtrage actuels.
                                    <br><button class="btn btn-outline-secondary mt-2" onclick="clearFilters()">Réinitialiser les filtres</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentView = 'table';

// Gérer manuellement l'affichage des filtres
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterCollapse = document.getElementById('filterCollapse');
    
    console.log('Filter toggle:', filterToggle);
    console.log('Filter collapse:', filterCollapse);
    
    if (filterToggle && filterCollapse) {
        filterToggle.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Toggle clicked!');
            
            const currentDisplay = filterCollapse.style.display;
            console.log('Current display:', currentDisplay);
            
            if (currentDisplay === 'none' || currentDisplay === '') {
                filterCollapse.style.display = 'block';
                filterToggle.innerHTML = '<i class="fas fa-filter me-1"></i>Filtres <i class="fas fa-chevron-up ms-1"></i>';
                console.log('Showing filters');
            } else {
                filterCollapse.style.display = 'none';
                filterToggle.innerHTML = '<i class="fas fa-filter me-1"></i>Filtres';
                console.log('Hiding filters');
            }
        });
    } else {
        console.error('Filter elements not found!');
    }
});

function submitFilters() {
    // Vérifier si le filtrage automatique est activé
    const autoFilter = document.getElementById('autoFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Ajouter un petit délai pour éviter les requêtes trop fréquentes
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 300);
}

function handleSearchKeyup(event) {
    const autoFilter = document.getElementById('autoFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Soumettre après 500ms d'inactivité pour la recherche
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
}

function toggleFilters() {
    const filterCollapse = document.getElementById('filterCollapse');
    if (!filterCollapse) {
        console.error('Filter collapse element not found!');
        return;
    }
    
    const currentDisplay = filterCollapse.style.display;
    console.log('Current display:', currentDisplay);
    
    if (currentDisplay === 'none' || currentDisplay === '') {
        filterCollapse.style.display = 'block';
        console.log('Showing filters');
    } else {
        filterCollapse.style.display = 'none';
        console.log('Hiding filters');
    }
}

function clearFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = "{{ route('admin.commercials.show', $commercial) }}";
}

function toggleView(view) {
    currentView = view;
    
    // Cacher les deux vues
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');
    
    if (tableView) tableView.style.display = 'none';
    if (cardsView) cardsView.style.display = 'none';
    
    // Afficher la vue sélectionnée
    if (view === 'table' && tableView) {
        tableView.style.display = 'block';
        document.getElementById('tableViewBtn').classList.add('active');
        document.getElementById('cardsViewBtn').classList.remove('active');
    } else if (view === 'cards' && cardsView) {
        cardsView.style.display = 'block';
        document.getElementById('cardsViewBtn').classList.add('active');
        document.getElementById('tableViewBtn').classList.remove('active');
    }
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = "{{ route('admin.commercials.show', $commercial) }}?" + params.toString();
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.adherent-checkbox:checked');
    const ids = Array.from(selected).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Veuillez sélectionner au moins un adhérent');
        return;
    }
    
    if (action === 'export') {
        exportData();
    } else if (action === 'activate' || action === 'deactivate') {
        if (!confirm(`${action === 'activate' ? 'Activer' : 'Désactiver'} les ${ids.length} adhérent(s) sélectionné(s)?`)) {
            return;
        }
        
        // Implémenter l'action groupée ici
        console.log(`${action} sur les adhérents:`, ids);
    }
}

// Gestion de la sélection
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.adherent-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkButtons = ['bulkActivate', 'bulkDeactivate', 'bulkExport'];
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.adherent-checkbox:checked');
        if (selectedCount) selectedCount.textContent = selected.length;
        
        // Activer/désactiver les boutons groupés
        bulkButtons.forEach(btnId => {
            const btn = document.getElementById(btnId);
            if (btn) btn.disabled = selected.length === 0;
        });
    }
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
});
</script>
@endsection
