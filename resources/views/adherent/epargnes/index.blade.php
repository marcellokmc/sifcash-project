@extends('layouts.adherent-modern')

@section('title', 'Mon Épargne')

@push('styles')
<style>
    .epargne-card {
        border: none;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .epargne-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.13);
    }
    .tx-badge-depot   { background: #dcfce7; color: #16a34a; }
    .tx-badge-retrait { background: #fee2e2; color: #dc2626; }
    .tx-badge-interet { background: #dbeafe; color: #2563eb; }
    .tx-badge-frais   { background: #fef9c3; color: #ca8a04; }
    .tx-badge-autre   { background: #f3f4f6; color: #6b7280; }
    .solde-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .tx-row:hover { background: #f8f9ff; }
</style>
@endpush

@section('content')
<div class="container-fluid" style="max-width:1300px; margin:0 auto;">

    {{-- Titre --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas fa-piggy-bank text-primary me-2"></i>Mon Épargne
            </h1>
            <p class="text-muted mb-0">Consultez vos soldes et l'historique de vos transactions</p>
        </div>
        <a href="{{ route('adherent.adhesions.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-list me-1"></i>Mes adhésions
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Solde global hero --}}
    <div class="solde-hero mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-1 opacity-75" style="font-size:.9rem;">Solde total épargne</p>
                <h2 class="fw-bold mb-0" style="font-size:2.5rem;">
                    {{ number_format($epargnes->sum('solde_actuel'), 0, ',', ' ') }} <small style="font-size:1rem;">FCFA</small>
                </h2>
                <p class="mt-2 mb-0 opacity-75">
                    <i class="fas fa-wallet me-1"></i>{{ $epargnes->count() }} compte(s) épargne actif(s)
                </p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <i class="fas fa-chart-pie fa-5x opacity-25"></i>
            </div>
        </div>
    </div>

    @if($epargnes->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-piggy-bank fa-4x text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Aucun compte épargne pour le moment</h5>
                <p class="text-muted">Souscrivez à un plan d'épargne pour commencer à épargner.</p>
                <a href="{{ route('adherent.adhesions.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-1"></i>Choisir un plan
                </a>
            </div>
        </div>
    @else

        {{-- Cartes des comptes épargne --}}
        <div class="row g-3 mb-4">
            @foreach($epargnes as $epargne)
            <div class="col-md-6 col-xl-4">
                <div class="card epargne-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="text-muted mb-1" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.5px;">
                                    {{ $epargne->type_epargne_formatted }}
                                </h6>
                                <h4 class="fw-bold mb-0 text-primary">
                                    {{ number_format($epargne->solde_actuel, 0, ',', ' ') }} FCFA
                                </h4>
                            </div>
                            <span class="badge bg-{{ $epargne->statut === 'actif' ? 'success' : 'secondary' }} px-3 py-2">
                                {{ ucfirst($epargne->statut) }}
                            </span>
                        </div>
                        <div class="row text-center mt-3 pt-3 border-top">
                            <div class="col-4">
                                <small class="text-muted d-block">Taux</small>
                                <span class="fw-bold text-info">{{ $epargne->taux_interet }}%</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Intérêts</small>
                                <span class="fw-bold text-success">{{ number_format($epargne->interet_cumule, 0, ',', ' ') }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">N° compte</small>
                                <span class="fw-bold" style="font-size:.8rem;">{{ $epargne->numero_compte }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Historique des transactions --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="mb-0">
                    <i class="fas fa-history text-primary me-2"></i>Historique des transactions
                </h5>
            </div>
            <div class="card-body p-0">
                @if($transactions->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucune transaction enregistrée.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Montant</th>
                                    <th>Solde après</th>
                                    <th>Référence</th>
                                    <th>Moyen</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $tx)
                                <tr class="tx-row">
                                    <td>
                                        <small class="text-muted">{{ $tx->date_operation->format('d/m/Y') }}</small><br>
                                        <small class="text-muted" style="font-size:.75rem;">{{ $tx->date_operation->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $txClass = match($tx->type_operation) {
                                                'depot'   => 'tx-badge-depot',
                                                'retrait' => 'tx-badge-retrait',
                                                'interet' => 'tx-badge-interet',
                                                'frais'   => 'tx-badge-frais',
                                                default   => 'tx-badge-autre',
                                            };
                                        @endphp
                                        <span class="badge {{ $txClass }} px-2 py-1">
                                            {{ $tx->type_operation_formatted }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array($tx->type_operation, ['depot', 'interet', 'virement']))
                                            <strong class="text-success">
                                                +{{ number_format($tx->montant, 0, ',', ' ') }} FCFA
                                            </strong>
                                        @else
                                            <strong class="text-danger">
                                                -{{ number_format($tx->montant, 0, ',', ' ') }} FCFA
                                            </strong>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($tx->solde_apres_operation, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <small class="text-muted font-monospace">{{ $tx->reference ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $tx->moyen_paiement_formatted }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted" style="max-width:150px; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $tx->notes }}">
                                            {{ $tx->notes ?? '—' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($transactions->hasPages())
                        <div class="px-3 py-3">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
