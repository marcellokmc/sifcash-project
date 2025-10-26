@extends('backoffice.layouts.app')

@section('title', 'Affectations par agent')
@section('page-title', 'Affectations par Agent')

@section('content')
<div class="row">
    <!-- Liste des agents -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-tie me-2"></i>Agents
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    @foreach($agents as $agent)
                        <a href="{{ route('admin.affectations.par-agent', $agent->id) }}" 
                           class="list-group-item list-group-item-action {{ $agentSelectionne && $agentSelectionne->id == $agent->id ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user me-2"></i>
                                    <strong>{{ $agent->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ ucfirst(str_replace('_', ' ', $agent->role)) }}
                                        @if($agent->agence)
                                            - {{ $agent->agence->nom }}
                                        @endif
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill">{{ $agent->adherents_geres_count }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de l'agent sélectionné -->
    <div class="col-md-8">
        @if($agentSelectionne)
            <!-- Info agent -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">
                                <i class="fas fa-user-circle text-success me-2"></i>{{ $agentSelectionne->name }}
                            </h4>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $agentSelectionne->role)) }}</span>
                                @if($agentSelectionne->agence)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-building me-1"></i>{{ $agentSelectionne->agence->nom }}
                                    </span>
                                @endif
                                <span class="badge bg-secondary">
                                    <i class="fas fa-envelope me-1"></i>{{ $agentSelectionne->email }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2 class="text-success mb-0">{{ $stats['total_adherents'] }}</h2>
                            <small class="text-muted">adhérents gérés</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Principal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['adherents_principaux'] }}</div>
                            <small class="text-muted">Responsable principal</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Secondaire</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['adherents_secondaires'] }}</div>
                            <small class="text-muted">Agent secondaire</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_adherents'] }}</div>
                            <small class="text-muted">Tous les adhérents</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adhérents gérés -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-users me-2"></i>Adhérents gérés ({{ $adherents->total() }})
                        </h6>
                        <form method="GET" action="{{ route('admin.affectations.par-agent', $agentSelectionne->id) }}" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Rechercher..." value="{{ request('search') }}" style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Membre ID</th>
                                    <th>Nom complet</th>
                                    <th>Agence</th>
                                    <th>Rôle</th>
                                    <th>Autres agents</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($adherents as $adherent)
                                <tr>
                                    <td><strong>{{ $adherent->membre_id }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.adherents.show', $adherent) }}">
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
                                        @php
                                            $pivotAgent = $adherent->agents->where('id', $agentSelectionne->id)->first();
                                            $isPrincipal = $pivotAgent ? $pivotAgent->pivot->is_principal : false;
                                        @endphp
                                        @if($isPrincipal)
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i>Principal
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Secondaire</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $autresAgents = $adherent->agents->where('id', '!=', $agentSelectionne->id);
                                        @endphp
                                        @if($autresAgents->count() > 0)
                                            <div class="d-flex gap-1 flex-wrap">
                                                @foreach($autresAgents->take(2) as $agent)
                                                    <span class="badge bg-secondary" title="{{ $agent->name }}">
                                                        {{ $agent->name }}
                                                    </span>
                                                @endforeach
                                                @if($autresAgents->count() > 2)
                                                    <span class="badge bg-light text-dark">+{{ $autresAgents->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Seul</span>
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
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.adherents.show', $adherent) }}" 
                                               class="btn btn-outline-primary" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.adherents.affectation', $adherent) }}" 
                                               class="btn btn-outline-success" title="Gérer affectation">
                                                <i class="fas fa-user-tie"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="retirerAgent({{ $adherent->id }}, {{ $agentSelectionne->id }})"
                                                    title="Retirer cet agent">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Aucun adhérent géré par cet agent</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($adherents->hasPages())
                        <div class="mt-3">
                            {{ $adherents->appends(['search' => request('search')])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-tie fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">Sélectionnez un agent</h5>
                    <p class="text-muted">Choisissez un agent dans la liste de gauche pour voir ses adhérents</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Formulaire caché pour retirer un agent -->
<form id="formRetirerAgent" method="POST" action="{{ route('admin.affectations.retirer-agent') }}" style="display: none;">
    @csrf
    <input type="hidden" name="adherent_id" id="adherent_id_retirer">
    <input type="hidden" name="agent_id" id="agent_id_retirer">
</form>

@push('scripts')
<script>
function retirerAgent(adherentId, agentId) {
    if (confirm('Êtes-vous sûr de vouloir retirer cet agent de cet adhérent ?')) {
        document.getElementById('adherent_id_retirer').value = adherentId;
        document.getElementById('agent_id_retirer').value = agentId;
        document.getElementById('formRetirerAgent').submit();
    }
}
</script>
@endpush
@endsection
