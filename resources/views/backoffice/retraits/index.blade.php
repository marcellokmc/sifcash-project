@extends('backoffice.layouts.app')

@section('title', 'Gestion des Demandes de Retrait')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Demandes de Retrait</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Demandes de Retrait</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Liste des Demandes</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterRetraits('all')">Tous</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterRetraits('en_attente')">En attente</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterRetraits('validé')">Validés</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterRetraits('rejeté')">Rejetés</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search-input" placeholder="Nom, email, motif...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="type-filter">
                                    <option value="">Tous</option>
                                    <option value="partiel">Partiel</option>
                                    <option value="total">Total</option>
                                    <option value="urgence">Urgence</option>
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
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="retraits-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Adhérent</th>
                                    <th>Montant</th>
                                    <th>Type</th>
                                    <th>Mode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($retraits as $retrait)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $retrait->date_demande->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $retrait->date_demande->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle me-2">
                                                <span class="avatar-title text-white">{{ substr($retrait->adherent->prenom, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $retrait->adherent->nom_complet }}</h6>
                                                <small class="text-muted">{{ $retrait->adherent->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-danger">{{ number_format($retrait->montant_demande, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($retrait->type_retrait) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $retrait->mode_retrait)) }}</span>
                                    </td>
                                    <td>
                                        @switch($retrait->statut)
                                            @case('validé')
                                                <span class="badge bg-success">Validé</span>
                                                @break
                                            @case('en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                                @break
                                            @case('rejeté')
                                                <span class="badge bg-danger">Rejeté</span>
                                                @break
                                            @case('traité')
                                                <span class="badge bg-primary">Traité</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($retrait->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.retraits.show', $retrait) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($retrait->statut === 'en_attente')
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="validateRetrait({{ $retrait->id }})" title="Valider">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="rejectRetrait({{ $retrait->id }})" title="Rejeter">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            @endif
                                            @if($retrait->statut === 'validé')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="processRetrait({{ $retrait->id }})" title="Traiter">
                                                <i class="mdi mdi-cash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline fs-1"></i>
                                            <p>Aucune demande de retrait trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($retraits->hasPages())
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Affichage de {{ $retraits->firstItem() }} à {{ $retraits->lastItem() }} sur {{ $retraits->total() }} résultats
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                {{ $retraits->links() }}
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
                <h5 class="modal-title">Valider la Demande de Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="validate-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Êtes-vous sûr de vouloir valider cette demande de retrait ?
                    </div>
                    <div class="mb-3">
                        <label for="validation-notes" class="form-label">Notes (Optionnel)</label>
                        <textarea class="form-control" id="validation-notes" name="notes" rows="3"
                                  placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider la Demande</button>
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
                <h5 class="modal-title">Rejeter la Demande de Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reject-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle"></i> Êtes-vous sûr de vouloir rejeter cette demande de retrait ?
                    </div>
                    <div class="mb-3">
                        <label for="reject-motif" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('motif_rejet') is-invalid @enderror"
                                  id="reject-motif" name="motif_rejet" rows="3"
                                  placeholder="Expliquez pourquoi cette demande est rejetée..." required></textarea>
                        @error('motif_rejet')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter la Demande</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de traitement -->
<div class="modal fade" id="processModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Traiter la Demande de Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="process-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i> Marquer cette demande comme traitée ?
                    </div>
                    <div class="mb-3">
                        <label for="process-notes" class="form-label">Notes de traitement</label>
                        <textarea class="form-control" id="process-notes" name="notes" rows="3"
                                  placeholder="Détails du traitement effectué..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Marquer comme Traité</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterRetraits(status) {
    // Implementation for filtering retraits by status
    console.log('Filtering by status:', status);
    // Add AJAX call to filter retraits
}

function applyFilters() {
    // Implementation for applying advanced filters
    console.log('Applying filters');
    // Add AJAX call to apply filters
}

function validateRetrait(retraitId) {
    const form = document.getElementById('validate-form');
    form.action = `/admin/retraits/${retraitId}/validate`;

    const modal = new bootstrap.Modal(document.getElementById('validateModal'));
    modal.show();
}

function rejectRetrait(retraitId) {
    const form = document.getElementById('reject-form');
    form.action = `/admin/retraits/${retraitId}/reject`;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}

function processRetrait(retraitId) {
    const form = document.getElementById('process-form');
    form.action = `/admin/retraits/${retraitId}/process`;

    const modal = new bootstrap.Modal(document.getElementById('processModal'));
    modal.show();
}

// Auto-refresh every 30 seconds for pending retraits
setInterval(function() {
    if (document.querySelector('.badge.bg-warning')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
