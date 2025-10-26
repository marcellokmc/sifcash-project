@extends('backoffice.layouts.app')

@section('title', 'Détails Plan - ' . $plan->nom)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-1">{{ $plan->nom }}</h2>
            <p class="text-muted mb-0">
                <i class="fas {{ $plan->type_plan == 'epargne' ? 'fa-piggy-bank text-success' : 'fa-credit-card text-primary' }} me-2"></i>
                Plan {{ $plan->type_plan == 'epargne' ? "d'épargne" : 'de crédit' }} - 
                {{ $plan->actif ? 'Actif' : 'Inactif' }}
            </p>
        </div>
        <div>
            @if($plan->actif)
                <form action="{{ route('admin.plans.deactivate', $plan) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Désactiver ce plan ?')">
                        <i class="fas fa-pause me-1"></i>Désactiver
                    </button>
                </form>
            @else
                <form action="{{ route('admin.plans.activate', $plan) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success me-2" onclick="return confirm('Activer ce plan ?')">
                        <i class="fas fa-play me-1"></i>Activer
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
            <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    @include('components.alerts')

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations du plan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations du Plan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nom du plan</label>
                                <div class="fw-bold">{{ $plan->nom }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Type de plan</label>
                                <div>
                                    <span class="badge {{ $plan->type_plan == 'epargne' ? 'bg-success' : 'bg-primary' }}">
                                        <i class="fas {{ $plan->type_plan == 'epargne' ? 'fa-piggy-bank' : 'fa-credit-card' }} me-1"></i>
                                        Plan {{ $plan->type_plan == 'epargne' ? "d'épargne" : 'de crédit' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Périodicité</label>
                                <div>{{ ucfirst($plan->periodicite) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Statut</label>
                                <div>
                                    <span class="badge {{ $plan->actif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $plan->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Durée minimum</label>
                                <div>{{ $plan->duree_min_jours }} jours</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Ordre d'affichage</label>
                                <div>{{ $plan->ordre_affichage }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Description</label>
                                <div class="border-start border-3 border-primary ps-3 bg-light p-3 rounded-end">
                                    {{ $plan->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration financière -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2 text-success"></i>Configuration Financière
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center border-end">
                                <div class="h4 text-primary mb-1">{{ number_format($plan->montant_min, 0, ',', ' ') }}</div>
                                <div class="text-muted small">Montant Minimum (FCFA)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center border-end">
                                <div class="h4 text-primary mb-1">{{ number_format($plan->montant_max, 0, ',', ' ') }}</div>
                                <div class="text-muted small">Montant Maximum (FCFA)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h4 text-success mb-1">{{ $plan->taux_interet }}%</div>
                                <div class="text-muted small">Taux d'Intérêt</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($plan->frais_adhesion > 0 || $plan->frais_retrait > 0)
                    <hr class="my-4">
                    <div class="row">
                        @if($plan->frais_adhesion > 0)
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h5 text-warning mb-1">{{ number_format($plan->frais_adhesion, 0, ',', ' ') }} FCFA</div>
                                <div class="text-muted small">Frais d'Adhésion</div>
                            </div>
                        </div>
                        @endif
                        @if($plan->frais_retrait > 0)
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h5 text-info mb-1">{{ number_format($plan->frais_retrait, 0, ',', ' ') }} FCFA</div>
                                <div class="text-muted small">Frais de Retrait</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Conditions et règles -->
            @if($plan->conditions)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gavel me-2 text-warning"></i>Conditions et Règles
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $conditions = is_string($plan->conditions) ? json_decode($plan->conditions, true) : $plan->conditions;
                    @endphp
                    
                    @if(is_array($conditions))
                        <div class="row">
                            @foreach($conditions as $key => $condition)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <strong class="ms-2">{{ is_bool($condition) ? ($condition ? 'Oui' : 'Non') : $condition }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="border-start border-3 border-warning ps-3 bg-light p-3 rounded-end">
                            {{ $plan->conditions }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Liste des adhésions -->
            @if($plan->adhesions && $plan->adhesions->count() > 0)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-info"></i>
                        Adhésions à ce Plan ({{ $plan->adhesions->count() }})
                    </h5>
                    <a href="{{ route('admin.adhesions.index', ['plan_id' => $plan->id]) }}" class="btn btn-sm btn-outline-info">
                        Voir toutes les adhésions
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Adhérent</th>
                                    <th>Montant</th>
                                    <th>Date adhésion</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plan->adhesions->take(10) as $adhesion)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($adhesion->adherent->nom_complet) }}&size=32" 
                                                     class="rounded-circle" width="32" height="32">
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $adhesion->adherent->nom_complet }}</div>
                                                <small class="text-muted">{{ $adhesion->adherent->membre_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($adhesion->montant, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td>{{ $adhesion->date_adhesion ? $adhesion->date_adhesion->format('d/m/Y') : 'Non renseignée' }}</td>
                                    <td>
                                        @switch($adhesion->statut)
                                            @case('active')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Active
                                                </span>
                                                @break
                                            @case('suspendue')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-pause me-1"></i>Suspendue
                                                </span>
                                                @break
                                            @case('close')
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Clôturée
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-info">{{ ucfirst($adhesion->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.adhesions.show', $adhesion) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($plan->adhesions->count() > 10)
                <div class="card-footer text-center">
                    <a href="{{ route('admin.adhesions.index', ['plan_id' => $plan->id]) }}" class="btn btn-outline-primary">
                        Voir les {{ $plan->adhesions->count() - 10 }} autres adhésions
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar droite -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h3 text-primary mb-1">{{ $plan->adhesions->count() }}</div>
                            <div class="text-muted small">Adhésions totales</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h3 text-success mb-1">{{ $plan->adhesions->where('statut', 'active')->count() }}</div>
                            <div class="text-muted small">Adhésions actives</div>
                        </div>
                        <div class="col-6">
                            <div class="h3 text-warning mb-1">{{ $plan->adhesions->where('statut', 'suspendue')->count() }}</div>
                            <div class="text-muted small">Suspendues</div>
                        </div>
                        <div class="col-6">
                            <div class="h3 text-secondary mb-1">{{ $plan->adhesions->where('statut', 'close')->count() }}</div>
                            <div class="text-muted small">Clôturées</div>
                        </div>
                    </div>

                    @if($plan->adhesions->count() > 0)
                    <hr>
                    <div class="text-center">
                        <div class="h4 text-info mb-1">
                            {{ number_format($plan->adhesions->sum('montant'), 0, ',', ' ') }} FCFA
                        </div>
                        <div class="text-muted small">Montant total des adhésions</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier ce plan
                        </a>
                        <a href="{{ route('admin.adhesions.index', ['plan_id' => $plan->id]) }}" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>Voir les adhésions
                        </a>
                        <a href="{{ route('adherent.plans.show', $plan) }}" class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Vue adhérent
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-secondary"></i>Informations Système
                    </h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <div class="mb-2">
                            <strong>ID:</strong> #{{ $plan->id }}
                        </div>
                        <div class="mb-2">
                            <strong>Créé le:</strong> {{ $plan->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>Modifié le:</strong> {{ $plan->updated_at->format('d/m/Y H:i') }}
                        </div>
                        <div>
                            <strong>Statut:</strong> 
                            <span class="badge {{ $plan->actif ? 'bg-success' : 'bg-secondary' }} ms-1">
                                {{ $plan->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection