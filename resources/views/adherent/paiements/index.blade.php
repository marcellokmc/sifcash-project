@extends('layouts.adherent-modern')

@section('title', 'Mes Paiements')

@section('content')
<div class="container-fluid">
    <!-- Header Mobile-First -->
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">💵 Mes Paiements</h4>
                    <small class="text-muted">
                        <a href="{{ route('adherent.dashboard') }}" class="text-decoration-none">Tableau de bord</a> / Paiements
                    </small>
                </div>
                <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary btn-mobile visible-desktop">
                    <i class="fas fa-plus me-1"></i>Nouveau
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Action Card Mobile -->
    <div class="row mb-mobile-3 visible-mobile">
        <div class="col-12">
            <div class="card sif-card-mobile border-0 shadow-sm">
                <div class="card-body text-center p-mobile-3">
                    <i class="fas fa-plus-circle text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1 fw-bold">Soumettre un Paiement</h6>
                    <p class="text-muted small mb-3">Soumettez vos preuves de paiement</p>
                    <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary btn-mobile">
                        <i class="fas fa-plus me-1"></i> Nouveau Paiement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-2">
                    <!-- Filtres -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>Historique
                        </h5>
                        <div class="btn-group-mobile w-100 w-md-auto" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterPayments('all')">Tous</button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="filterPayments('validé')">Validés</button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterPayments('soumis')">Attente</button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterPayments('rejeté')">Rejetés</button>
                        </div>
                    </div>

                    <div class="table-responsive-mobile">
                        <table class="table table-mobile table-striped" id="paiements-table">
                            <thead>
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
                                @forelse($paiements as $paiement)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $paiement->date_soumission->format('d/m/Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $paiement->date_soumission->format('H:i') }}</small>
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
                                            <a href="{{ route('adherent.paiements.show', $paiement) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($paiement->statut === 'brouillon')
                                            <a href="{{ route('adherent.paiements.edit', $paiement) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($paiements->hasPages())
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                                <small class="text-muted">
                                    Affichage de {{ $paiements->firstItem() }} à {{ $paiements->lastItem() }} sur {{ $paiements->total() }} résultats
                                </small>
                                <div>
                                    {{ $paiements->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
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
</script>
@endpush
