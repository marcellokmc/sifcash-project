@extends('backoffice.layouts.app')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord Administrateur')

@push('styles')
<style>
    /* Améliorations mobiles */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 1rem;
        }
        .stat-card {
            padding: 1rem 0.5rem;
        }
        .stat-icon {
            font-size: 1.5rem !important;
        }
        .quick-action {
            margin-bottom: 1rem;
        }
    }
    
    .alert-critical {
        border-left: 4px solid #dc3545;
        transition: all 0.3s ease;
    }
    .alert-critical:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    }
    .quick-action {
        transition: transform 0.2s;
        height: 100%;
    }
    .quick-action:hover {
        transform: translateY(-3px);
    }
    .stat-card {
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<!-- Alertes critiques -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-left-danger">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Alertes critiques
                </h6>
                <span class="badge bg-danger">{{ count($alerts) }}</span>
            </div>
            <div class="card-body p-0">
                @if(count($alerts) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($alerts as $alert)
                            <a href="{{ $alert['url'] }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $alert['title'] }}</h6>
                                    <small>{{ $alert['time'] }}</small>
                                </div>
                                <p class="mb-1">{{ $alert['message'] }}</p>
                                <small class="text-danger">
                                    <i class="fas fa-arrow-circle-right me-1"></i>Voir les détails
                                </small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="mb-0">Aucune alerte critique pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cartes de statistiques -->
<div class="row mb-4">
    <!-- Utilisateurs -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Utilisateurs Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Adhérents -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Adhérents</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="total_adherents">{{ $stats['total_adherents'] ?? 0 }}</div>
                        @if(isset($stats['adherents_en_attente']) && $stats['adherents_en_attente'] > 0)
                            <small class="text-warning">
                                <i class="fas fa-clock me-1"></i>{{ $stats['adherents_en_attente'] }} en attente
                            </small>
                        @endif
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Crédits -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Crédits actifs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="credits_actifs">{{ $stats['credits_actifs'] ?? 0 }}</div>
                        <div class="mt-1">
                            @if(isset($stats['credits_en_attente']) && $stats['credits_en_attente'] > 0)
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>{{ $stats['credits_en_attente'] }} en attente
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paiements en retard -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Paiements en retard</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" data-stat="paiements_retard">{{ $stats['paiements_retard'] ?? 0 }}</div>
                        <div class="mt-1">
                            <small class="text-danger">
                                {{ $stats['montant_retard'] ?? 0 }} FCFA
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Actions rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.credits.create') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <span>Nouveau crédit</span>
                        </a>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.adherents.create') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <span>Nouvel adhérent</span>
                        </a>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.payments.overdue') }}" class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action position-relative">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <span>Retards</span>
                            @if(isset($stats['paiements_retard']) && $stats['paiements_retard'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $stats['paiements_retard'] }}
                                </span>
                            @endif
                        </a>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.reports.monthly') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <span>Rapports</span>
                        </a>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action">
                            <i class="fas fa-cog fa-2x mb-2"></i>
                            <span>Paramètres</span>
                        </a>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('admin.help') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 quick-action">
                            <i class="fas fa-question-circle fa-2x mb-2"></i>
                            <span>Aide</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques d'évolution -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Évolution des crédits ({{ now()->format('Y') }})
                </h6>
            </div>
            <div class="card-body">
                <canvas id="creditsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-money-bill-wave me-2"></i>Encaissements ({{ now()->format('Y') }})
                </h6>
            </div>
            <div class="card-body">
                <canvas id="paymentsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Activité récente -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Activité récente
                </h6>
                <a href="{{ route('admin.activity') }}" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentActivities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $activity->description }}</h6>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small text-muted">
                                <i class="fas fa-user me-1"></i>{{ $activity->causer->name ?? 'Système' }}
                            </p>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">
                            Aucune activité récente
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="col-lg-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Statistiques
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Crédits approuvés</span>
                        <strong>{{ $stats['credits_approuves'] ?? 0 }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ ($stats['credits_approuves'] ?? 0) / max(1, ($stats['total_credits'] ?? 1)) * 100 }}%" 
                             aria-valuenow="{{ $stats['credits_approuves'] ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ $stats['total_credits'] ?? 100 }}">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Taux de remboursement</span>
                        <strong>{{ $stats['taux_remboursement'] ?? 0 }}%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" 
                             style="width: {{ $stats['taux_remboursement'] ?? 0 }}%" 
                             aria-valuenow="{{ $stats['taux_remboursement'] ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Adhésions ce mois</span>
                        <strong>{{ $stats['adhesions_mois'] ?? 0 }}</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" 
                             style="width: {{ min(100, (($stats['adhesions_mois'] ?? 0) / max(1, ($stats['adhesions_mois_dernier'] ?? 1))) * 100) }}%" 
                             aria-valuenow="{{ $stats['adhesions_mois'] ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="{{ max(1, ($stats['adhesions_mois_dernier'] ?? 1)) * 1.5 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Aperçu du calendrier -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="far fa-calendar-alt me-2"></i>Calendrier
                </h6>
            </div>
            <div class="card-body">
                <div id="mini-calendar"></div>
                <div class="mt-3">
                    <h6 class="text-uppercase small text-muted mb-2">Prochains événements</h6>
                    @forelse($upcomingEvents as $event)
                        <div class="d-flex mb-2">
                            <div class="bg-primary text-white rounded p-1 me-2 text-center" style="width: 40px;">
                                <div class="small">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
                                <div class="h5 mb-0">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small">{{ $event->title }}</div>
                                <div class="text-muted small">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                                    @if($event->location)
                                        • {{ $event->location }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Aucun événement à venir</p>
                    @endforelse
                    <a href="{{ route('admin.calendar') }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                        Voir le calendrier complet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des crédits
    const creditsCtx = document.getElementById('creditsChart').getContext('2d');
    const creditsChart = new Chart(creditsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyStats['months'] ?? []) !!},
            datasets: [{
                label: 'Nouveaux crédits',
                data: {!! json_encode($monthlyStats['credits'] ?? []) !!},
                borderColor: 'rgba(78, 115, 223, 1)',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Graphique des paiements
    const paymentsCtx = document.getElementById('paymentsChart').getContext('2d');
    const paymentsChart = new Chart(paymentsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyStats['months'] ?? []) !!},
            datasets: [{
                label: 'Montant',
                data: {!! json_encode($monthlyStats['payments'] ?? []) !!},
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('fr-FR', { 
                                style: 'currency', 
                                currency: 'XOF',
                                maximumFractionDigits: 0 
                            }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR', { 
                                style: 'currency', 
                                currency: 'XOF',
                                maximumFractionDigits: 0,
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });

    // Rafraîchir les données toutes les 5 minutes
    setInterval(() => {
        fetch('{{ route("admin.dashboard.stats") }}')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les graphiques
                if (creditsChart && data.monthly) {
                    creditsChart.data.datasets[0].data = data.monthly.credits || [];
                    creditsChart.update();
                }
                
                if (paymentsChart && data.monthly) {
                    paymentsChart.data.datasets[0].data = data.monthly.payments || [];
                    paymentsChart.update();
                }
                
                // Mettre à jour les compteurs
                document.querySelectorAll('[data-stat]').forEach(el => {
                    const stat = el.getAttribute('data-stat');
                    if (data[stat] !== undefined) {
                        el.textContent = data[stat];
                    }
                });
            })
            .catch(error => console.error('Erreur lors du rafraîchissement des données:', error));
    }, 300000); // 5 minutes

    // Initialisation du mini-calendrier
    document.addEventListener('DOMContentLoaded', function() {
        // Ici, vous pouvez initialiser un plugin de calendrier comme FullCalendar
        // ou une solution plus légère selon vos besoins
        console.log('Initialisation du calendrier...');
    });
</script>
@endpush

@endsection
