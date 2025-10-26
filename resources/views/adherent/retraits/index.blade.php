@extends('layouts.adherent-modern')

@section('title', 'Mes Demandes de Retrait')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('adherent.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Mes Retraits</li>
                    </ol>
                </div>
                <h4 class="page-title">Mes Demandes de Retrait</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Demander un Retrait</h5>
                    <p class="card-text">Demandez le retrait de vos fonds disponibles.</p>
                    <a href="{{ route('adherent.retraits.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Demande
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Historique des Demandes</h4>
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

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="retraits-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
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
                                            <a href="{{ route('adherent.retraits.show', $retrait) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($retrait->statut === 'en_attente')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="cancelRetrait({{ $retrait->id }})" title="Annuler">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline fs-1"></i>
                                            <p>Aucune demande de retrait trouvée</p>
                                            <a href="{{ route('adherent.retraits.create') }}" class="btn btn-primary">
                                                Faire votre première demande
                                            </a>
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

@push('scripts')
<script>
function filterRetraits(status) {
    // Implementation for filtering retraits by status
    console.log('Filtering by status:', status);
    // Add AJAX call to filter retraits
}

function cancelRetrait(retraitId) {
    const form = document.getElementById('cancel-form');
    form.action = `/adherent/retraits/${retraitId}`;

    const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}
</script>
@endpush
