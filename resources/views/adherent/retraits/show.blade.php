@extends('layouts.adherent-modern')

@section('title', 'Détail de la Demande de Retrait')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('adherent.retraits.index') }}">Mes Retraits</a></li>
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
                        <a href="{{ route('adherent.retraits.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour à la liste
                        </a>

                        @if($retrait->statut === 'en_attente')
                        <button type="button" class="btn btn-danger" onclick="cancelRetrait({{ $retrait->id }})">
                            <i class="mdi mdi-close"></i> Annuler la demande
                        </button>
                        @endif

                        @if($retrait->statut === 'rejeté')
                        <a href="{{ route('adherent.retraits.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nouvelle Demande
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
                        <p class="mb-0">Votre demande est en cours de traitement par nos équipes. Vous recevrez une notification une fois validée.</p>
                    </div>
                </div>
            </div>
            @endif

            @if($retrait->statut === 'validé')
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success">
                        <h6><i class="mdi mdi-check-circle"></i> Demande validée</h6>
                        <p class="mb-0">Votre demande a été validée et sera traitée dans les plus brefs délais.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annuler la Demande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancel-form" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-circle"></i> Êtes-vous sûr de vouloir annuler cette demande de retrait ?
                    </div>
                    <p>Cette action est irréversible. Votre demande sera supprimée définitivement.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, garder</button>
                    <button type="submit" class="btn btn-danger">Oui, annuler</button>
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
function cancelRetrait(retraitId) {
    const form = document.getElementById('cancel-form');
    form.action = `/adherent/retraits/${retraitId}`;

    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}
</script>
@endpush
