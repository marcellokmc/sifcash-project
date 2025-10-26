@extends('backoffice.layouts.app')

@section('title', 'Affectations par agence')
@section('page-title', 'Affectations par Agence')

@section('content')
<div class="row">
    <!-- Liste des agences -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building me-2"></i>Agences
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($agences as $agence)
                        <a href="{{ route('admin.affectations.par-agence', $agence->id) }}" 
                           class="list-group-item list-group-item-action {{ $agenceSelectionnee && $agenceSelectionnee->id == $agence->id ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-building me-2"></i>
                                    <strong>{{ $agence->nom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $agence->ville ?? $agence->departement }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill">{{ $agence->adherents_count }}</span>
                                    <br>
                                    <small class="text-muted">{{ $agence->users_count }} agents</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de l'agence sélectionnée -->
    <div class="col-md-8">
        @if($agenceSelectionnee)
            <!-- Statistiques de l'agence -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Adhérents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_adherents'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Affectés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['adherents_affectes'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Non affectés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['adherents_non_affectes'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Agents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['agents_actifs'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agents de l'agence -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-user-tie me-2"></i>Agents de {{ $agenceSelectionnee->nom }}
                    </h6>
                </div>
                <div class="card-body">
                    @if($agents->count() > 0)
                        <div class="row">
                            @foreach($agents as $agent)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-user me-1"></i>{{ $agent->name }}
                                                    </h6>
                                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $agent->role)) }}</span>
                                                </div>
                                                <div class="text-end">
                                                    <h4 class="text-success mb-0">{{ $agent->adherents_geres_count }}</h4>
                                                    <small class="text-muted">adhérents</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="{{ route('admin.affectations.par-agent', $agent->id) }}" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i>Voir les adhérents
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user-times fa-3x mb-3"></i>
                            <p>Aucun agent actif dans cette agence</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Adhérents de l'agence -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Adhérents de {{ $agenceSelectionnee->nom }} ({{ $adherents->total() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Membre ID</th>
                                    <th>Nom complet</th>
                                    <th>Agents gestionnaires</th>
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
                                        @if($adherent->agents_count > 0)
                                            <div class="d-flex gap-1 flex-wrap">
                                                @foreach($adherent->agents as $agent)
                                                    <span class="badge {{ $agent->pivot->is_principal ? 'bg-success' : 'bg-secondary' }}" 
                                                          title="{{ $agent->pivot->is_principal ? 'Principal' : 'Secondaire' }}">
                                                        {{ $agent->name }}
                                                    </span>
                                                @endforeach
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
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Aucun adhérent dans cette agence</p>
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
        @else
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">Sélectionnez une agence</h5>
                    <p class="text-muted">Choisissez une agence dans la liste de gauche pour voir ses adhérents et agents</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
