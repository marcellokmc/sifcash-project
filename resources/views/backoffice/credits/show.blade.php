@extends('backoffice.layouts.app')

@section('title', 'Crédit #'.$credit->id)

@section('content')
@include('components.backoffice.alerts')

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h3 mb-0">Crédit #{{ $credit->id }}</h1>
    <div class="text-muted">Adhérent: {{ $credit->adherent->nom ?? '—' }}</div>
  </div>
  <div class="text-end">
    <span class="badge bg-secondary me-1">Statut: {{ $credit->statut }}</span>
    <span class="badge bg-info">État: {{ $credit->etat }}</span>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="creditTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-details" data-bs-toggle="tab" data-bs-target="#pane-details" type="button" role="tab">Détails</button>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="pane-details" role="tabpanel">
            <div class="row mb-2">
              <div class="col-md-6"><strong>Montant demandé</strong><br>{{ number_format((float)$credit->montant_demande, 2, ',', ' ') }} FCFA</div>
              <div class="col-md-6"><strong>Montant accordé</strong><br>{{ $credit->montant_accorde ? number_format((float)$credit->montant_accorde, 2, ',', ' ') . ' FCFA' : '—' }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-4"><strong>Taux</strong><br>{{ (float)$credit->taux }}%</div>
              <div class="col-md-4"><strong>Durée</strong><br>{{ $credit->duree }}</div>
              <div class="col-md-4"><strong>Périodicité</strong><br>{{ ucfirst($credit->periodicite) }}</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-6"><strong>Frais d'adhésion</strong><br>{{ number_format((float)$credit->frais_adhesion, 2, ',', ' ') }} FCFA</div>
              <div class="col-md-6"><strong>Frais de dossier</strong><br>{{ number_format((float)$credit->frais_dossier, 2, ',', ' ') }} FCFA</div>
            </div>
            <div class="row mb-2">
              <div class="col-md-6"><strong>Date demande</strong><br>{{ $credit->date_demande }}</div>
              <div class="col-md-6"><strong>Date début remboursement</strong><br>{{ $credit->date_debut_remboursement ?? '—' }}</div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <!-- Carte d'export -->
    <div class="card mb-3">
      <div class="card-header bg-primary text-white">
        <i class="fas fa-download me-2"></i>Exports
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <!-- Export Contrat -->
          @if(!empty($credit->contract_path))
            <a href="{{ route('admin.credits.contract.download', $credit) }}" 
               class="btn btn-outline-danger btn-sm">
              <i class="fas fa-file-pdf me-2"></i>Télécharger le contrat
            </a>
          @else
            <a href="{{ route('admin.credits.export-contract', ['credit' => $credit->id, 'format' => 'pdf']) }}" 
               class="btn btn-outline-danger btn-sm">
              <i class="fas fa-file-pdf me-2"></i>Générer le contrat
            </a>
          @endif
          
        </div>
      </div>
    </div>
    
    <div class="card mb-3">
      <div class="card-header">Actions</div>
      <div class="card-body">
        @can('approve', $credit)
          @if(in_array($credit->etat, ['soumis','en_examen']))
            <form method="post" action="{{ route('admin.credits.approve', $credit) }}" class="mb-2">
              @csrf
              <div class="mb-2">
                <label class="form-label">Montant accordé</label>
                <input name="montant_accorde" type="number" step="0.01" class="form-control" value="{{ $credit->montant_demande }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Taux (%)</label>
                <input name="taux" type="number" step="0.01" class="form-control" value="{{ $credit->taux }}">
              </div>
              <button class="btn btn-success w-100">Approuver</button>
            </form>

            <form method="post" action="{{ route('admin.credits.reject', $credit) }}" class="mb-2">
              @csrf
              <div class="mb-2">
                <label class="form-label">Motif du rejet</label>
                <textarea name="motif_rejet" class="form-control" rows="2" required></textarea>
              </div>
              <button class="btn btn-outline-danger w-100">Rejeter</button>
            </form>
          @endif

          @if($credit->etat !== 'cloture' && $credit->statut === 'approuvé')
            <form method="post" action="{{ route('admin.credits.mark-repaid', $credit) }}" onsubmit="return confirm('Confirmer le marquage comme remboursé ?');">
              @csrf
              <button class="btn btn-outline-secondary w-100">Marquer comme remboursé</button>
            </form>
          @endif
        @endcan
      </div>
    </div>
  </div>
</div>
@endsection
