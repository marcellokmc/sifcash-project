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
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-echeances" data-bs-toggle="tab" data-bs-target="#pane-echeances" type="button" role="tab">Échéancier</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-paiements" data-bs-toggle="tab" data-bs-target="#pane-paiements" type="button" role="tab">Paiements</button>
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

          <div class="tab-pane fade" id="pane-echeances" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Attendu</th>
                    <th>Payé</th>
                    <th>Pénalité</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                @forelse($credit->echeances as $i => $e)
                  <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($e->date_echeance)->format('d/m/Y') }}</td>
                    <td>{{ number_format((float)$e->montant_attendu, 2, ',', ' ') }}</td>
                    <td>{{ number_format((float)$e->montant_paye, 2, ',', ' ') }}</td>
                    <td>{{ number_format((float)$e->penalite_appliquee, 2, ',', ' ') }}</td>
                    <td>
                      @php $badge = $e->statut === 'payé' ? 'bg-success' : ($e->statut === 'en_retard' ? 'bg-danger' : 'bg-secondary'); @endphp
                      <span class="badge {{ $badge }}">{{ $e->statut }}</span>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center py-3">Aucune échéance</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="tab-pane fade" id="pane-paiements" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Pénalité</th>
                    <th>Mode</th>
                    <th>Référence</th>
                    <th>Preuves</th>
                  </tr>
                </thead>
                <tbody>
                @forelse($credit->paiements as $p)
                  <tr>
                    <td>{{ $p->date_paiement }}</td>
                    <td>{{ number_format((float)$p->montant, 2, ',', ' ') }}</td>
                    <td>{{ number_format((float)$p->penalite, 2, ',', ' ') }}</td>
                    <td>{{ $p->mode ?? '—' }}</td>
                    <td>{{ $p->reference ?? '—' }}</td>
                    <td>
                      @forelse($p->preuves as $pr)
                        <a href="{{ Storage::url($pr->path) }}" target="_blank" class="btn btn-link btn-sm">{{ $pr->original_name }}</a>
                      @empty
                        —
                      @endforelse
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center py-3">Aucun paiement</td></tr>
                @endforelse
                </tbody>
              </table>
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
          <a href="{{ route('admin.credits.export-contract', ['credit' => $credit->id, 'format' => 'pdf']) }}" 
             class="btn btn-outline-danger btn-sm">
            <i class="fas fa-file-pdf me-2"></i>Contrat PDF
          </a>
          
          <!-- Export Échéancier -->
          <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('admin.credits.export-schedule', ['credit' => $credit->id, 'format' => 'pdf']) }}" 
               class="btn btn-outline-danger">
              <i class="fas fa-file-pdf me-1"></i>Échéancier PDF
            </a>
            <a href="{{ route('admin.credits.export-schedule', ['credit' => $credit->id, 'format' => 'excel']) }}" 
               class="btn btn-outline-success">
              <i class="fas fa-file-excel me-1"></i>Excel
            </a>
          </div>
          
          <!-- Export Paiements -->
          <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('admin.credits.export-payments', ['credit' => $credit->id, 'format' => 'pdf']) }}" 
               class="btn btn-outline-danger">
              <i class="fas fa-file-pdf me-1"></i>Paiements PDF
            </a>
            <a href="{{ route('admin.credits.export-payments', ['credit' => $credit->id, 'format' => 'excel']) }}" 
               class="btn btn-outline-success">
              <i class="fas fa-file-excel me-1"></i>Excel
            </a>
          </div>
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
        @endcan

        @can('contract', $credit)
          @if($credit->etat === 'approuve')
            <form method="post" action="{{ route('admin.credits.contract', $credit) }}" class="mb-2">
              @csrf
              <div class="mb-2">
                <label class="form-label">Date de début remboursement</label>
                <input name="date_debut_remboursement" type="date" class="form-control" value="{{ $credit->date_debut_remboursement ?? now()->toDateString() }}">
              </div>
              <button class="btn btn-primary w-100">Valider le contrat</button>
            </form>
          @endif
        @endcan

        @can('generateSchedule', $credit)
          @if(in_array($credit->etat, ['approuve','contrat']))
            <form method="post" action="{{ route('admin.credits.generate-schedule', $credit) }}" class="mb-2">
              @csrf
              <button class="btn btn-warning w-100">Générer l'échéancier</button>
            </form>
          @endif
        @endcan

        @can('recordPayment', $credit)
          @if($credit->etat === 'actif')
            <form method="post" action="{{ route('admin.credits.record-payment', $credit) }}" enctype="multipart/form-data">
              @csrf
              <div class="mb-2">
                <label class="form-label">Échéance</label>
                <select name="echeance_id" class="form-select">
                  @foreach($credit->echeances as $e)
                    <option value="{{ $e->id }}">{{ \Carbon\Carbon::parse($e->date_echeance)->format('d/m/Y') }} — Attendu {{ number_format((float)$e->montant_attendu, 2, ',', ' ') }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label">Date paiement</label>
                <input name="date_paiement" type="date" class="form-control" value="{{ now()->toDateString() }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Montant</label>
                <input name="montant" type="number" step="0.01" class="form-control" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Mode</label>
                <input name="mode" type="text" class="form-control">
              </div>
              <div class="mb-2">
                <label class="form-label">Référence</label>
                <input name="reference" type="text" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Preuves (fichiers)</label>
                <input type="file" name="preuves[]" class="form-control" multiple>
              </div>
              <button class="btn btn-outline-primary w-100">Enregistrer paiement</button>
            </form>
          @endif
        @endcan
      </div>
    </div>
  </div>
</div>
@endsection
