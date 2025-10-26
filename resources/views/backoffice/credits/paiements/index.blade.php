@extends('backoffice.layouts.app')

@section('title', 'Paiements de Crédit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.credits.index') }}">Crédits</a></li>
                        <li class="breadcrumb-item active">Paiements</li>
                    </ol>
                </div>
                <h4 class="page-title">Paiements de Crédit</h4>
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
                                <button type="button" class="btn btn-primary" onclick="showRecordPaymentModal()">
                                    <i class="mdi mdi-plus"></i> Enregistrer un Paiement
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search-input" placeholder="Nom, crédit, référence...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Crédit</label>
                                <select class="form-select" id="credit-filter">
                                    <option value="">Tous les crédits</option>
                                    @foreach($credits as $credit)
                                    <option value="{{ $credit->id }}">{{ $credit->adherent->nom_complet }} - {{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" id="statut-filter">
                                    <option value="">Tous</option>
                                    <option value="valide">Validé</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="rejete">Rejeté</option>
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
                                    <th>Crédit</th>
                                    <th>Échéance</th>
                                    <th>Montant</th>
                                    <th>Pénalité</th>
                                    <th>Mode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paiements as $paiement)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $paiement->date_paiement->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $paiement->date_paiement->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle me-2">
                                                <span class="avatar-title text-white">{{ substr($paiement->credit->adherent->prenom, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $paiement->credit->adherent->nom_complet }}</h6>
                                                <small class="text-muted">{{ number_format($paiement->credit->montant_accorde, 0, ',', ' ') }} FCFA</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($paiement->echeance)
                                        <span class="fw-semibold">{{ $paiement->echeance->date_echeance->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">Échéance du {{ $paiement->echeance->date_echeance->format('d/m/Y') }}</small>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        @if($paiement->penalite > 0)
                                        <span class="fw-semibold text-danger">{{ number_format($paiement->penalite, 0, ',', ' ') }} FCFA</span>
                                        @else
                                        <span class="text-muted">Aucune</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ ucfirst($paiement->mode ?? 'N/A') }}</span>
                                    </td>
                                    <td>
                                        @switch($paiement->statut)
                                            @case('valide')
                                                <span class="badge bg-success">Validé</span>
                                                @break
                                            @case('en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                                @break
                                            @case('rejete')
                                                <span class="badge bg-danger">Rejeté</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($paiement->statut) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.credits.paiements.show', $paiement) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($paiement->preuves && $paiement->preuves->count() > 0)
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="showPreuves({{ $paiement->id }})" title="Preuves">
                                                <i class="mdi mdi-file-document"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
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

<!-- Modal d'enregistrement de paiement -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enregistrer un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="record-payment-form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="credit_id" class="form-label">Crédit <span class="text-danger">*</span></label>
                                <select class="form-select" id="credit_id" name="credit_id" required>
                                    <option value="">Sélectionner un crédit</option>
                                    @foreach($credits as $credit)
                                    <option value="{{ $credit->id }}"
                                            data-adherent="{{ $credit->adherent->nom_complet }}"
                                            data-montant="{{ $credit->montant_accorde }}">
                                        {{ $credit->adherent->nom_complet }} - {{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="echeance_id" class="form-label">Échéance <span class="text-danger">*</span></label>
                                <select class="form-select" id="echeance_id" name="echeance_id" required>
                                    <option value="">Sélectionner d'abord un crédit</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_paiement" class="form-label">Date de Paiement <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="date_paiement" name="date_paiement"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="montant" name="montant"
                                           step="0.01" min="0.01" required>
                                    <span class="input-group-text">FCFA</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mode" class="form-label">Mode de Paiement</label>
                                <select class="form-select" id="mode" name="mode">
                                    <option value="">Sélectionner un mode</option>
                                    <option value="especes">Espèces</option>
                                    <option value="virement">Virement</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="cheque">Chèque</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reference" class="form-label">Référence</label>
                                <input type="text" class="form-control" id="reference" name="reference"
                                       placeholder="Référence du paiement">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="preuves" class="form-label">Preuves de Paiement</label>
                        <input type="file" class="form-control" id="preuves" name="preuves[]"
                               accept=".pdf,.jpg,.jpeg,.png" multiple>
                        <div class="form-text">Formats acceptés: PDF, JPG, JPEG, PNG (Max: 5MB par fichier)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'affichage des preuves -->
<div class="modal fade" id="preuvesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preuves de Paiement</h5>
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
function showRecordPaymentModal() {
    const modal = new bootstrap.Modal(document.getElementById('recordPaymentModal'));
    modal.show();
}

function showPreuves(paiementId) {
    // Charger les preuves via AJAX
    fetch(`/admin/credits/paiements/${paiementId}/preuves`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('preuves-content');
            content.innerHTML = data.html;

            const modal = new bootstrap.Modal(document.getElementById('preuvesModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des preuves');
        });
}

function applyFilters() {
    // Implementation for applying advanced filters
    console.log('Applying filters');
    // Add AJAX call to apply filters
}

// Gestion du changement de crédit
document.getElementById('credit_id').addEventListener('change', function() {
    const creditId = this.value;
    const echeanceSelect = document.getElementById('echeance_id');

    if (creditId) {
        // Charger les échéances du crédit sélectionné
        fetch(`/admin/credits/${creditId}/echeances`)
            .then(response => response.json())
            .then(data => {
                echeanceSelect.innerHTML = '<option value="">Sélectionner une échéance</option>';
                data.forEach(echeance => {
                    const option = document.createElement('option');
                    option.value = echeance.id;
                    option.textContent = `Échéance du ${echeance.date_echeance} - ${echeance.montant_attendu} FCFA`;
                    echeanceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    } else {
        echeanceSelect.innerHTML = '<option value="">Sélectionner d\'abord un crédit</option>';
    }
});

// Auto-refresh every 30 seconds for pending payments
setInterval(function() {
    if (document.querySelector('.badge.bg-warning')) {
        location.reload();
    }
}, 30000);
</script>
@endpush
