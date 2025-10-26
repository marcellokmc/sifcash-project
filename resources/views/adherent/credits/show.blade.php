@extends('layouts.adherent-modern')

@section('title', 'Crédit #'.$credit->id)

@push('styles')
<style>
.stat-card {
    transition: transform 0.2s;
}
.stat-card:hover {
    transform: translateY(-2px);
}
.progress-thin {
    height: 4px;
}
.echeance-card {
    border-left: 4px solid #dee2e6;
    transition: all 0.3s ease;
}
.echeance-card.paid {
    border-left-color: #28a745;
    background-color: #f8f9fa;
}
.echeance-card.overdue {
    border-left-color: #dc3545;
}
.echeance-card.pending {
    border-left-color: #ffc107;
}
@media (max-width: 768px) {
    .mobile-stack > * {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div class="mb-2 mb-md-0">
                    <h1 class="h3 mb-1 text-primary">Crédit #{{ $credit->id }}</h1>
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
                        <span class="badge bg-{{ $statutColors[$credit->statut] ?? 'secondary' }} fs-6">{{ ucfirst($credit->statut) }}</span>
                        <span class="badge bg-{{ $etatColors[$credit->etat] ?? 'info' }} fs-6">{{ ucfirst($credit->etat) }}</span>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#creditDetailsModal">
                        <i class="fas fa-info-circle"></i> Détails complets
                    </button>
                    <a href="{{ route('adherent.credits.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                    <h5 class="text-success mb-0 fw-bold">{{ number_format((float)$credit->montant_accorde, 0, ',', ' ') }} FCFA</h5>
                    <small class="text-dark fw-medium">Montant accordé</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-percent fa-2x"></i>
                    </div>
                    <h5 class="text-info mb-0 fw-bold">{{ (float)$credit->taux }}%</h5>
                    <small class="text-dark fw-medium">Taux d'intérêt</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <h5 class="text-warning mb-0 fw-bold">{{ $credit->duree }}</h5>
                    <small class="text-dark fw-medium">{{ ucfirst($credit->periodicite) }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-secondary mb-2">
                        <i class="fas fa-list-ol fa-2x"></i>
                    </div>
                    @php
                        $totalEcheances = $credit->echeances->count();
                        $echeancesPayees = $credit->echeances->where('statut', 'payé')->count();
                    @endphp
                    <h5 class="text-success mb-0 fw-bold">{{ $echeancesPayees }}/{{ $totalEcheances }}</h5>
                    <small class="text-dark fw-medium">Échéances payées</small>
                    @if($totalEcheances > 0)
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-success" style="width: {{ ($echeancesPayees/$totalEcheances)*100 }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Échéancier -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Échéancier de remboursement
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#echeancierDetails" aria-expanded="true">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse show" id="echeancierDetails">
                    <!-- Version desktop/tablet -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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
                                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Payé</span>
                                                @elseif($isOverdue)
                                                    <span class="badge bg-danger"><i class="fas fa-exclamation-triangle me-1"></i>En retard</span>
                                                @elseif($today->diffInDays($echeanceDate, false) <= 7 && $today->diffInDays($echeanceDate, false) >= 0)
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>À venir</span>
                                                @else
                                                    <span class="badge bg-secondary"><i class="fas fa-calendar me-1"></i>En attente</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$isPaid)
                                                    <button class="btn btn-outline-primary btn-sm payment-btn" 
                                                            data-bs-toggle="tooltip" title="Effectuer un paiement"
                                                            data-echeance-id="{{ $e->id }}"
                                                            data-montant="{{ $e->montant_attendu }}"
                                                            data-date="{{ $echeanceDate->format('Y-m-d') }}"
                                                            data-penalite="{{ $e->penalite_appliquee ?? 0 }}">
                                                        <i class="fas fa-credit-card"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="tooltip" title="Paiement effectué">
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
                                                    <span class="badge bg-success">Payé</span>
                                                @elseif($isOverdue)
                                                    <span class="badge bg-danger">En retard</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">À venir</span>
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
                                                <button class="btn btn-primary btn-sm w-100 payment-btn"
                                                        data-echeance-id="{{ $e->id }}"
                                                        data-montant="{{ $e->montant_attendu }}"
                                                        data-date="{{ $echeanceDate->format('Y-m-d') }}"
                                                        data-penalite="{{ $e->penalite_appliquee ?? 0 }}">
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
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Historique des paiements
                    </h5>
                    <span class="badge bg-light text-success">{{ $credit->paiements->count() }} paiement(s)</span>
                </div>
                <div class="card-body">
                    @forelse($credit->paiements as $p)
                        <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <div class="text-success me-3">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div>
                                                <h6 class="mb-1 fw-bold text-dark">{{ number_format((float)$p->montant, 0, ',', ' ') }} FCFA</h6>
                                                    <small class="text-secondary fw-medium">
                                                        {{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}
                                                        @if($p->penalite > 0)
                                                            • <span class="text-danger fw-semibold">Pénalité: {{ number_format((float)$p->penalite, 0, ',', ' ') }} FCFA</span>
                                                        @endif
                                                    </small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                @if($p->preuves && $p->preuves->count() > 0)
                                    <button class="btn btn-outline-info btn-sm" data-bs-toggle="tooltip" title="Voir les preuves">
                                        <i class="fas fa-file-alt"></i> {{ $p->preuves->count() }}
                                    </button>
                                @endif
                                <span class="badge bg-success">Validé</span>
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
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Besoin d'aide ?</h6>
                        <p class="mb-0">Pour tout litige sur un paiement, contactez notre support client.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-warning border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Rappel important</h6>
                        <p class="mb-0">Les paiements en retard peuvent entraîner des pénalités.</p>
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
// Initialiser les tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gestion des boutons de paiement
    document.querySelectorAll('.payment-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const echeanceId = this.dataset.echeanceId;
            const montant = parseFloat(this.dataset.montant);
            const penalite = parseFloat(this.dataset.penalite || 0);
            const dateEcheance = this.dataset.date;
            
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
            
            // Afficher le modal
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        });
    });
    
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
