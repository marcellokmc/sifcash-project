@extends('backoffice.layouts.app')

@section('title', 'Adhésion #'.$adhesion->id)
@section('page-title', 'Détails de l\'Adhésion')

@push('styles')
<style>
    .stat-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #ffffff !important;
    }
    .stat-card .card-body {
        background-color: #ffffff !important;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529 !important;
    }
    .paiement-table {
        font-size: 0.95rem;
    }
    .paiement-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }
    .action-icon {
        cursor: pointer;
        transition: all 0.2s;
    }
    .action-icon:hover {
        transform: scale(1.2);
    }
    .modal-content {
        border-radius: 12px;
        border: none;
    }
    .btn-action {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
@include('components.backoffice.alerts')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            <i class="fas fa-file-contract text-primary me-2"></i>Adhésion #{{ $adhesion->id }}
        </h1>
        <div class="text-muted">
            <i class="fas fa-user me-1"></i>{{ $adhesion->adherent->nom ?? 'N/A' }} {{ $adhesion->adherent->prenom ?? '' }}
            @if($adhesion->adherent->telephone)
                | <i class="fas fa-phone me-1"></i>{{ $adhesion->adherent->telephone }}
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.adhesions.index') }}" class="btn btn-secondary btn-action">
            <i class="fas fa-arrow-left me-1"></i>Retour
        </a>
        <a href="{{ route('admin.adhesions.download', $adhesion) }}" class="btn btn-info btn-action">
            <i class="fas fa-download me-1"></i>Télécharger
        </a>
        <a href="{{ route('admin.adhesions.download-with-payments', $adhesion) }}" class="btn btn-primary btn-action">
            <i class="fas fa-file-pdf me-1"></i>Avec paiements
        </a>
        @can('update', $adhesion)
            <a href="{{ route('admin.adhesions.edit', $adhesion) }}" class="btn btn-warning btn-action">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
        @endcan
    </div>
</div>

{{-- Statistiques de l'adhésion --}}
@php
    $totalPaiements = $adhesion->paiements->where('statut', 'validé')->sum('montant');
    $paiementsEnAttente = $adhesion->paiements->where('statut', 'en_attente')->count();
    $retraitAnticipe = $adhesion->paiements
        ->where('statut', 'validé')
        ->where('categorie', '!=', 'ouverture')
        ->sum(function($paiement) {
            return $paiement->details
                ->whereNotIn('type_frais', ['interet', 'dossier', 'entretien'])
                ->sum('montant');
        });
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card" style="background-color: #fff !important;">
            <div class="card-body" style="background-color: #fff !important;">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <div class="info-label">Montant souscrit</div>
                        <div class="info-value">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }}</div>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="background-color: #fff !important;">
            <div class="card-body" style="background-color: #fff !important;">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="info-label">Total paiements</div>
                        <div class="info-value">{{ number_format($totalPaiements, 0, ',', ' ') }}</div>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="background-color: #fff !important;">
            <div class="card-body" style="background-color: #fff !important;">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="info-label">En attente</div>
                        <div class="info-value">{{ $paiementsEnAttente }}</div>
                        <small class="text-muted">paiement(s)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card" style="background-color: #fff !important;">
            <div class="card-body" style="background-color: #fff !important;">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <div class="info-label">Retrait anticipé</div>
                        <div class="info-value">{{ number_format($retraitAnticipe, 0, ',', ' ') }}</div>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations principales -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Informations de l'Adhésion
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Plan d'épargne</h6>
                        <p class="mb-0">
                            <strong>{{ $adhesion->plan->nom ?? 'Plan supprimé' }}</strong>
                            @if($adhesion->plan)
                                <br><small class="text-muted">
                                    Taux: {{ $adhesion->plan->taux_interet }}% - {{ ucfirst($adhesion->plan->periodicite) }}
                                </small>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Montant souscrit</h6>
                        <p class="mb-0">
                            <strong class="text-primary h5">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</strong>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de début</h6>
                        <p class="mb-0">
                            {{ $adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'Non définie' }}
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de fin</h6>
                        <p class="mb-0">
                            {{ $adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'Non définie' }}
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Statut</h6>
                        <p class="mb-0">
                            @php
                                $statutClass = match($adhesion->statut) {
                                    'actif' => 'bg-success',
                                    'en_attente_activation' => 'bg-warning text-dark',
                                    'suspendue' => 'bg-warning text-dark', 
                                    'terminee' => 'bg-secondary',
                                    'annulee' => 'bg-danger',
                                    default => 'bg-info'
                                };
                            @endphp
                            <span class="badge {{ $statutClass }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Renouvelable</h6>
                        <p class="mb-0">
                            @if($adhesion->renouvelable)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-times me-1"></i>Non
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de création</h6>
                        <p class="mb-0">{{ $adhesion->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Dernière mise à jour</h6>
                        <p class="mb-0">{{ $adhesion->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                
                @if($adhesion->notes)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">Notes</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-sticky-note me-2"></i>{{ $adhesion->notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Gestion des paiements -->
        <div class="card mt-4 stat-card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-money-check-alt me-2 text-success"></i>Gestion des paiements
                </h5>
            </div>
            <div class="card-body p-0">
                @if($adhesion->paiements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover paiement-table mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Catégorie</th>
                                    <th>Mode</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($adhesion->paiements->sortByDesc('date_soumission') as $paiement)
                                <tr>
                                    <td><span class="badge bg-secondary">#{{ $paiement->id }}</span></td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $paiement->date_soumission ? $paiement->date_soumission->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </td>
                                    <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $paiement->categorie === 'ouverture' ? 'info' : 'primary' }}">
                                            {{ ucfirst($paiement->categorie) }}
                                        </span>
                                    </td>
                                    <td><small>{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</small></td>
                                    <td>
                                        @if($paiement->statut === 'validé')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Validé
                                            </span>
                                        @elseif($paiement->statut === 'rejeté')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejeté
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.paiements.show', $paiement) }}" 
                                               class="btn btn-outline-info" 
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($paiement->statut === 'en_attente')
                                                @can('validate', $paiement)
                                                    <button type="button" 
                                                            class="btn btn-outline-success" 
                                                            onclick="approvePaiement({{ $paiement->id }})"
                                                            title="Approuver">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            onclick="rejectPaiement({{ $paiement->id }})"
                                                            title="Rejeter">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if($paiement->motif_rejet)
                                <tr class="bg-light">
                                    <td colspan="7">
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>Motif de rejet:</strong> {{ $paiement->motif_rejet }}
                                        </small>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Aucun paiement enregistré pour cette adhésion</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Sidebar avec actions et infos adhérent -->
    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                @if($adhesion->statut === 'en_attente_activation')
                    @can('activate', $adhesion)
                        <form method="POST" action="{{ route('admin.adhesions.activate', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Activer cette adhésion ?')">
                                <i class="fas fa-play me-1"></i>Activer l'adhésion
                            </button>
                        </form>
                    @endcan
                @elseif($adhesion->statut === 'actif')
                    @can('suspend', $adhesion)
                        <button type="button" class="btn btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#suspendModal">
                            <i class="fas fa-pause me-1"></i>Suspendre
                        </button>
                    @endcan
                    
                    @can('close', $adhesion)
                        <button type="button" class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#closeModal">
                            <i class="fas fa-stop me-1"></i>Clôturer
                        </button>
                    @endcan
                @elseif($adhesion->statut === 'suspendue')
                    @can('suspend', $adhesion)
                        <form method="POST" action="{{ route('admin.adhesions.resume', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Reprendre cette adhésion ?')">
                                <i class="fas fa-play me-1"></i>Reprendre
                            </button>
                        </form>
                    @endcan
                @endif
                
                @if($adhesion->renouvelable && in_array($adhesion->statut, ['actif', 'terminee']))
                    @can('renew', $adhesion)
                        <form method="POST" action="{{ route('admin.adhesions.renew', $adhesion) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Renouveler cette adhésion ?')">
                                <i class="fas fa-redo me-1"></i>Renouveler
                            </button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>
        
        <!-- Informations adhérent -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Informations Adhérent
                </h6>
            </div>
            <div class="card-body">
                @if($adhesion->adherent)
                <div class="text-center mb-3">
                    <img src="{{ $adhesion->adherent->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($adhesion->adherent->nom.' '.$adhesion->adherent->prenom).'&background=random' }}" 
                         class="rounded-circle mb-2" 
                         width="80" height="80"
                         alt="Photo de l'adhérent">
                    <h6 class="mb-0">{{ $adhesion->adherent->nom }} {{ $adhesion->adherent->prenom }}</h6>
                    <small class="text-muted">{{ $adhesion->adherent->user->email ?? '' }}</small>
                </div>
                
                <div class="mb-2">
                    <strong>Matricule:</strong> {{ $adhesion->adherent->matricule ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Téléphone:</strong> {{ $adhesion->adherent->telephone ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Statut:</strong> 
                    <span class="badge bg-{{ $adhesion->adherent->statut_validation === 'valide' ? 'success' : 'warning' }}">
                        {{ ucfirst($adhesion->adherent->statut_validation ?? 'En cours') }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Membre depuis:</strong> {{ $adhesion->adherent->created_at->format('m/Y') }}
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('admin.adherents.show', $adhesion->adherent) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-user me-1"></i>Voir le profil complet
                    </a>
                </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Adhérent non trouvé
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal pour suspendre l'adhésion --}}
<div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.adhesions.suspend', $adhesion) }}">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="suspendModalLabel">
                        <i class="fas fa-pause me-2"></i>Suspendre l'adhésion
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motif_suspend" class="form-label">Motif de suspension <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motif_suspend" name="motif" rows="4" required 
                                  placeholder="Veuillez indiquer la raison de la suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-pause me-1"></i>Suspendre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour clôturer l'adhésion --}}
<div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.adhesions.close', $adhesion) }}">
                @csrf
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="closeModalLabel">
                        <i class="fas fa-stop me-2"></i>Clôturer l'adhésion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> La clôture de l'adhésion est irréversible.
                    </div>
                    
                    <div class="mb-3">
                        <label for="type_cloture" class="form-label">Type de clôture <span class="text-danger">*</span></label>
                        <select class="form-select" id="type_cloture" name="type_cloture" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="retrait_anticipé">Clôture pour retrait anticipé</option>
                            <option value="arrivee_terme">Clôture pour arrivée à terme</option>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Choisissez "Retrait anticipé" si l'adhérent retire avant la fin, "Arrivée à terme" si le plan arrive à échéance.
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motif_close" class="form-label">Motif de clôture <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motif_close" name="motif" rows="4" required
                                  placeholder="Indiquez la raison de la clôture..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-stop me-1"></i>Clôturer définitivement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour approuver un paiement --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="approveModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Approuver le paiement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Confirmez-vous l'approbation de ce paiement ?</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Le paiement sera marqué comme validé et pris en compte dans le solde de l'adhésion.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Approuver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour rejeter un paiement --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i class="fas fa-times-circle me-2"></i>Rejeter le paiement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motif_rejet" class="form-label">Motif de rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motif_rejet" name="motif_rejet" rows="4" required 
                                  placeholder="Veuillez indiquer la raison du rejet..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function approvePaiement(paiementId) {
        const form = document.getElementById('approveForm');
        form.action = `/admin/paiements/${paiementId}/validate`;
        const modal = new bootstrap.Modal(document.getElementById('approveModal'));
        modal.show();
    }

    function rejectPaiement(paiementId) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/paiements/${paiementId}/reject`;
        document.getElementById('motif_rejet').value = '';
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>
@endpush

@endsection
