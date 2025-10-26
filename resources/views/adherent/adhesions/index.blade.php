@extends('layouts.adherent-modern')

@section('title', 'Mes Adhésions')

@section('content')
<div class="container py-4">
    <!-- En-tête -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h2 class="text-primary mb-1">
                <i class="fas fa-handshake me-2"></i>Mes Adhésions
            </h2>
            <p class="text-muted mb-0">Gérez vos souscriptions aux plans d'épargne et de crédit</p>
        </div>
        <div class="d-grid d-md-block gap-2">
            <a href="{{ route('adherent.plans.index') }}" class="btn btn-outline-primary me-md-2">
                <i class="fas fa-search me-1"></i>Découvrir les plans
            </a>
            <a href="{{ route('adherent.adhesions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Nouvelle adhésion
            </a>
        </div>
    </div>

    @include('components.alerts')

    <!-- Statistiques -->
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body py-3">
                    <i class="fas fa-clipboard-list text-primary fa-2x mb-2"></i>
                    <div class="h4 text-primary mb-1">{{ $stats['total'] ?? 0 }}</div>
                    <small class="text-muted">Total adhésions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body py-3">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <div class="h4 text-success mb-1">{{ $stats['actives'] ?? 0 }}</div>
                    <small class="text-muted">Adhésions actives</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body py-3">
                    <i class="fas fa-pause-circle text-warning fa-2x mb-2"></i>
                    <div class="h4 text-warning mb-1">{{ $stats['suspendues'] ?? 0 }}</div>
                    <small class="text-muted">Suspendues</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body py-3">
                    <i class="fas fa-wallet text-info fa-2x mb-2"></i>
                    <div class="h4 text-info mb-1">{{ number_format($stats['solde_total'] ?? 0, 0, ',', ' ') }}</div>
                    <small class="text-muted">Solde total (FCFA)</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <form method="GET" id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="statut" class="form-label small">Statut</label>
                            <select name="statut" id="statut" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Tous les statuts</option>
                                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Active</option>
                                <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendue</option>
                                <option value="clos" {{ request('statut') == 'clos' ? 'selected' : '' }}>Clos</option>
                                <option value="en_attente_activation" {{ request('statut') == 'en_attente_activation' ? 'selected' : '' }}>En attente</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="plan" class="form-label small">Plan</label>
                            <select name="plan" id="plan" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Tous les plans</option>
                                @if(isset($plans))
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->nom }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label small">Depuis le</label>
                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" 
                                   value="{{ request('date_from') }}" onchange="document.getElementById('filterForm').submit();">
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-refresh me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des adhésions -->
    @if($adhesions && $adhesions->count() > 0)
        <div class="row">
            @foreach($adhesions as $adhesion)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card h-100 adhesion-card" data-adhesion-id="{{ $adhesion->id }}">
                        <!-- En-tête de la carte -->
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title mb-1">
                                    <i class="fas fa-piggy-bank text-success me-2"></i>
                                    {{ $adhesion->plan->nom }}
                                </h6>
                                <small class="text-muted">
                                    Épargne • {{ ucfirst($adhesion->plan->periodicite) }}
                                </small>
                            </div>
                            <span class="badge bg-{{ 
                                $adhesion->statut === 'actif' ? 'success' : 
                                ($adhesion->statut === 'suspendu' ? 'warning' : 
                                ($adhesion->statut === 'clos' ? 'info' : 'secondary')) 
                            }}">
                                {{ ucfirst($adhesion->statut) }}
                            </span>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body">
                            <!-- Montant principal -->
                            <div class="text-center mb-3 py-2 bg-light rounded">
                                <div class="h5 text-primary mb-0">{{ number_format($adhesion->montant ?? $adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</div>
                                <small class="text-muted">Montant de cotisation</small>
                            </div>

                            <!-- Informations détaillées -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Taux d'intérêt</small>
                                    <strong class="text-success">{{ $adhesion->plan->taux_interet }}%</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Date d'adhésion</small>
                                    <strong>{{ $adhesion->date_debut ? $adhesion->date_debut->format('d/m/Y') : $adhesion->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </div>

                            <!-- Solde actuel (si disponible) -->
                            @if(isset($adhesion->solde_actuel))
                                <div class="alert alert-success py-2 mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small><i class="fas fa-wallet me-1"></i>Solde actuel</small>
                                        <strong>{{ number_format($adhesion->solde_actuel, 0, ',', ' ') }} FCFA</strong>
                                    </div>
                                </div>
                            @endif

                            <!-- Dernière activité -->
                            <div class="text-muted small mb-2">
                                <i class="fas fa-clock me-1"></i>
                                Mise à jour {{ $adhesion->updated_at->diffForHumans() }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer bg-white border-top">
                            <div class="d-grid gap-1">
                                <a href="{{ route('adherent.adhesions.show', $adhesion) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir détails
                                </a>
                                @if($adhesion->statut === 'actif')
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('adherent.paiements.create', ['adhesion' => $adhesion->id]) }}" class="btn btn-success">
                                            <i class="fas fa-plus-circle me-1"></i>Dépôt
                                        </a>
                                        <a href="{{ route('adherent.retraits.create', ['adhesion' => $adhesion->id]) }}" class="btn btn-warning">
                                            <i class="fas fa-minus-circle me-1"></i>Retrait
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($adhesions, 'hasPages') && $adhesions->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $adhesions->links() }}
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Aucune adhésion -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-handshake text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucune adhésion trouvée</h4>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['statut', 'plan', 'date_from']))
                            Aucune adhésion ne correspond à vos critères de recherche.
                        @else
                            Vous n'avez pas encore souscrit à un plan.
                        @endif
                    </p>
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        @if(request()->hasAny(['statut', 'plan', 'date_from']))
                            <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-refresh me-2"></i>Voir toutes mes adhésions
                            </a>
                        @endif
                        <a href="{{ route('adherent.plans.index') }}" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Découvrir les plans
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.adhesion-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #dee2e6;
}

.adhesion-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .adhesion-card {
        margin-bottom: 1rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 768px) {
    .d-flex.flex-column.flex-md-row {
        align-items: stretch !important;
    }
    
    .d-grid.d-md-block {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au scroll
    const cards = document.querySelectorAll('.adhesion-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });
    
    // Tooltip pour les badges de statut
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
@endsection
