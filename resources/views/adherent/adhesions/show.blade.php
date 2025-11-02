{{-- resources/views/adherent/adhesions/show.blade.php --}}
@extends('layouts.adherent-modern')

@section('content')
<div class="container-fluid" style="max-width: 1400px; margin: 0 auto;">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-link text-decoration-none mb-2">
                <i class="fas fa-arrow-left me-1"></i>Retour à mes adhésions
            </a>
            <h1 class="h3 mb-1">
                <i class="fas fa-piggy-bank text-primary me-2"></i>{{ $adhesion->plan->nom ?? 'Plan d\'épargne' }}
            </h1>
            <p class="text-muted mb-0">Adhésion #{{ $adhesion->id }} - Souscrite le {{ $adhesion->created_at->format('d/m/Y') }}</p>
        </div>
        <div>
            @php
                $statutClass = match($adhesion->statut) {
                    'actif' => 'success',
                    'en_attente_activation' => 'warning',
                    'suspendu' => 'warning',
                    'clos' => 'secondary',
                    default => 'info'
                };
            @endphp
            <span class="badge bg-{{ $statutClass }} px-3 py-2" style="font-size: 1rem;">
                {{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}
            </span>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75" style="font-size: 0.85rem;">Montant souscrit</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }}</h3>
                            <small class="opacity-75">FCFA</small>
                        </div>
                        <div>
                            <i class="fas fa-hand-holding-usd fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75" style="font-size: 0.85rem;">Total versé</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($totalPaiements, 0, ',', ' ') }}</h3>
                            <small class="opacity-75">FCFA</small>
                        </div>
                        <div>
                            <i class="fas fa-coins fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75" style="font-size: 0.85rem;">Retrait disponible</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($retraitAnticipe, 0, ',', ' ') }}</h3>
                            <small class="opacity-75">FCFA</small>
                        </div>
                        <div>
                            <i class="fas fa-wallet fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75" style="font-size: 0.85rem;">Intérêts accumulés</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($interetsAccumules, 0, ',', ' ') }}</h3>
                            <small class="opacity-75">FCFA</small>
                        </div>
                        <div>
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Details --}}
        <div class="col-lg-8">
            {{-- Informations du plan --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>Informations du plan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-percentage me-1"></i>Taux d'intérêt</small>
                                <h6 class="mb-0 text-primary fw-bold">{{ $adhesion->plan->taux_interet ?? 'N/A' }}% / {{ ucfirst($adhesion->plan->periodicite ?? 'an') }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-clock me-1"></i>Durée minimale</small>
                                <h6 class="mb-0 fw-bold">{{ $adhesion->plan->duree_min ?? 'N/A' }} mois</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-calendar-alt me-1"></i>Date de début</small>
                                <h6 class="mb-0 fw-bold">{{ optional($adhesion->date_debut)->format('d/m/Y') ?? 'N/A' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-calendar-check me-1"></i>Date de fin</small>
                                <h6 class="mb-0 fw-bold">{{ optional($adhesion->date_fin)->format('d/m/Y') ?? 'Non définie' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-sync me-1"></i>Renouvellement</small>
                                <h6 class="mb-0 fw-bold">
                                    @if($adhesion->renouvelable)
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Automatique</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Non</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1"><i class="fas fa-hourglass-half me-1"></i>Paiements en attente</small>
                                <h6 class="mb-0 fw-bold">
                                    @if($paiementsEnAttente > 0)
                                        <span class="badge bg-warning text-dark">{{ $paiementsEnAttente }}</span>
                                    @else
                                        <span class="text-success">Aucun</span>
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historique des paiements --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Historique des paiements
                        </h5>
                        <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Nouveau paiement
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($adhesion->paiements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Catégorie</th>
                                        <th>Mode</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($adhesion->paiements->sortByDesc('date_soumission') as $paiement)
                                        <tr>
                                            <td>{{ $paiement->date_soumission->format('d/m/Y') }}</td>
                                            <td><strong class="text-primary">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                                            <td><span class="badge bg-info">{{ ucfirst($paiement->categorie) }}</span></td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match($paiement->statut) {
                                                        'validé' => 'success',
                                                        'en_attente' => 'warning',
                                                        'rejeté' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($paiement->statut) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('adherent.paiements.show', $paiement) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun paiement enregistré pour cette adhésion.</p>
                            <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Effectuer un paiement
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Renouvellements --}}
            @if($adhesion->renouvellements && $adhesion->renouvellements->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-redo text-primary me-2"></i>Historique des renouvellements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($adhesion->renouvellements as $renouvellement)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                            {{ $renouvellement->date_renouvellement->format('d/m/Y') }}
                                        </h6>
                                        <p class="mb-0 text-muted small">Montant: {{ number_format($renouvellement->montant_souscrit, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <span class="badge bg-{{ $renouvellement->statut === 'complete' ? 'success' : 'warning' }}">
                                        {{ ucfirst($renouvellement->statut) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column: Actions & Info --}}
        <div class="col-lg-4">
            {{-- Actions rapides --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>Actions rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Effectuer un paiement
                        </a>
                        @if($retraitAnticipe > 0)
                            <a href="{{ route('adherent.retraits.create') }}" class="btn btn-success">
                                <i class="fas fa-hand-holding-usd me-2"></i>Demander un retrait
                            </a>
                        @endif
                        <a href="{{ route('admin.adhesions.download', $adhesion) }}" class="btn btn-info">
                            <i class="fas fa-download me-2"></i>Télécharger les détails
                        </a>
                        <a href="{{ route('admin.adhesions.download-with-payments', $adhesion) }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-pdf me-2"></i>Télécharger avec paiements
                        </a>
                    </div>
                </div>
            </div>

            {{-- Dernier paiement --}}
            @if($dernierPaiement)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt text-success me-2"></i>Dernier paiement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-success mb-2">{{ number_format($dernierPaiement->montant, 0, ',', ' ') }} FCFA</h3>
                        <p class="text-muted mb-0">{{ $dernierPaiement->date_soumission->format('d/m/Y') }}</p>
                        <small class="text-muted">via {{ ucfirst(str_replace('_', ' ', $dernierPaiement->mode_paiement)) }}</small>
                    </div>
                </div>
            </div>
            @endif

            {{-- Description du plan --}}
            @if($adhesion->plan && $adhesion->plan->description)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>À propos du plan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted" style="font-size: 0.9rem;">{{ $adhesion->plan->description }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
