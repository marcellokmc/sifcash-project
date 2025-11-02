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
                        <i class="fas fa-file-signature fa-2x"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="color: #10b981; font-size: clamp(0.9rem, 2vw, 1.25rem);">{{ $credit->contract_path ? 'Contrat prêt' : '—' }}</h5>
                    <small class="text-secondary d-block mt-1" style="font-size: 0.7rem;">&nbsp;</small>
                    <small class="text-dark fw-medium" style="font-size: 0.75rem;">Contrat</small>
                </div>
            </div>
        </div>
    </div>


        @if(!empty($paymentsEnabled) && $paymentsEnabled)
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
        @endif
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

@endsection

