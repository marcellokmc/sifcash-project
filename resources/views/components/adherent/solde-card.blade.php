@props(['adhesion'])

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="avatar-sm bg-primary rounded-circle">
                    <span class="avatar-title text-white">
                        <i class="mdi mdi-wallet"></i>
                    </span>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-1">{{ $adhesion->plan->nom }}</h6>
                <p class="text-muted mb-0">{{ $adhesion->numero_adhesion }}</p>
            </div>
            <div class="flex-shrink-0 text-end">
                <h5 class="mb-1 text-success">{{ number_format($adhesion->solde_actuel, 0, ',', ' ') }} FCFA</h5>
                <small class="text-muted">Solde actuel</small>
            </div>
        </div>

        <hr class="my-3">

        <div class="row text-center">
            <div class="col-4">
                <div class="border-end">
                    <h6 class="mb-1 text-primary">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }}</h6>
                    <small class="text-muted">Souscrit</small>
                </div>
            </div>
            <div class="col-4">
                <div class="border-end">
                    <h6 class="mb-1 text-info">{{ number_format($adhesion->interets_cumules, 0, ',', ' ') }}</h6>
                    <small class="text-muted">Intérêts</small>
                </div>
            </div>
            <div class="col-4">
                <h6 class="mb-1 text-warning">{{ number_format($adhesion->montant_retrait, 0, ',', ' ') }}</h6>
                <small class="text-muted">Retiré</small>
            </div>
        </div>

        <div class="mt-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Solde disponible</span>
                <span class="fw-bold text-success">{{ number_format($adhesion->solde_disponible, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        @if($adhesion->statut === 'actif')
        <div class="mt-3">
            <div class="d-grid gap-2">
                <a href="{{ route('adherent.retraits.create') }}" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-cash"></i> Demander un retrait
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
