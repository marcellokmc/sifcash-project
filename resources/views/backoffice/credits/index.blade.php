@extends('backoffice.layouts.app')

@section('title', 'Crédits')

@section('content')
@include('components.backoffice.alerts')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-0">Gestion des Crédits</h1>
    <p class="text-muted mb-0">Liste des demandes de crédit et leur traitement</p>
  </div>
  <div>
    <div class="btn-group" role="group">
      <a href="{{ route('admin.credits.index') }}" 
         class="btn {{ !request('statut') && !request('etat') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-list me-1"></i>Tous
      </a>
      <a href="{{ route('admin.credits.index', ['etat' => 'soumis']) }}" 
         class="btn {{ request('etat') === 'soumis' ? 'btn-warning' : 'btn-outline-warning' }}">
        <i class="fas fa-paper-plane me-1"></i>Soumis
        @php $soumisCount = \App\Models\Credit::where('etat', 'soumis')->count() @endphp
        @if($soumisCount > 0)<span class="badge bg-light text-dark ms-1">{{ $soumisCount }}</span>@endif
      </a>
      <a href="{{ route('admin.credits.index', ['etat' => 'en_examen']) }}" 
         class="btn {{ request('etat') === 'en_examen' ? 'btn-info' : 'btn-outline-info' }}">
        <i class="fas fa-search me-1"></i>En examen
        @php $examenCount = \App\Models\Credit::where('etat', 'en_examen')->count() @endphp
        @if($examenCount > 0)<span class="badge bg-light text-dark ms-1">{{ $examenCount }}</span>@endif
      </a>
      <a href="{{ route('admin.credits.index', ['etat' => 'approuve']) }}" 
         class="btn {{ request('etat') === 'approuve' ? 'btn-success' : 'btn-outline-success' }}">
        <i class="fas fa-check me-1"></i>Approuvés
      </a>
      <a href="{{ route('admin.credits.index', ['etat' => 'actif']) }}" 
         class="btn {{ request('etat') === 'actif' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-play me-1"></i>Actifs
      </a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Adhérent</th>
            <th>Montant demandé</th>
            <th>Montant accordé</th>
            <th>Taux</th>
            <th>Durée</th>
            <th>Périodicité</th>
            <th>Statut</th>
            <th>État</th>
            <th width="200">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $credit)
            <tr>
              <td>{{ $credit->id }}</td>
              <td>{{ $credit->adherent->nom ?? '—' }}</td>
              <td>{{ number_format((float)$credit->montant_demande, 2, ',', ' ') }} FCFA</td>
              <td>{{ $credit->montant_accorde ? number_format((float)$credit->montant_accorde, 2, ',', ' ') . ' FCFA' : '—' }}</td>
              <td>{{ (float)$credit->taux }}%</td>
              <td>{{ $credit->duree }}</td>
              <td>{{ ucfirst($credit->periodicite) }}</td>
              <td>
                @php
                  $statutClass = match($credit->statut) {
                    'en_attente', 'soumis' => 'bg-warning text-dark',
                    'approuve', 'approuvé' => 'bg-success',
                    'rejete', 'rejeté' => 'bg-danger',
                    'actif' => 'bg-primary',
                    default => 'bg-secondary'
                  };
                @endphp
                <span class="badge {{ $statutClass }}">{{ ucfirst($credit->statut) }}</span>
              </td>
              <td>
                @php
                  $etatClass = match($credit->etat) {
                    'soumis', 'en_examen' => 'bg-warning text-dark',
                    'approuve' => 'bg-success',
                    'contrat' => 'bg-info',
                    'actif' => 'bg-primary',
                    'rejete' => 'bg-danger',
                    default => 'bg-secondary'
                  };
                @endphp
                <span class="badge {{ $etatClass }}">{{ ucfirst($credit->etat) }}</span>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <a href="{{ route('admin.credits.show', $credit) }}" 
                     class="btn btn-sm btn-outline-primary" 
                     title="Voir les détails">
                    <i class="fas fa-eye"></i>
                  </a>
                  
                  @can('approve', $credit)
                    @if(in_array($credit->etat, ['soumis', 'en_examen']))
                      <button type="button"
                              class="btn btn-sm btn-success" 
                              data-bs-toggle="modal" 
                              data-bs-target="#approveModal{{ $credit->id }}"
                              title="Approuver">
                        <i class="fas fa-check"></i>
                      </button>
                      <button type="button" 
                              class="btn btn-sm btn-danger" 
                              data-bs-toggle="modal" 
                              data-bs-target="#rejectModal{{ $credit->id }}"
                              title="Rejeter">
                        <i class="fas fa-times"></i>
                      </button>
                    @endif
                  @endcan
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-4">
                <div class="text-center">
                  <i class="fas fa-credit-card text-muted" style="font-size: 3rem;"></i>
                  <h5 class="mt-3 text-muted">Aucun crédit trouvé</h5>
                  <p class="text-muted">Les demandes de crédit apparaîtront ici.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if(method_exists($items, 'links'))
    <div class="card-footer">{{ $items->links() }}</div>
  @endif
</div>

<!-- Modales d'approbation et de rejet -->
@foreach($items as $credit)
  @can('approve', $credit)
    @if(in_array($credit->etat, ['soumis', 'en_examen']))
      <!-- Modal Approbation -->
      <div class="modal fade" id="approveModal{{ $credit->id }}" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Approuver le crédit #{{ $credit->id }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.credits.approve', $credit) }}">
              @csrf
              <div class="modal-body">
                <div class="alert alert-info">
                  <strong>Adhérent:</strong> {{ $credit->adherent->nom ?? 'N/A' }}<br>
                  <strong>Montant demandé:</strong> {{ number_format($credit->montant_demande, 0, ',', ' ') }} FCFA<br>
                  <strong>Durée:</strong> {{ $credit->duree }} mois
                </div>
                
                <div class="mb-3">
                  <label for="montant_accorde{{ $credit->id }}" class="form-label">Montant accordé <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           id="montant_accorde{{ $credit->id }}"
                           name="montant_accorde" 
                           value="{{ $credit->montant_demande }}" 
                           min="0" 
                           step="1000" 
                           required>
                    <span class="input-group-text">FCFA</span>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label for="taux{{ $credit->id }}" class="form-label">Taux d'intérêt (%)</label>
                  <div class="input-group">
                    <input type="number" 
                           class="form-control" 
                           id="taux{{ $credit->id }}"
                           name="taux" 
                           value="{{ $credit->taux }}" 
                           min="0" 
                           max="50" 
                           step="0.1">
                    <span class="input-group-text">%</span>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-check me-1"></i>Approuver le crédit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Rejet -->
      <div class="modal fade" id="rejectModal{{ $credit->id }}" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Rejeter le crédit #{{ $credit->id }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.credits.reject', $credit) }}">
              @csrf
              <div class="modal-body">
                <div class="alert alert-warning">
                  <strong>Attention:</strong> Cette action rejettera définitivement la demande de crédit.
                </div>
                
                <div class="alert alert-info">
                  <strong>Adhérent:</strong> {{ $credit->adherent->nom ?? 'N/A' }}<br>
                  <strong>Montant demandé:</strong> {{ number_format($credit->montant_demande, 0, ',', ' ') }} FCFA
                </div>
                
                <div class="mb-3">
                  <label for="motif_rejet{{ $credit->id }}" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                  <textarea class="form-control" 
                            id="motif_rejet{{ $credit->id }}"
                            name="motif_rejet" 
                            rows="4" 
                            placeholder="Expliquez les raisons du rejet..."
                            required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">
                  <i class="fas fa-times me-1"></i>Rejeter le crédit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif
  @endcan
@endforeach

@endsection
