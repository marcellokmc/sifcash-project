@extends('backoffice.layouts.app')

@section('title', 'Gestion des Commerciaux')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Gestion des Commerciaux
                        </h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="exportCommercials()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                            <a href="{{ route('admin.commercials.create') }}" class="btn btn-light">
                                <i class="fas fa-plus me-2"></i>Nouveau Commercial
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $commercials->total() }}</h4>
                                    <small>Total Commerciaux</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $commercials->where('actif', 1)->count() }}</h4>
                                    <small>Commerciaux Actifs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $commercials->sum('adherents_count') }}</h4>
                                    <small>Total Adhérents</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0">{{ $commercials->where('actif', 1)->sum('adherents_count') > 0 ? number_format($commercials->where('actif', 1)->sum('adherents_count') / $commercials->where('actif', 1)->count(), 1) : 0 }}</h4>
                                    <small>Moyenne/Commercial Actif</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <form method="GET" action="{{ route('admin.commercials.index') }}" class="mb-4" id="commercialFilterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Rechercher..." value="{{ request('search') }}"
                                           onchange="submitCommercialFilters()" onkeyup="handleCommercialSearchKeyup(event)">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="actif" class="form-select" onchange="submitCommercialFilters()">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" {{ request('actif') == '1' ? 'selected' : '' }}>Actifs</option>
                                    <option value="0" {{ request('actif') == '0' ? 'selected' : '' }}>Inactifs</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <a href="{{ route('admin.commercials.index') }}" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times me-2"></i>Réinitialiser
                                    </a>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="checkbox" id="autoCommercialFilter" checked>
                                        <label class="form-check-label" for="autoCommercialFilter">
                                            Auto
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Contact</th>
                                    <th>Statut</th>
                                    <th>Adhérents</th>
                                    <th>Performance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commercials as $commercial)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $commercial->code_commercial }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ strtoupper(substr($commercial->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $commercial->nom_complet }}</strong>
                                                    @if($commercial->notes)
                                                        <br><small class="text-muted">{{ Str::limit($commercial->notes, 30) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><i class="fas fa-phone me-1"></i>{{ $commercial->telephone }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($commercial->actif)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-danger">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2">{{ $commercial->adherents_count }}</span>
                                                @if($commercial->adherents_count > 0)
                                                    <small class="text-muted">
                                                        <a href="{{ route('admin.commercials.show', $commercial) }}" class="text-decoration-none">
                                                            <i class="fas fa-eye me-1"></i>Voir
                                                        </a>
                                                    </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($commercial->adherents_count > 0)
                                                <div class="progress" style="height: 8px;">
                                                    @php
                                                        $maxAdherents = $commercials->max('adherents_count');
                                                        $percentage = $maxAdherents > 0 ? ($commercial->adherents_count / $maxAdherents) * 100 : 0;
                                                    @endphp
                                                    <div class="progress-bar {{ $percentage >= 70 ? 'bg-success' : ($percentage >= 40 ? 'bg-warning' : 'bg-danger') }}" 
                                                         style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ number_format($percentage, 0) }}%</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.commercials.show', $commercial) }}" 
                                                   class="btn btn-outline-primary" title="Voir les adhérents">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                                <a href="{{ route('admin.commercials.edit', $commercial) }}" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($commercial->actif)
                                                    <form method="POST" action="{{ route('admin.commercials.toggle', $commercial) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary" title="Désactiver">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.commercials.toggle', $commercial) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" title="Activer">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.commercials.destroy', $commercial) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            title="Supprimer" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commercial ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun commercial trouvé</p>
                                            <a href="{{ route('admin.commercials.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer un commercial
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <span class="text-muted">
                                Affichage de {{ $commercials->firstItem() }} à {{ $commercials->lastItem() }} 
                                sur {{ $commercials->total() }} commerciaux
                            </span>
                            @if(request()->hasAny(['search', 'actif']))
                                <br><small class="text-info">
                                    <i class="fas fa-filter me-1"></i>
                                    Filtres appliqués: 
                                    @if(request('search'))<span class="badge bg-secondary me-1">Recherche: {{ request('search') }}</span>@endif
                                    @if(request('actif') !== null)<span class="badge bg-secondary me-1">Statut: {{ request('actif') == '1' ? 'Actif' : 'Inactif' }}</span>@endif
                                </small>
                            @endif
                        </div>
                        {{ $commercials->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportCommercials() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = "{{ route('admin.commercials.index') }}?" + params.toString();
}

function submitCommercialFilters() {
    // Vérifier si le filtrage automatique est activé
    const autoFilter = document.getElementById('autoCommercialFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Ajouter un petit délai pour éviter les requêtes trop fréquentes
    clearTimeout(window.commercialFilterTimeout);
    window.commercialFilterTimeout = setTimeout(() => {
        document.getElementById('commercialFilterForm').submit();
    }, 300);
}

function handleCommercialSearchKeyup(event) {
    const autoFilter = document.getElementById('autoCommercialFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Soumettre après 500ms d'inactivité pour la recherche
    clearTimeout(window.commercialSearchTimeout);
    window.commercialSearchTimeout = setTimeout(() => {
        document.getElementById('commercialFilterForm').submit();
    }, 500);
}
</script>
@endsection
