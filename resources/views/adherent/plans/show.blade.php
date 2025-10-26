@extends('layouts.adherent-modern')

@section('title', $plan->nom . ' - Détail du Plan')

@section('content')
<div class="container py-4">
    <!-- Navigation breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('adherent.dashboard') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Tableau de bord
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('adherent.plans.index') }}" class="text-decoration-none">Plans disponibles</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $plan->nom }}</li>
        </ol>
    </nav>

    @include('components.alerts')

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- En-tête du plan -->
            <div class="card mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex align-items-center">
                        @if($plan->type_plan === 'epargne')
                            <i class="fas fa-piggy-bank fa-2x me-3"></i>
                        @else
                            <i class="fas fa-credit-card fa-2x me-3"></i>
                        @endif
                        <div>
                            <h3 class="mb-1">{{ $plan->nom }}</h3>
                            <p class="mb-0 opacity-75">
                                Plan {{ $plan->type_plan === 'epargne' ? "d'épargne" : 'de crédit' }}
                            </p>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                {{ $plan->taux_interet }}% d'intérêt
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text fs-6">{{ $plan->description }}</p>
                </div>
            </div>

            <!-- Caractéristiques détaillées -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Caractéristiques du Plan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon bg-success">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="feature-content">
                                    <h6 class="mb-1">Montant de contribution</h6>
                                    <p class="text-muted mb-0">
                                        Entre {{ number_format($plan->montant_min, 0, ',', ' ') }} FCFA 
                                        et {{ number_format($plan->montant_max, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon bg-info">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="feature-content">
                                    <h6 class="mb-1">Périodicité</h6>
                                    <p class="text-muted mb-0">{{ ucfirst($plan->periodicite) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon bg-warning">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="feature-content">
                                    <h6 class="mb-1">Taux d'intérêt</h6>
                                    <p class="text-muted mb-0">{{ $plan->taux_interet }}% par période</p>
                                </div>
                            </div>
                        </div>
                        @if($plan->duree_min_jours)
                        <div class="col-md-6">
                            <div class="feature-item">
                                <div class="feature-icon bg-secondary">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="feature-content">
                                    <h6 class="mb-1">Durée minimum</h6>
                                    <p class="text-muted mb-0">{{ $plan->duree_min_jours }} jours d'engagement</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Conditions et frais -->
            @if($plan->frais_adhesion > 0 || $plan->frais_retrait > 0 || $plan->conditions)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-contract me-2 text-warning"></i>Conditions et Frais
                    </h5>
                </div>
                <div class="card-body">
                    @if($plan->frais_adhesion > 0 || $plan->frais_retrait > 0)
                        <div class="row mb-3">
                            @if($plan->frais_adhesion > 0)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-user-plus text-primary me-3"></i>
                                    <div>
                                        <div class="fw-semibold">Frais d'adhésion</div>
                                        <div class="text-muted">{{ number_format($plan->frais_adhesion, 0, ',', ' ') }} FCFA</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($plan->frais_retrait > 0)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-money-bill-wave text-success me-3"></i>
                                    <div>
                                        <div class="fw-semibold">Frais de retrait</div>
                                        <div class="text-muted">{{ number_format($plan->frais_retrait, 0, ',', ' ') }} FCFA</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif

                    @if($plan->conditions)
                        <div>
                            <h6 class="text-muted mb-2">CONDITIONS GÉNÉRALES</h6>
                            <div class="text-muted">
                                @php
                                    $conditions = is_array($plan->conditions) ? implode("\n", $plan->conditions) : $plan->conditions;
                                @endphp
                                {!! nl2br(e($conditions)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Simulateur -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2 text-success"></i>Simulateur de Gains
                    </h5>
                </div>
                <div class="card-body">
                    <form id="simulatorForm" class="row g-3">
                        <div class="col-md-6">
                            <label for="montant_simule" class="form-label">Montant à investir (FCFA)</label>
                            <input type="number" class="form-control" id="montant_simule" 
                                   min="{{ $plan->montant_min }}" max="{{ $plan->montant_max }}" 
                                   value="{{ $plan->montant_min }}" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label for="duree_simule" class="form-label">Nombre de périodes</label>
                            <input type="number" class="form-control" id="duree_simule" 
                                   min="1" max="36" value="6">
                        </div>
                        <div class="col-12">
                            <div class="alert alert-success" id="simulation_result">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="h5 mb-1" id="total_verse">0 FCFA</div>
                                        <small class="text-muted">Total versé</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="h5 mb-1 text-success" id="total_interets">0 FCFA</div>
                                        <small class="text-muted">Intérêts gagnés</small>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="h5 mb-1 text-primary" id="total_final">0 FCFA</div>
                                        <small class="text-muted">Montant final</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar droite -->
        <div class="col-lg-4">
            <!-- Mes adhésions à ce plan -->
            @php
                $mesAdhesions = $plan->adhesions->where('adherent_id', auth()->id());
            @endphp
            @if($mesAdhesions->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-check me-2 text-info"></i>Mes Adhésions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="h4 text-primary">{{ $mesAdhesions->count() }}</div>
                        <div class="text-muted small">Adhésion(s) à ce plan</div>
                    </div>
                    
                    @foreach($mesAdhesions->take(3) as $adhesion)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <div class="fw-semibold">{{ number_format($adhesion->montant, 0, ',', ' ') }} FCFA</div>
                                <small class="text-muted">{{ $adhesion->created_at->format('d/m/Y') }}</small>
                            </div>
                            <span class="badge bg-{{ $adhesion->statut === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($adhesion->statut) }}
                            </span>
                        </div>
                    @endforeach

                    @if($mesAdhesions->count() > 3)
                        <small class="text-muted">et {{ $mesAdhesions->count() - 3 }} autre(s)...</small>
                    @endif

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('adherent.adhesions.index', ['plan' => $plan->id]) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-cog me-1"></i>Gérer mes adhésions
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions principales -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-rocket me-2 text-primary"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($mesAdhesions->where('statut', 'active')->count() === 0)
                            <a href="{{ route('adherent.adhesions.create', ['plan' => $plan->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>Souscrire à ce plan
                            </a>
                        @else
                            <div class="alert alert-info py-2 mb-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    Vous avez déjà une adhésion active
                                </small>
                            </div>
                            <a href="{{ route('adherent.adhesions.create', ['plan' => $plan->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus-circle me-2"></i>Nouvelle adhésion
                            </a>
                        @endif
                        
                        <a href="{{ route('adherent.plans.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour aux plans
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistiques générales -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-warning"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h5 text-success">{{ $plan->adhesions_count ?? $plan->adhesions->count() }}</div>
                            <small class="text-muted">Adhérents</small>
                        </div>
                        <div class="col-6">
                            <div class="h5 text-info">{{ number_format($plan->adhesions->sum('montant'), 0, ',', ' ') }} FCFA</div>
                            <small class="text-muted">Montant total</small>
                        </div>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-users me-1"></i>
                        Rejoignez {{ $plan->adhesions->count() }} autre(s) adhérent(s) sur ce plan
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.feature-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.feature-content h6 {
    font-weight: 600;
    color: #333;
}

.card-header h5 {
    font-weight: 600;
}

@media (max-width: 768px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .feature-item {
        margin-bottom: 1.5rem;
    }
    
    .card-header h3 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const montantInput = document.getElementById('montant_simule');
    const dureeInput = document.getElementById('duree_simule');
    const tauxInteret = {{ $plan->taux_interet }};
    
    function calculerSimulation() {
        const montant = parseFloat(montantInput.value) || 0;
        const duree = parseInt(dureeInput.value) || 0;
        
        if (montant > 0 && duree > 0) {
            // Calcul simple des intérêts composés
            const totalVerse = montant * duree;
            const totalInterets = totalVerse * (tauxInteret / 100);
            const totalFinal = totalVerse + totalInterets;
            
            // Mise à jour de l'affichage
            document.getElementById('total_verse').textContent = 
                new Intl.NumberFormat('fr-FR').format(totalVerse) + ' FCFA';
            document.getElementById('total_interets').textContent = 
                new Intl.NumberFormat('fr-FR').format(totalInterets) + ' FCFA';
            document.getElementById('total_final').textContent = 
                new Intl.NumberFormat('fr-FR').format(totalFinal) + ' FCFA';
        }
    }
    
    // Calcul initial
    calculerSimulation();
    
    // Mise à jour en temps réel
    montantInput.addEventListener('input', calculerSimulation);
    dureeInput.addEventListener('input', calculerSimulation);
    
    // Validation des limites
    montantInput.addEventListener('input', function() {
        const min = {{ $plan->montant_min }};
        const max = {{ $plan->montant_max }};
        const value = parseFloat(this.value);
        
        if (value < min) {
            this.setCustomValidity('Le montant minimum est de ' + min.toLocaleString() + ' FCFA');
        } else if (value > max) {
            this.setCustomValidity('Le montant maximum est de ' + max.toLocaleString() + ' FCFA');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endpush
@endsection
