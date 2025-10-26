@extends('layouts.adherent-modern')

@section('title', 'Historique des Paiements')

@push('styles')
<style>
.payment-card {
    transition: all 0.3s ease;
    border-left: 4px solid #dee2e6;
}
.payment-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.payment-card.validated {
    border-left-color: #28a745;
}
.payment-card.pending {
    border-left-color: #ffc107;
}
.payment-card.rejected {
    border-left-color: #dc3545;
}
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}
.amount-badge {
    font-size: 1.1rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px;
}
.credit-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête avec breadcrumb et statistiques -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-0 text-dark">
                        <i class="fas fa-credit-card me-2 text-primary"></i>
                        Historique des Paiements de Crédit
                    </h4>
                    <p class="text-secondary fw-medium mb-0 mt-1">Suivi de tous vos paiements effectués</p>
                </div>
                <div>
                    <ol class="breadcrumb bg-transparent p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('adherent.dashboard') }}" class="text-decoration-none">
                                <i class="fas fa-home"></i> Tableau de bord
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('adherent.credits.index') }}" class="text-decoration-none">
                                <i class="fas fa-university"></i> Mes Crédits
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="fas fa-history"></i> Paiements
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1 text-dark fw-bold">{{ $paiements->where('statut', 'valide')->count() }}</h4>
                            <p class="mb-0 text-dark fw-semibold">Paiements validés</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-3x text-success opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $paiements->where('statut', 'en_attente')->count() }}</h4>
                            <p class="mb-0">En attente</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($paiements->where('statut', 'valide')->sum('montant'), 0, ',', ' ') }}</h4>
                            <p class="mb-0">Total payé (FCFA)</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $paiements->total() }}</h4>
                            <p class="mb-0">Total paiements</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et contenu principal -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="card-title mb-0 text-dark">
                                <i class="fas fa-history text-primary"></i>
                                Historique détaillé
                            </h4>
                            <p class="text-secondary fw-medium small mb-0">Liste de tous vos paiements effectués</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="filter" id="all" autocomplete="off" checked onclick="filterPayments('all')">
                                <label class="btn btn-outline-secondary btn-sm" for="all">
                                    <i class="fas fa-list"></i> Tous
                                </label>

                                <input type="radio" class="btn-check" name="filter" id="validated" autocomplete="off" onclick="filterPayments('valide')">
                                <label class="btn btn-outline-success btn-sm" for="validated">
                                    <i class="fas fa-check-circle"></i> Validés
                                </label>

                                <input type="radio" class="btn-check" name="filter" id="pending" autocomplete="off" onclick="filterPayments('en_attente')">
                                <label class="btn btn-outline-warning btn-sm" for="pending">
                                    <i class="fas fa-clock"></i> En attente
                                </label>
                            </div>
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> Imprimer
                            </button>
                        </div>
                    </div>

                    @if($paiements->count() > 0)
                    <div class="row">
                        @foreach($paiements as $paiement)
                        <div class="col-12 mb-3">
                            <div class="payment-card card {{ $paiement->statut == 'valide' ? 'validated' : ($paiement->statut == 'en_attente' ? 'pending' : 'rejected') }}" data-status="{{ $paiement->statut }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 col-md-3">
                                            <div class="text-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                    <i class="fas fa-credit-card text-primary fa-2x"></i>
                                                </div>
                                                <span class="badge amount-badge bg-{{ $paiement->statut == 'valide' ? 'success' : ($paiement->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                                    {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-4">
                                            <div class="credit-info">
                                                <h6 class="mb-1 fw-bold text-dark">
                                                    <i class="fas fa-university text-primary"></i>
                                                    Crédit {{ number_format($paiement->credit->montant_accorde, 0, ',', ' ') }} FCFA
                                                </h6>
                                                <p class="text-secondary fw-medium small mb-0">
                                                    <i class="fas fa-calendar-alt text-info"></i>
                                                    {{ $paiement->credit->duree }} mois - Taux {{ $paiement->credit->taux }}%
                                                </p>
                                                @if($paiement->echeance)
                                                <p class="text-secondary fw-medium small mb-0">
                                                    <i class="fas fa-calendar-check text-success"></i>
                                                    Échéance du {{ $paiement->echeance->date_echeance->format('d/m/Y') }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-2">
                                            <div class="text-center">
                                                <h6 class="text-secondary fw-semibold mb-1">Date de paiement</h6>
                                                <p class="mb-0 fw-bold text-dark">{{ $paiement->date_paiement->format('d/m/Y') }}</p>
                                                <small class="text-secondary">{{ $paiement->date_paiement->format('H:i') }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-2">
                                            <div class="text-center">
                                                @if($paiement->penalite > 0)
                                                <h6 class="text-secondary fw-semibold mb-1">Pénalité</h6>
                                                <span class="badge bg-danger fs-6">
                                                    {{ number_format($paiement->penalite, 0, ',', ' ') }} FCFA
                                                </span>
                                                @else
                                                <h6 class="text-secondary fw-semibold mb-1">Pénalité</h6>
                                                <span class="badge bg-success fs-6">Aucune</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-2 col-md-2">
                                            <div class="text-center">
                                                <div class="mb-2">
                                                    @switch($paiement->statut)
                                                        @case('valide')
                                                            <span class="badge bg-success p-2">
                                                                <i class="fas fa-check-circle"></i> Validé
                                                            </span>
                                                            @break
                                                        @case('en_attente')
                                                            <span class="badge bg-warning p-2">
                                                                <i class="fas fa-clock"></i> En attente
                                                            </span>
                                                            @break
                                                        @case('rejete')
                                                            <span class="badge bg-danger p-2">
                                                                <i class="fas fa-times-circle"></i> Rejeté
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-light text-dark p-2">{{ ucfirst($paiement->statut) }}</span>
                                                    @endswitch
                                                </div>
                                                
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <a href="{{ route('adherent.credits.paiements.show', $paiement) }}" 
                                                       class="btn btn-outline-primary btn-sm" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($paiement->preuves && $paiement->preuves->count() > 0)
                                                    <button type="button" 
                                                            class="btn btn-outline-info btn-sm" 
                                                            onclick="showPreuves({{ $paiement->id }})"
                                                            title="Voir les preuves">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card text-primary" style="font-size: 4rem; opacity: 0.5;"></i>
                        <h5 class="text-dark my-3">Aucun paiement trouvé</h5>
                        <p class="text-secondary fw-medium mb-4">Vous n'avez effectué aucun paiement de crédit pour le moment.</p>
                        <a href="{{ route('adherent.credits.index') }}" class="btn btn-primary">
                            <i class="fas fa-university"></i> Voir mes crédits
                        </a>
                    </div>
                    @endif

                    @if($paiements->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Affichage de <strong>{{ $paiements->firstItem() }}</strong> à <strong>{{ $paiements->lastItem() }}</strong> sur <strong>{{ $paiements->total() }}</strong> paiements
                        </div>
                        <div>
                            {{ $paiements->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'affichage des preuves -->
<div class="modal fade" id="preuvesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i>Preuves de Paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="preuves-content">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialiser les tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function filterPayments(status) {
    const cards = document.querySelectorAll('.payment-card');
    
    cards.forEach(card => {
        if (status === 'all' || card.getAttribute('data-status') === status) {
            card.style.display = 'block';
            card.closest('.col-12').style.display = 'block';
        } else {
            card.style.display = 'none';
            card.closest('.col-12').style.display = 'none';
        }
    });
    
    // Animation pour les cartes visibles
    const visibleCards = document.querySelectorAll('.payment-card[style*="display: block"], .payment-card:not([style])');
    visibleCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 50);
    });
}

function showPreuves(paiementId) {
    // Afficher un loader
    const content = document.getElementById('preuves-content');
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2 text-muted">Chargement des preuves...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('preuvesModal'));
    modal.show();
    
    // Charger les preuves via AJAX
    fetch(`/adherent/credits/paiements/${paiementId}/preuves`)
        .then(response => response.json())
        .then(data => {
            content.innerHTML = data.html;
        })
        .catch(error => {
            console.error('Erreur:', error);
            content.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                    <h5 class="text-danger mt-2">Erreur de chargement</h5>
                    <p class="text-muted">Impossible de charger les preuves. Veuillez réessayer.</p>
                </div>
            `;
        });
}

// Effet de hover sur les cartes
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.payment-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
