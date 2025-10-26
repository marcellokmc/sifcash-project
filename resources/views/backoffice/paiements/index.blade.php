@extends('backoffice.layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Paiements</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Paiements</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Liste des Paiements</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterPayments('all')">Tous</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterPayments('soumis')">En attente</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterPayments('validé')">Validés</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterPayments('rejeté')">Rejetés</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search-input" placeholder="Nom, email, référence...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie-filter">
                                    <option value="">Toutes</option>
                                    <option value="ouverture">Frais d'ouverture</option>
                                    <option value="cotisation">Cotisation</option>
                                    <option value="credit">Paiement crédit</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Mode</label>
                                <select class="form-select" id="mode-filter">
                                    <option value="">Tous</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="virement">Virement</option>
                                    <option value="cheque">Chèque</option>
                                    <option value="especes">Espèces</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" id="date-debut">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Date fin</label>
                                <input type="date" class="form-control" id="date-fin">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary d-block" onclick="applyFilters()">
                                    <i class="mdi mdi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="paiements-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Adhérent</th>
                                    <th>Montant</th>
                                    <th>Catégorie</th>
                                    <th>Mode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paiements as $paiement)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $paiement->date_soumission->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $paiement->date_soumission->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle me-2">
                                                <span class="avatar-title text-white">{{ substr($paiement->adherent->prenom, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $paiement->adherent->nom_complet }}</h6>
                                                <small class="text-muted">{{ $paiement->adherent->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($paiement->categorie) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</span>
                                    </td>
                                    <td>
                                        @switch($paiement->statut)
                                            @case('validé')
                                                <span class="badge bg-success">Validé</span>
                                                @break
                                            @case('soumis')
                                                <span class="badge bg-warning">En attente</span>
                                                @break
                                            @case('rejeté')
                                                <span class="badge bg-danger">Rejeté</span>
                                                @break
                                            @case('brouillon')
                                                <span class="badge bg-secondary">Brouillon</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($paiement->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.paiements.show', $paiement) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($paiement->statut === 'soumis')
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="validatePayment({{ $paiement->id }})" title="Valider">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="rejectPayment({{ $paiement->id }})" title="Rejeter">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            @endif
                                            @if($paiement->preuve)
                                            <a href="{{ route('admin.paiements.download', $paiement) }}" class="btn btn-sm btn-outline-info" title="Télécharger">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline fs-1"></i>
                                            <p>Aucun paiement trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($paiements->hasPages())
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Affichage de {{ $paiements->firstItem() }} à {{ $paiements->lastItem() }} sur {{ $paiements->total() }} résultats
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $paiements->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de validation -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valider le Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="validate-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Êtes-vous sûr de vouloir valider ce paiement ?
                    </div>
                    <div class="mb-3">
                        <label for="validation-notes" class="form-label">Notes (Optionnel)</label>
                        <textarea class="form-control" id="validation-notes" name="notes" rows="3"
                                  placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reject-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle"></i> Êtes-vous sûr de vouloir rejeter ce paiement ?
                    </div>
                    <div class="mb-3">
                        <label for="reject-motif" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('motif_rejet') is-invalid @enderror"
                                  id="reject-motif" name="motif_rejet" rows="3"
                                  placeholder="Expliquez pourquoi ce paiement est rejeté..." required></textarea>
                        @error('motif_rejet')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterPayments(status) {
    // Implementation for filtering payments by status
    console.log('Filtering by status:', status);
    // Add AJAX call to filter payments
}

function applyFilters() {
    // Implementation for applying advanced filters
    console.log('Applying filters');
    // Add AJAX call to apply filters
}

function validatePayment(paymentId) {
    const form = document.getElementById('validate-form');
    form.action = `/admin/paiements/${paymentId}/validate`;

    const modal = new bootstrap.Modal(document.getElementById('validateModal'));
    modal.show();
}

function rejectPayment(paymentId) {
    const form = document.getElementById('reject-form');
    form.action = `/admin/paiements/${paymentId}/reject`;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

// Auto-refresh every 30 seconds for pending payments
setInterval(function() {
    if (document.querySelector('.badge.bg-warning')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
