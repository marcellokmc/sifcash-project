@extends('layouts.adherent-modern')

@section('title', 'Mes Paiements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Mes Paiements</li>
                    </ol>
                </div>
                <h4 class="page-title">Mes Paiements</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Soumettre un Paiement</h5>
                    <p class="card-text">Soumettez vos preuves de paiement pour vos adhésions.</p>
                    <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouveau Paiement
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Historique des Paiements</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterPayments('all')">Tous</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterPayments('validé')">Validés</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterPayments('soumis')">En attente</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterPayments('rejeté')">Rejetés</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="paiements-table">
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
                                            <a href="{{ route('adherent.paiements.show', $paiement) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($paiement->statut === 'brouillon')
                                            <a href="{{ route('adherent.paiements.edit', $paiement) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline fs-1"></i>
                                            <p>Aucun paiement trouvé</p>
                                            <a href="{{ route('adherent.paiements.create') }}" class="btn btn-primary">
                                                Soumettre votre premier paiement
                                            </a>
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
