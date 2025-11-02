@extends('backoffice.layouts.app')

@section('title', "Dashboard Agence: " . ($agence->nom ?? 'Mon agence'))

@section('content')
<div class="row g-3">
  <!-- Cartes KPI -->
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Adhérents (Agence)</div>
            <div class="h4 fw-bold">{{ number_format($stats['total_adherents']) }}</div>
          </div>
          <i class="fas fa-users fa-2x text-primary"></i>
        </div>
        <div class="mt-2 small">
          <span class="badge bg-success">Actifs: {{ $stats['adherents_actifs'] }}</span>
          <span class="badge bg-warning text-dark">En attente: {{ $stats['adherents_en_attente'] }}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Documents en attente</div>
            <div class="h4 fw-bold">{{ number_format($stats['documents_en_attente']) }}</div>
          </div>
          <i class="fas fa-file-alt fa-2x text-warning"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Crédits (en attente / approuvés)</div>
            <div class="h4 fw-bold">{{ number_format($stats['credits_en_attente']) }} / {{ number_format($stats['credits_approuves']) }}</div>
          </div>
          <i class="fas fa-hand-holding-usd fa-2x text-success"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Épargne totale (FCFA)</div>
            <div class="h4 fw-bold">{{ number_format($stats['epargnes_total'], 0, ',', ' ') }}</div>
          </div>
          <i class="fas fa-piggy-bank fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Activité récente de l'agence -->
<div class="card mt-4 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Activité récente (Agence)</h5>
    <span class="text-muted small">Derniers événements</span>
  </div>
  <div class="card-body">
    @if($recentActivity->count() > 0)
      <div class="list-group list-group-flush">
        @foreach($recentActivity as $log)
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">{{ $log->user->name ?? 'Utilisateur' }} <span class="text-muted">— {{ $log->action }}</span></div>
              <div class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <span class="badge bg-light text-dark">{{ $log->user->agence->nom ?? 'Agence' }}</span>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-center text-muted py-4">
        <i class="fas fa-inbox fa-2x mb-2"></i>
        <div>Aucune activité récente</div>
      </div>
    @endif
  </div>
</div>
<!-- Graphiques -->
<div class="row mt-4">
  <div class="col-lg-7 mb-3">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Répartition par agent</h6>
      </div>
      <div class="card-body">
        <canvas id="agentsChart" height="140"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-5 mb-3">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Tendances (6 derniers mois)</h6>
      </div>
      <div class="card-body">
        <canvas id="trendsChart" height="140"></canvas>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
  const agentsLabels = @json($agentsData['labels'] ?? []);
  const adherentsCounts = @json($agentsData['adherentsCounts'] ?? []);
  const docsPending = @json($agentsData['docsPending'] ?? []);
  const creditsPending = @json($agentsData['creditsPending'] ?? []);

  const ctxA = document.getElementById('agentsChart');
  if (ctxA && agentsLabels.length) {
    new Chart(ctxA, {
      type: 'bar',
      data: {
        labels: agentsLabels,
        datasets: [
          { label: 'Adhérents', data: adherentsCounts, backgroundColor: 'rgba(59,130,246,0.6)' },
          { label: 'Docs en attente', data: docsPending, backgroundColor: 'rgba(234,179,8,0.7)' },
          { label: 'Crédits en attente', data: creditsPending, backgroundColor: 'rgba(34,197,94,0.7)' },
        ]
      },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
  }

  const trendLabels = @json($trends['labels'] ?? []);
  const creditsApproved = @json($trends['creditsApproved'] ?? []);
  const newAdherents = @json($trends['newAdherents'] ?? []);
  const ctxT = document.getElementById('trendsChart');
  if (ctxT && trendLabels.length) {
    new Chart(ctxT, {
      type: 'line',
      data: {
        labels: trendLabels,
        datasets: [
          { label: 'Crédits approuvés', data: creditsApproved, borderColor: 'rgba(34,197,94,1)', backgroundColor: 'rgba(34,197,94,0.15)', tension: .3 },
          { label: 'Nouveaux adhérents', data: newAdherents, borderColor: 'rgba(59,130,246,1)', backgroundColor: 'rgba(59,130,246,0.15)', tension: .3 },
        ]
      },
      options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });
  }
})();
</script>
@endpush
@endsection
