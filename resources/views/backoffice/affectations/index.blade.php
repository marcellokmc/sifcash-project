@extends('backoffice.layouts.app')

@section('title', 'Affectation en masse')
@section('page-title', 'Gestion des Affectations')

@section('content')
<div class="row">
    <!-- Statistiques -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Adhérents</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Affectés</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['affectes'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Non Affectés</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['non_affectes'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sans Agence</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sans_agence'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et Actions -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>Filtres et Actions en masse
        </h6>
        <button type="button" class="btn btn-primary btn-sm" id="btnAffecterSelection" disabled>
            <i class="fas fa-check me-1"></i>Affecter la sélection (<span id="selectionCount">0</span>)
        </button>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.affectations.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Recherche</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, prénom, membre ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Agence</label>
                <select name="agence_id" class="form-select">
                    <option value="">Toutes</option>
                    @foreach($agences as $agence)
                        <option value="{{ $agence->id }}" {{ request('agence_id') == $agence->id ? 'selected' : '' }}>
                            {{ $agence->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Agent</label>
                <select name="agent_id" class="form-select">
                    <option value="">Tous</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }} ({{ $agent->adherents_geres_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="non_affecte" {{ request('statut') == 'non_affecte' ? 'selected' : '' }}>Non affectés</option>
                    <option value="affecte" {{ request('statut') == 'affecte' ? 'selected' : '' }}>Affectés</option>
                    <option value="sans_agence" {{ request('statut') == 'sans_agence' ? 'selected' : '' }}>Sans agence</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>Filtrer
                </button>
                <a href="{{ route('admin.affectations.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-1"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Liste des adhérents -->
<div class="card shadow">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-users me-2"></i>Adhérents ({{ $adherents->total() }})
            </h6>
            <div>
                <input type="checkbox" id="selectAll" class="form-check-input me-2">
                <label for="selectAll" class="form-check-label">Tout sélectionner</label>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="30"></th>
                        <th>Membre ID</th>
                        <th>Nom complet</th>
                        <th>Agence</th>
                        <th>Agents gestionnaires</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adherents as $adherent)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input adherent-checkbox" 
                                   value="{{ $adherent->id }}" data-adherent-id="{{ $adherent->id }}">
                        </td>
                        <td>
                            <strong>{{ $adherent->membre_id }}</strong>
                        </td>
                        <td>
                            <a href="{{ route('admin.adherents.show', $adherent) }}" class="text-decoration-none">
                                {{ $adherent->nom_complet }}
                            </a>
                        </td>
                        <td>
                            @if($adherent->agence)
                                <span class="badge bg-info">{{ $adherent->agence->nom }}</span>
                            @else
                                <span class="badge bg-warning">Non affecté</span>
                            @endif
                        </td>
                        <td>
                            @if($adherent->agents_count > 0)
                                <div class="d-flex gap-1 flex-wrap">
                                    @foreach($adherent->agents->take(2) as $agent)
                                        <span class="badge {{ $agent->pivot->is_principal ? 'bg-success' : 'bg-secondary' }}" 
                                              title="{{ $agent->pivot->is_principal ? 'Principal' : 'Secondaire' }}">
                                            {{ $agent->name }}
                                        </span>
                                    @endforeach
                                    @if($adherent->agents_count > 2)
                                        <span class="badge bg-light text-dark">+{{ $adherent->agents_count - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-danger">Aucun agent</span>
                            @endif
                        </td>
                        <td>
                            @if($adherent->statut_compte == 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.adherents.affectation', $adherent) }}" 
                               class="btn btn-sm btn-primary" title="Gérer affectation">
                                <i class="fas fa-user-tie"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Aucun adhérent trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($adherents->hasPages())
            <div class="mt-3">
                {{ $adherents->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal d'affectation en masse -->
<div class="modal fade" id="affectationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.affectations.affecter-masse') }}" id="formAffectation">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-tie me-2"></i>Affectation en masse
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="selectedCountText">0 adhérent(s) sélectionné(s)</span>
                    </div>

                    <div class="mb-3">
                        <label for="modal_agence_id" class="form-label">
                            <i class="fas fa-building me-1"></i>Agence de rattachement
                        </label>
                        <select name="agence_id" id="modal_agence_id" class="form-select">
                            <option value="">-- Ne pas modifier --</option>
                            @foreach($agences as $agence)
                                <option value="{{ $agence->id }}">{{ $agence->nom }} - {{ $agence->departement }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Laissez vide pour ne pas modifier l'agence</small>
                    </div>

                    <div class="mb-3">
                        <label for="modal_agent_ids" class="form-label">
                            <i class="fas fa-user-check me-1"></i>Agents gestionnaires
                        </label>
                        
                        <!-- Barre de recherche -->
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchAgent" class="form-control" placeholder="Rechercher un agent...">
                        </div>
                        
                        <!-- Liste des agents avec checkboxes -->
                        <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;" id="agentsList">
                            @foreach($agents as $agent)
                                <div class="form-check agent-item" 
                                     data-agence="{{ $agent->agence_id ?? '' }}"
                                     data-search="{{ strtolower($agent->name . ' ' . ($agent->agence ? $agent->agence->nom : '')) }}">
                                    <input class="form-check-input agent-checkbox" 
                                           type="checkbox" 
                                           name="agent_ids[]" 
                                           value="{{ $agent->id }}" 
                                           id="agent_{{ $agent->id }}">
                                    <label class="form-check-label w-100" for="agent_{{ $agent->id }}" style="cursor: pointer;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $agent->name }}</strong>
                                                @if($agent->agence)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-building me-1"></i>{{ $agent->agence->nom }}
                                                    </small>
                                                @endif
                                            </div>
                                            <span class="badge bg-secondary">{{ $agent->adherents_geres_count }}</span>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <small class="form-text text-muted mt-2 d-block">
                            <i class="fas fa-info-circle me-1"></i>Sélectionnez un ou plusieurs agents. Le premier sera l'agent principal.
                            <span id="selectedAgentsCount" class="badge bg-primary ms-2">0 sélectionné(s)</span>
                        </small>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_principal" id="is_principal" checked value="1">
                        <label class="form-check-label" for="is_principal">
                            Définir le premier agent comme agent principal
                        </label>
                    </div>

                    <input type="hidden" name="adherent_ids" id="adherent_ids_input">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Affecter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.adherent-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');
    const btnAffecterSelection = document.getElementById('btnAffecterSelection');
    const selectionCount = document.getElementById('selectionCount');
    const affectationModal = new bootstrap.Modal(document.getElementById('affectationModal'));
    const agenceSelect = document.getElementById('modal_agence_id');
    const searchAgentInput = document.getElementById('searchAgent');
    const agentCheckboxes = document.querySelectorAll('.agent-checkbox');
    const agentItems = document.querySelectorAll('.agent-item');
    const selectedAgentsCount = document.getElementById('selectedAgentsCount');

    // Fonction pour mettre à jour le compteur de sélection
    function updateSelectionCount() {
        const checkedCount = document.querySelectorAll('.adherent-checkbox:checked').length;
        selectionCount.textContent = checkedCount;
        btnAffecterSelection.disabled = checkedCount === 0;
        document.getElementById('selectedCountText').textContent = `${checkedCount} adhérent(s) sélectionné(s)`;
    }

    // Gestion de la sélection individuelle
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectionCount();
            // Mettre à jour l'état de "Tout sélectionner"
            selectAllCheckbox.checked = document.querySelectorAll('.adherent-checkbox:checked').length === checkboxes.length;
        });
    });

    // Tout sélectionner / désélectionner
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionCount();
    });

    // Ouvrir le modal d'affectation
    btnAffecterSelection.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.adherent-checkbox:checked'))
            .map(cb => cb.value);
        
        document.getElementById('adherent_ids_input').value = JSON.stringify(selectedIds);
        affectationModal.show();
    });

    // Filtrer les agents par agence sélectionnée
    agenceSelect.addEventListener('change', function() {
        const selectedAgence = this.value;
        
        agentItems.forEach(item => {
            const agentAgence = item.dataset.agence;
            if (!selectedAgence || agentAgence === selectedAgence || !agentAgence) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
                // Décocher si masqué
                const checkbox = item.querySelector('.agent-checkbox');
                if (checkbox) checkbox.checked = false;
            }
        });
        
        updateAgentCount();
    });
    
    // Recherche d'agents
    searchAgentInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        agentItems.forEach(item => {
            const searchData = item.dataset.search;
            const matchesSearch = searchData.includes(searchTerm);
            
            // Vérifier aussi le filtre d'agence
            const selectedAgence = agenceSelect.value;
            const agentAgence = item.dataset.agence;
            const matchesAgence = !selectedAgence || agentAgence === selectedAgence || !agentAgence;
            
            if (matchesSearch && matchesAgence) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Mettre à jour le compteur d'agents sélectionnés
    function updateAgentCount() {
        const count = document.querySelectorAll('.agent-checkbox:checked').length;
        selectedAgentsCount.textContent = count + ' sélectionné(s)';
    }
    
    // Écouter les changements de sélection d'agents
    agentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateAgentCount);
    });
});
</script>
@endpush
@endsection
