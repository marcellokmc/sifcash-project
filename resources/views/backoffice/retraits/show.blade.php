@extends('backoffice.layouts.app')

@section('title', 'Détail de la Demande de Retrait')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.retraits.index') }}">Demandes de Retrait</a></li>
                        <li class="breadcrumb-item active">Détail de la Demande</li>
                    </ol>
                </div>
                <h4 class="page-title">Détail de la Demande de Retrait</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Informations de la Demande</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            @switch($retrait->statut)
                                @case('validé')
                                    <span class="badge bg-success fs-6">Validé</span>
                                    @break
                                @case('en_attente')
                                    <span class="badge bg-warning fs-6">En attente de validation</span>
                                    @break
                                @case('rejeté')
                                    <span class="badge bg-danger fs-6">Rejeté</span>
                                    @break
                                @case('traité')
                                    <span class="badge bg-primary fs-6">Traité</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark fs-6">{{ ucfirst($retrait->statut) }}</span>
                            @endswitch
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Montant Demandé</label>
                                <p class="text-danger fs-5">{{ number_format($retrait->montant_demande, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Type de Retrait</label>
                                <p><span class="badge bg-info">{{ ucfirst($retrait->type_retrait) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mode de Retrait</label>
                                <p>{{ ucfirst(str_replace('_', ' ', $retrait->mode_retrait)) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date de Demande</label>
                                <p>{{ $retrait->date_demande->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    @if($retrait->informations_retrait)
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Informations de Retrait</label>
                                <div class="border rounded p-3">
                                    @if(isset($retrait->informations_retrait['numero_mobile']))
                                    <p class="mb-1"><strong>Numéro Mobile Money:</strong> {{ $retrait->informations_retrait['numero_mobile'] }}</p>
                                    @endif
                                    @if(isset($retrait->informations_retrait['operateur_mobile']))
                                    <p class="mb-1"><strong>Opérateur:</strong> {{ ucfirst($retrait->informations_retrait['operateur_mobile']) }}</p>
                                    @endif
                                    @if(isset($retrait->informations_retrait['numero_compte']))
                                    <p class="mb-1"><strong>Numéro de Compte:</strong> {{ $retrait->informations_retrait['numero_compte'] }}</p>
                                    @endif
                                    @if(isset($retrait->informations_retrait['nom_banque']))
                                    <p class="mb-1"><strong>Banque:</strong> {{ $retrait->informations_retrait['nom_banque'] }}</p>
                                    @endif
                                    @if(isset($retrait->informations_retrait['nom_beneficiaire']))
                                    <p class="mb-0"><strong>Bénéficiaire:</strong> {{ $retrait->informations_retrait['nom_beneficiaire'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Motif du Retrait</label>
                                <div class="border rounded p-3">
                                    <p class="mb-0">{{ $retrait->motif }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($retrait->statut === 'rejeté' && $retrait->motif_rejet)
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h6><i class="mdi mdi-alert-circle"></i> Motif du Rejet</h6>
                                <p class="mb-0">{{ $retrait->motif_rejet }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($retrait->statut === 'validé' && $retrait->date_validation)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date de Validation</label>
                                <p>{{ $retrait->date_validation->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @if($retrait->validatedByAgent)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Validé par</label>
                                <p>{{ $retrait->validatedByAgent->nom }} {{ $retrait->validatedByAgent->prenom }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            @if($retrait->historiques && $retrait->historiques->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique du Traitement</h5>
                    <div class="timeline">
                        @foreach($retrait->historiques as $historique)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">{{ $historique->action }}</h6>
                                <p class="timeline-text">{{ $historique->description }}</p>
                                <small class="text-muted">{{ $historique->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations Adhérent</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary rounded-circle me-3">
                            <span class="avatar-title text-white fs-3">{{ substr($retrait->adherent->prenom, 0, 1) }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $retrait->adherent->nom_complet }}</h6>
                            <small class="text-muted">{{ $retrait->adherent->email }}</small>
                        </div>
                    </div>
                    <div class="border rounded p-3">
                        <p class="mb-1"><strong>Membre ID:</strong> {{ $retrait->adherent->membre_id }}</p>
                        <p class="mb-1"><strong>Téléphone:</strong> {{ $retrait->adherent->telephone }}</p>
                        <p class="mb-0"><strong>Statut:</strong>
                            <span class="badge bg-{{ $retrait->adherent->statut_compte === 'actif' ? 'success' : 'warning' }}">
                                {{ ucfirst($retrait->adherent->statut_compte) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Adhésion Associée</h5>
                    @if($retrait->adhesion)
                    <div class="border rounded p-3">
                        <h6 class="text-primary">{{ $retrait->adhesion->plan->nom }}</h6>
                        <p class="mb-1"><strong>Numéro:</strong> {{ $retrait->adhesion->numero_adhesion }}</p>
                        <p class="mb-1"><strong>Montant souscrit:</strong> {{ number_format($retrait->adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</p>
                        <p class="mb-1"><strong>Solde actuel:</strong> {{ number_format($retrait->adhesion->solde_actuel, 0, ',', ' ') }} FCFA</p>
                        <p class="mb-0"><strong>Statut:</strong>
                            <span class="badge bg-{{ $retrait->adhesion->statut === 'actif' ? 'success' : 'warning' }}">
                                {{ ucfirst($retrait->adhesion->statut) }}
                            </span>
                        </p>
                    </div>
                    @else
                    <p class="text-muted">Aucune adhésion associée</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.retraits.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour à la liste
                        </a>

                        @if($retrait->statut === 'en_attente')
                        <button type="button" class="btn btn-success" onclick="validateRetrait({{ $retrait->id }})">
                            <i class="mdi mdi-check"></i> Valider
                        </button>
                        <button type="button" class="btn btn-danger" onclick="rejectRetrait({{ $retrait->id }})">
                            <i class="mdi mdi-close"></i> Rejeter
                        </button>
                        @endif

                        @if($retrait->statut === 'validé')
                        <button type="button" class="btn btn-primary" onclick="processRetrait({{ $retrait->id }})">
                            <i class="mdi mdi-cash"></i> Traiter
                        </button>
                        @endif

                        <a href="{{ route('admin.adherents.show', $retrait->adherent) }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-account"></i> Voir l'adhérent
                        </a>

                        @if($retrait->adhesion)
                        <a href="{{ route('admin.adhesions.show', $retrait->adhesion) }}" class="btn btn-outline-info">
                            <i class="mdi mdi-file-document"></i> Voir l'adhésion
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($retrait->statut === 'en_attente')
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="mdi mdi-clock"></i> En attente de validation</h6>
                        <p class="mb-0">Cette demande nécessite une validation manuelle. Vérifiez les informations avant de valider ou rejeter.</p>
                    </div>
                </div>
            </div>
            @endif

            @if($retrait->statut === 'validé')
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        <h6><i class="mdi mdi-check-circle"></i> Demande validée</h6>
                        <p class="mb-0">Cette demande a été validée et peut maintenant être traitée.</p>
                    </div>
                </div>
            </div>
            @endif
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

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
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
</script>
@endpush
