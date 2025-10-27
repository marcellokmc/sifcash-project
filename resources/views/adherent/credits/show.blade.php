@extends('layouts.adherent-modern')

@section('title', 'Crédit #'.$credit->id)

@push('styles')
<style>
/* SURCHARGE IMPORTANTE : Le layout force les backgrounds transparents, on doit les override */
.card, .card-header, .card-body, .card-footer {
    background: initial !important;
}
/* Carte statistique avec effet hover */
.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    background-color: #ffffff !important;
}

.stat-card .card-body {
    background-color: #ffffff !important;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}

/* Barre de progression fine */
.progress-thin {
    height: 6px;
    border-radius: 10px;
}

/* Carte d'échéance avec bordure colorée */
.echeance-card {
    border-left: 5px solid #dee2e6;
    transition: all 0.3s ease;
    border-radius: 8px;
    background: #ffffff !important;
}
.echeance-card.paid {
    border-left-color: #10b981;
    background: linear-gradient(to right, #f0fdf4 0%, #ffffff 100%) !important;
}
.echeance-card.paid .card-body {
    background: linear-gradient(to right, #f0fdf4 0%, #ffffff 100%) !important;
}
.echeance-card.overdue {
    border-left-color: #ef4444;
    background: linear-gradient(to right, #fef2f2 0%, #ffffff 100%) !important;
}
.echeance-card.overdue .card-body {
    background: linear-gradient(to right, #fef2f2 0%, #ffffff 100%) !important;
}
.echeance-card.pending {
    border-left-color: #f59e0b;
    background: linear-gradient(to right, #fffbeb 0%, #ffffff 100%) !important;
}
.echeance-card.pending .card-body {
    background: linear-gradient(to right, #fffbeb 0%, #ffffff 100%) !important;
}

/* En-tête de carte avec gradient */
.card-header-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none !important;
    border-radius: 12px 12px 0 0 !important;
    color: #ffffff !important;
}

.card-header-success-gradient {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    border: none !important;
    border-radius: 12px 12px 0 0 !important;
    color: #ffffff !important;
}

/* Cards avec border radius moderne */
.modern-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    background-color: #ffffff !important;
}

.modern-card .card-body {
    background-color: #ffffff !important;
}

.modern-card .card-header {
    background: initial !important;
}

/* Badge moderne */
.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #ffffff !important;
}

/* Bouton collapse toggle */
.collapse-toggle-btn {
    transition: all 0.3s ease;
}
.collapse-toggle-btn:not(.collapsed) i {
    transform: rotate(180deg);
}

/* Table responsive améliorée */
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

.table {
    background-color: #ffffff !important;
}

.table tbody tr {
    background-color: #ffffff !important;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .stat-card .card-body {
        padding: 1.25rem 1rem;
    }
    
    .mobile-stack > * {
        margin-bottom: 0.5rem;
    }
    
    h1.h3 {
        font-size: 1.5rem;
    }
    
    .badge-modern {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
    
    .echeance-card .card-body {
        padding: 1rem;
    }
    
    /* Améliorer la lisibilité sur mobile */
    .alert {
        font-size: 0.9rem;
    }
}

/* Desktop optimizations (écrans 15") */
@media (min-width: 1200px) and (max-width: 1600px) {
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .table {
        font-size: 0.95rem;
    }
}

/* Animation pour le collapse */
.collapse {
    transition: height 0.35s ease;
}

.collapsing {
    transition: height 0.35s ease;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-2 fw-bold" style="color: #667eea;">Crédit #{{ $credit->id }}</h1>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $statutColors = [
                                'en_attente' => 'warning',
                                'approuve' => 'success',
                                'rejete' => 'danger',
                                'suspendu' => 'secondary'
                            ];
                            $etatColors = [
                                'actif' => 'success',
                                'clos' => 'secondary',
                                'suspendu' => 'warning'
                            ];
                        @endphp
                        <span class="badge badge-modern bg-{{ $statutColors[$credit->statut] ?? 'secondary' }}" style="color: #ffffff !important;">{{ ucfirst($credit->statut) }}</span>
                        <span class="badge badge-modern bg-{{ $etatColors[$credit->etat] ?? 'info' }}" style="color: #ffffff !important;">{{ ucfirst($credit->etat) }}</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#creditDetailsModal">
                        <i class="fas fa-info-circle"></i><span class="d-none d-md-inline ms-1">Détails complets</span>
                    </button>
                    <a href="{{ route('adherent.credits.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i><span class="d-none d-md-inline ms-1">Retour</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100 modern-card" style="background-color: #ffffff !important;">
                <div class="card-body text-center" style="background-color: #ffffff !important;">
                    <div class="mb-2" style="color: #667eea;">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="color: #10b981; font-size: clamp(0.9rem, 2vw, 1.25rem);">{{ number_format((float)$credit->montant_accorde, 0, ',', ' ') }}</h5>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">FCFA</small>
                    <small class="text-dark fw-medium" style="font-size: 0.75rem;">Montant accordé</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100 modern-card" style="background-color: #ffffff !important;">
                <div class="card-body text-center" style="background-color: #ffffff !important;">
                    <div class="mb-2" style="color: #3b82f6;">
                        <i class="fas fa-percent fa-2x"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="color: #3b82f6; font-size: clamp(0.9rem, 2vw, 1.25rem);">{{ (float)$credit->taux }}%</h5>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">&nbsp;</small>
                    <small class="text-dark fw-medium" style="font-size: 0.75rem;">Taux d'intérêt</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100 modern-card" style="background-color: #ffffff !important;">
                <div class="card-body text-center" style="background-color: #ffffff !important;">
                    <div class="mb-2" style="color: #f59e0b;">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="color: #f59e0b; font-size: clamp(0.9rem, 2vw, 1.25rem);">{{ $credit->duree }}</h5>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">mois</small>
                    <small class="text-dark fw-medium" style="font-size: 0.75rem;">{{ ucfirst($credit->periodicite) }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100 modern-card" style="background-color: #ffffff !important;">
                <div class="card-body text-center" style="background-color: #ffffff !important;">
                    <div class="mb-2" style="color: #6b7280;">
                        <i class="fas fa-list-ol fa-2x"></i>
                    </div>
                    @php
                        $totalEcheances = $credit->echeances->count();
                        $echeancesPayees = $credit->echeances->where('statut', 'payé')->count();
                    @endphp
                    <h5 class="mb-0 fw-bold" style="color: #10b981; font-size: clamp(0.9rem, 2vw, 1.25rem);">{{ $echeancesPayees }}/{{ $totalEcheances }}</h5>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">&nbsp;</small>
                    <small class="text-dark fw-medium" style="font-size: 0.75rem;">Échéances payées</small>
                    @if($totalEcheances > 0)
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar" style="width: {{ ($echeancesPayees/$totalEcheances)*100 }}%; background-color: #10b981;"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Échéancier -->
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-12">
            <div class="card modern-card" style="background-color: #ffffff !important;">
                <div class="card-header card-header-gradient text-white d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: #ffffff !important;">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar-check me-2"></i>Échéancier de remboursement
                    </h5>
                </div>
                <div>
                    <!-- Version desktop/tablet -->
                    <div class="d-none d-md-block" style="background-color: #ffffff !important;">
                        <div class="table-responsive" style="background-color: #ffffff !important;">
                            <table class="table table-hover align-middle mb-0" style="background-color: #ffffff !important;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 80px;">#</th>
                                        <th style="width: 130px;">Date d'échéance</th>
                                        <th class="text-end" style="width: 120px;">Montant attendu</th>
                                        <th class="text-end" style="width: 120px;">Montant payé</th>
                                        <th class="text-end" style="width: 100px;">Pénalité</th>
                                        <th class="text-center" style="width: 100px;">Statut</th>
                                        <th class="text-center" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($credit->echeances as $i => $e)
                                        @php
                                            $today = \Carbon\Carbon::now();
                                            $echeanceDate = \Carbon\Carbon::parse($e->date_echeance);
                                            $isOverdue = $today->gt($echeanceDate) && $e->statut !== 'payé';
                                            $isPaid = $e->statut === 'payé';
                                            
                                            $rowClass = $isPaid ? 'table-success' : ($isOverdue ? 'table-danger' : '');
                                            $badgeClass = $isPaid ? 'bg-success' : ($isOverdue ? 'bg-danger' : ($today->diffInDays($echeanceDate, false) <= 7 ? 'bg-warning' : 'bg-secondary'));
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="text-center fw-bold">
                                                <span class="badge bg-light text-dark">{{ $i+1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold">{{ $echeanceDate->format('d/m/Y') }}</span>
                                                    <small class="text-muted">{{ $echeanceDate->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-primary fs-6">{{ number_format((float)$e->montant_attendu, 0, ',', ' ') }}</span>
                                                <small class="text-secondary d-block">FCFA</small>
                                            </td>
                                            <td class="text-end">
                                                @if($e->montant_paye > 0)
                                                    <span class="fw-bold text-success fs-6">{{ number_format((float)$e->montant_paye, 0, ',', ' ') }}</span>
                                                    <small class="text-secondary d-block">FCFA</small>
                                                @else
                                                    <span class="text-secondary">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($e->penalite_appliquee > 0)
                                                    <span class="fw-bold text-danger fs-6">{{ number_format((float)$e->penalite_appliquee, 0, ',', ' ') }}</span>
                                                    <small class="text-secondary d-block">FCFA</small>
                                                @else
                                                    <span class="text-secondary">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($isPaid)
                                                    <span class="badge badge-modern" style="background-color: #10b981;"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                                @elseif($isOverdue)
                                                    <span class="badge badge-modern" style="background-color: #ef4444;"><i class="fas fa-exclamation-triangle me-1"></i>En retard</span>
                                                @elseif($today->diffInDays($echeanceDate, false) <= 7 && $today->diffInDays($echeanceDate, false) >= 0)
                                                    <span class="badge badge-modern" style="background-color: #f59e0b;"><i class="fas fa-clock me-1"></i>À venir</span>
                                                @else
                                                    <span class="badge badge-modern bg-secondary"><i class="fas fa-calendar me-1"></i>En attente</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$isPaid)
                                                    <button class="btn btn-outline-primary btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#paymentModal"
                                                            onclick="preparePayment('{{ $e->id }}', '{{ $e->montant_attendu }}', '{{ $e->penalite_appliquee ?? 0 }}', '{{ $echeanceDate->format('Y-m-d') }}')"
                                                            title="Effectuer un paiement">
                                                        <i class="fas fa-credit-card"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-success btn-sm" title="Paiement effectué">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-secondary">
                                                    <i class="fas fa-calendar-times fa-3x mb-3 text-primary" style="opacity: 0.5;"></i>
                                                    <h6 class="text-dark">Aucune échéance</h6>
                                                    <p class="mb-0 fw-medium">L'échéancier sera généré après approbation du crédit.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Version mobile -->
                    <div class="d-md-none">
                        <div class="p-3">
                            @forelse($credit->echeances as $i => $e)
                                @php
                                    $today = \Carbon\Carbon::now();
                                    $echeanceDate = \Carbon\Carbon::parse($e->date_echeance);
                                    $isOverdue = $today->gt($echeanceDate) && $e->statut !== 'payé';
                                    $isPaid = $e->statut === 'payé';
                                    
                                    $cardClass = $isPaid ? 'echeance-card paid' : ($isOverdue ? 'echeance-card overdue' : 'echeance-card pending');
                                @endphp
                                <div class="card {{ $cardClass }} mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">Échéance #{{ $i+1 }}</h6>
                                                <small class="text-secondary fw-medium">{{ $echeanceDate->format('d M Y') }} • {{ $echeanceDate->diffForHumans() }}</small>
                                            </div>
                                            <div>
                                                @if($isPaid)
                                                    <span class="badge badge-modern" style="background-color: #10b981;">Payé</span>
                                                @elseif($isOverdue)
                                                    <span class="badge badge-modern" style="background-color: #ef4444;">En retard</span>
                                                @else
                                                    <span class="badge badge-modern" style="background-color: #f59e0b; color: #ffffff;">À venir</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="text-secondary small fw-semibold">Attendu</div>
                                                    <div class="fw-bold text-primary fs-6">{{ number_format((float)$e->montant_attendu, 0, ',', ' ') }}</div>
                                                    <div class="text-secondary small">FCFA</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="text-secondary small fw-semibold">Payé</div>
                                                    <div class="fw-bold {{ $e->montant_paye > 0 ? 'text-success' : 'text-secondary' }} fs-6">{{ number_format((float)$e->montant_paye, 0, ',', ' ') }}</div>
                                                    <div class="text-secondary small">FCFA</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($e->penalite_appliquee > 0)
                                            <div class="mt-2">
                                                <div class="alert alert-warning py-2 mb-0">
                                                    <small><i class="fas fa-exclamation-triangle me-1"></i>Pénalité: {{ number_format((float)$e->penalite_appliquee, 0, ',', ' ') }} FCFA</small>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!$isPaid)
                                            <div class="mt-2">
                                                <button class="btn btn-primary btn-sm w-100"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentModal"
                                                        onclick="preparePayment('{{ $e->id }}', '{{ $e->montant_attendu }}', '{{ $e->penalite_appliquee ?? 0 }}', '{{ $echeanceDate->format('Y-m-d') }}')">
                                                    <i class="fas fa-credit-card me-1"></i>Effectuer le paiement
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">Aucune échéance</h6>
                                    <p class="text-muted mb-0">L'échéancier sera généré après approbation du crédit.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="row g-3 g-md-4 mb-3 mb-md-4">
        <div class="col-12">
            <div class="card modern-card" style="background-color: #ffffff !important;">
                <div class="card-header card-header-success-gradient text-white d-flex justify-content-between align-items-center py-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; color: #ffffff !important;">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-credit-card me-2"></i>Historique des paiements
                    </h5>
                    <span class="badge bg-light badge-modern" style="color: #10b981;">{{ $credit->paiements->count() }}</span>
                </div>
                <div class="card-body" style="background-color: #ffffff !important;">
                    @forelse($credit->paiements as $p)
                        <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded" style="background: linear-gradient(to right, #f0fdf4 0%, #f9fafb 100%); border-left: 4px solid #10b981;">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="me-3" style="color: #10b981;">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">{{ number_format((float)$p->montant, 0, ',', ' ') }} FCFA</h6>
                                    <small class="text-secondary fw-medium" style="font-size: 0.8rem;">
                                        {{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}
                                        @if($p->penalite > 0)
                                            • <span style="color: #ef4444;" class="fw-semibold">Pénalité: {{ number_format((float)$p->penalite, 0, ',', ' ') }} FCFA</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-md-row gap-2 align-items-end align-items-md-center">
                                @if($p->preuves && $p->preuves->count() > 0)
                                    <button class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Voir les preuves">
                                        <i class="fas fa-file-alt"></i> {{ $p->preuves->count() }}
                                    </button>
                                @endif
                                <span class="badge badge-modern" style="background-color: #10b981;">Validé</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-credit-card fa-3x text-primary mb-3" style="opacity: 0.5;"></i>
                            <h6 class="text-dark">Aucun paiement effectué</h6>
                            <p class="text-secondary fw-medium mb-0">Les paiements apparaîtront ici après validation.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Informations et contact -->
    <div class="row g-2 g-md-3 mb-3 mb-md-4">
        <div class="col-md-6">
            <div class="alert border-0 shadow-sm" style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%); border-left: 4px solid #3b82f6 !important;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-info-circle fa-lg me-3 mt-1" style="color: #3b82f6;"></i>
                    <div>
                        <h6 class="alert-heading mb-1 fw-bold" style="color: #1e40af;">Besoin d'aide ?</h6>
                        <p class="mb-0" style="font-size: 0.9rem; color: #1e3a8a;">Pour tout litige sur un paiement, contactez notre support client.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert border-0 shadow-sm" style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); border-left: 4px solid #f59e0b !important;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle fa-lg me-3 mt-1" style="color: #d97706;"></i>
                    <div>
                        <h6 class="alert-heading mb-1 fw-bold" style="color: #92400e;">Rappel important</h6>
                        <p class="mb-0" style="font-size: 0.9rem; color: #78350f;">Les paiements en retard peuvent entraîner des pénalités.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails complets du crédit -->
<div class="modal fade" id="creditDetailsModal" tabindex="-1" aria-labelledby="creditDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="creditDetailsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>Détails complets - Crédit #{{ $credit->id }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <h6 class="text-primary mb-3"><i class="fas fa-dollar-sign me-2"></i>Informations financières</h6>
                            <div class="mb-2">
                                <strong>Montant demandé:</strong>
                                <span class="float-end">{{ number_format((float)$credit->montant_demande, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="mb-2">
                                <strong>Montant accordé:</strong>
                                <span class="float-end text-success">{{ $credit->montant_accorde ? number_format((float)$credit->montant_accorde, 0, ',', ' ') . ' FCFA' : '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Taux d'intérêt:</strong>
                                <span class="float-end">{{ (float)$credit->taux }}%</span>
                            </div>
                            <div class="mb-2">
                                <strong>Frais d'adhésion:</strong>
                                <span class="float-end">{{ number_format((float)$credit->frais_adhesion, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="mb-0">
                                <strong>Frais de dossier:</strong>
                                <span class="float-end">{{ number_format((float)$credit->frais_dossier, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <h6 class="text-info mb-3"><i class="fas fa-calendar me-2"></i>Informations temporelles</h6>
                            <div class="mb-2">
                                <strong>Durée:</strong>
                                <span class="float-end">{{ $credit->duree }} {{ ucfirst($credit->periodicite) }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Date de demande:</strong>
                                <span class="float-end">{{ \Carbon\Carbon::parse($credit->date_demande)->format('d/m/Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Date début remboursement:</strong>
                                <span class="float-end">{{ $credit->date_debut_remboursement ? \Carbon\Carbon::parse($credit->date_debut_remboursement)->format('d/m/Y') : '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Type de crédit:</strong>
                                <span class="float-end">{{ ucfirst($credit->type_credit ?? 'Non spécifié') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($credit->description)
                    <div class="mt-3">
                        <div class="border rounded p-3">
                            <h6 class="text-warning mb-2"><i class="fas fa-comment me-2"></i>Description</h6>
                            <p class="mb-0">{{ $credit->description }}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentModalLabel">
                    <i class="fas fa-credit-card me-2"></i>Effectuer un paiement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" action="{{ route('adherent.credits.submit-payment', $credit) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle fa-lg me-3"></i>
                        <div>
                            <strong>Note :</strong> Ce paiement sera envoyé pour validation par un agent. Vous recevrez une notification une fois traité.
                        </div>
                    </div>

                    <input type="hidden" id="echeance_id" name="echeance_id" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_paiement" class="form-label">Date de paiement <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_paiement" name="date_paiement" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant à payer <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="montant" name="montant" 
                                           step="0.01" min="1" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                                <div class="form-text" id="montant-info"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mode" class="form-label">Mode de paiement</label>
                                <select class="form-select" id="mode" name="mode">
                                    <option value="">Sélectionner...</option>
                                    <option value="especes">Espèces</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="virement">Virement bancaire</option>
                                    <option value="cheque">Chèque</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Référence/Numéro</label>
                                <input type="text" class="form-control" id="reference" name="reference" 
                                       placeholder="Ex: numéro de transaction">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="preuves" class="form-label">Preuves de paiement</label>
                        <input type="file" class="form-control" id="preuves" name="preuves[]" 
                               accept=".jpg,.jpeg,.png,.pdf" multiple>
                        <div class="form-text">Formats acceptés: JPG, PNG, PDF (max 5MB par fichier)</div>
                    </div>
                    
                    <div id="payment-summary" class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Résumé du paiement</h6>
                            <div class="d-flex justify-content-between">
                                <span>Montant de l'échéance :</span>
                                <span id="echeance-amount">-</span>
                            </div>
                            <div class="d-flex justify-content-between" id="penalite-row" style="display: none;">
                                <span>Pénalité :</span>
                                <span id="penalite-amount" class="text-danger">-</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total à payer :</span>
                                <span id="total-amount">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonction globale pour préparer les données du paiement
function preparePayment(echeanceId, montant, penalite, dateEcheance) {
    montant = parseFloat(montant);
    penalite = parseFloat(penalite);
    
    console.log('Préparation paiement:', { echeanceId, montant, penalite });
    
    // Remplir le modal avec les données
    document.getElementById('echeance_id').value = echeanceId;
    document.getElementById('montant').value = (montant + penalite).toFixed(2);
    
    // Mettre à jour le résumé
    const echeanceAmountSpan = document.getElementById('echeance-amount');
    const penaliteAmountSpan = document.getElementById('penalite-amount');
    const penaliteRow = document.getElementById('penalite-row');
    const totalAmountSpan = document.getElementById('total-amount');
    
    echeanceAmountSpan.textContent = new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
    
    if (penalite > 0) {
        penaliteAmountSpan.textContent = new Intl.NumberFormat('fr-FR').format(penalite) + ' FCFA';
        penaliteRow.style.display = 'flex';
    } else {
        penaliteRow.style.display = 'none';
    }
    
    totalAmountSpan.textContent = new Intl.NumberFormat('fr-FR').format(montant + penalite) + ' FCFA';
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    
    // Gestion de la soumission du formulaire
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Afficher l'état de chargement
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();
                
                // Afficher un message de succès
                showAlert('success', data.message);
                
                // Recharger la page après 2 secondes
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Une erreur est survenue lors de l\'envoi du paiement.');
        })
        .finally(() => {
            // Restaurer le bouton
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="${iconClass} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        // Auto-fermer après 5 secondes
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
    
    // Mise à jour du montant quand l'utilisateur change la valeur
    document.getElementById('montant').addEventListener('input', function() {
        const montant = parseFloat(this.value) || 0;
        const info = document.getElementById('montant-info');
        
        if (montant > 0) {
            info.textContent = `Montant saisi: ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA`;
            info.className = 'form-text text-success';
        } else {
            info.textContent = '';
        }
    });
});
</script>
@endpush
