@extends('backoffice.layouts.app')

@section('title', 'Rapports et Statistiques des Crédits')

@push('styles')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-chart-bar text-primary me-2"></i> Rapports et Statistiques des Crédits
        </h1>
        <div>
            <a href="{{ route('admin.credits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des crédits</div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ number_format($stats['total_credits'], 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Montant total accordé</div>
                            <div class="h5 mb-0 font-weight-bold text-dark">{{ number_format($stats['total_montant'], 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Taux de remboursement</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-dark">{{ $stats['taux_remboursement'] }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" 
                                             style="width: {{ $stats['taux_remboursement'] }}%" 
                                             aria-valuenow="{{ $stats['taux_remboursement'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percent fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Montant moyen</div>
                            <div class="h5 mb-0 font-weight-bold text-dark">
                                {{ number_format($stats['moyenne_montant'], 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Répartition par statut -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des crédits par statut</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution mensuelle -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution mensuelle des crédits</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers crédits accordés -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Derniers crédits accordés</h6>
            <a href="{{ route('admin.credits.index', ['statut' => 'approuve']) }}" class="btn btn-sm btn-primary">
                Voir tout <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Adhérent</th>
                            <th>Montant</th>
                            <th>Date d'accord</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($derniersCredits as $credit)
                            <tr>
                                <td>#{{ str_pad($credit->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <a href="{{ route('admin.adherents.show', $credit->adherent) }}">
                                        {{ $credit->adherent->nom_complet }}
                                    </a>
                                </td>
                                <td>{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $credit->date_validation ? \Carbon\Carbon::parse($credit->date_validation)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $credit->statut === 'approuvé' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($credit->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun crédit trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Plus gros crédits -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Plus gros crédits accordés</h6>
            <a href="{{ route('admin.credits.index') }}?sort=montant_accorde&direction=desc" class="btn btn-sm btn-primary">
                Voir tout <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Adhérent</th>
                            <th>Montant</th>
                            <th>Date d'accord</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plusGrosCredits as $credit)
                            <tr>
                                <td>#{{ str_pad($credit->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <a href="{{ route('admin.adherents.show', $credit->adherent) }}">
                                        {{ $credit->adherent->nom_complet }}
                                    </a>
                                </td>
                                <td>{{ number_format($credit->montant_accorde, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $credit->date_validation ? \Carbon\Carbon::parse($credit->date_validation)->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $credit->statut === 'approuvé' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($credit->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucun crédit trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Graphique circulaire des statuts
    var ctxPie = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($stats['credits_par_statut']->toArray())) !!},
            datasets: [{
                data: {!! json_encode(array_values($stats['credits_par_statut']->toArray())) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                ],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#6c757d'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                },
            },
        },
    });

    // Graphique d'évolution mensuelle
    var ctxLine = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['credits_par_mois']->pluck('mois')) !!},
            datasets: [{
                label: "Nombre de crédits",
                data: {!! json_encode($stats['credits_par_mois']->pluck('total')) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        beginAtZero: true,
                        precision: 0
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleMarginBottom: 10,
                    titleColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            }
        }
    });
</script>
@endpush
