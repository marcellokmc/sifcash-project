@props(['item', 'type' => 'paiement'])

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <i class="mdi mdi-check-circle text-success"></i>
            Panel de Validation
        </h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Statut Actuel</label>
                    <div>
                        @switch($item->statut)
                            @case('validé')
                                <span class="badge bg-success fs-6">Validé</span>
                                @break
                            @case('soumis')
                            @case('en_attente')
                                <span class="badge bg-warning fs-6">En attente</span>
                                @break
                            @case('rejeté')
                                <span class="badge bg-danger fs-6">Rejeté</span>
                                @break
                            @default
                                <span class="badge bg-light text-dark fs-6">{{ ucfirst($item->statut) }}</span>
                        @endswitch
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Date de Soumission</label>
                    <p class="mb-0">{{ $item->date_soumission->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        @if($item->statut === 'soumis' || $item->statut === 'en_attente')
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="mdi mdi-information"></i> Actions Disponibles</h6>
                    <p class="mb-0">Cette {{ $type }} nécessite une validation manuelle.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-success w-100" onclick="validateItem({{ $item->id }})">
                    <i class="mdi mdi-check"></i> Valider
                </button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-danger w-100" onclick="rejectItem({{ $item->id }})">
                    <i class="mdi mdi-close"></i> Rejeter
                </button>
            </div>
        </div>
        @endif

        @if($item->statut === 'validé' && $item->date_validation)
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Date de Validation</label>
                    <p class="mb-0">{{ $item->date_validation->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            @if($item->validatedByAgent)
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Validé par</label>
                    <p class="mb-0">{{ $item->validatedByAgent->nom }} {{ $item->validatedByAgent->prenom }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        @if($item->statut === 'rejeté' && $item->motif_rejet)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <h6><i class="mdi mdi-alert-circle"></i> Motif du Rejet</h6>
                    <p class="mb-0">{{ $item->motif_rejet }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de validation -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valider la {{ ucfirst($type) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="validate-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Êtes-vous sûr de vouloir valider cette {{ $type }} ?
                    </div>
                    <div class="mb-3">
                        <label for="validation-notes" class="form-label">Notes (Optionnel)</label>
                        <textarea class="form-control" id="validation-notes" name="notes" rows="3"
                                  placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider</button>
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
                <h5 class="modal-title">Rejeter la {{ ucfirst($type) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reject-form" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle"></i> Êtes-vous sûr de vouloir rejeter cette {{ $type }} ?
                    </div>
                    <div class="mb-3">
                        <label for="reject-motif" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject-motif" name="motif_rejet" rows="3"
                                  placeholder="Expliquez pourquoi cette {{ $type }} est rejetée..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function validateItem(itemId) {
    const form = document.getElementById('validate-form');
    form.action = `/admin/{{ $type }}s/${itemId}/validate`;

    const modal = new bootstrap.Modal(document.getElementById('validateModal'));
    modal.show();
}

function rejectItem(itemId) {
    const form = document.getElementById('reject-form');
    form.action = `/admin/{{ $type }}s/${itemId}/reject`;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endpush
