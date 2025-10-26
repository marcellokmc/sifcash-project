@extends('backoffice.layouts.app')

@section('title', 'Conditions d\'éligibilité crédit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Conditions d'éligibilité</h1>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card mb-3">
  <div class="card-header">Nouvelle condition</div>
  <div class="card-body">
    <form method="post" action="{{ route('admin.credits.eligibilites.store') }}" class="row g-3">
      @csrf
      <div class="col-md-4">
        <label class="form-label">Type de cotisation requise</label>
        <select name="type_cotisation_requise" class="form-select" required>
          <option value="journalier">Journalier</option>
          <option value="hebdomadaire">Hebdomadaire</option>
          <option value="mensuel">Mensuel</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Durée minimum d'ancienneté</label>
        <input type="number" class="form-control" name="duree_minimum_anciennete" min="0" required>
        <small class="text-muted">Unité selon type (jours/semaines/mois)</small>
      </div>
      <div class="col-md-3">
        <label class="form-label">Montant épargne minimum</label>
        <input type="number" step="0.01" class="form-control" name="montant_epargne_minimum" value="0">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="actif" id="actif-new" value="1" checked>
          <label class="form-check-label" for="actif-new">Actif</label>
        </div>
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Créer</button>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Type</th>
            <th>Ancienneté min</th>
            <th>Épargne min</th>
            <th>Actif</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $cond)
            <tr>
              <td>{{ $cond->id }}</td>
              <td>{{ ucfirst($cond->type_cotisation_requise) }}</td>
              <td>{{ $cond->duree_minimum_anciennete }}</td>
              <td><x-money :value="$cond->montant_epargne_minimum" /></td>
              <td>
                @if($cond->actif)
                  <x-badge type="success">Actif</x-badge>
                @else
                  <x-badge>Inactif</x-badge>
                @endif
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-1">
                  <form method="post" action="{{ route('admin.credits.eligibilites.toggle-active', $cond) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">Basculer actif</button>
                  </form>
                  <!-- Edit inline -->
                  <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#edit-{{ $cond->id }}">Éditer</button>
                </div>
              </td>
            </tr>
            <tr class="collapse" id="edit-{{ $cond->id }}">
              <td colspan="6">
                <form method="post" action="{{ route('admin.credits.eligibilites.update', $cond) }}" class="row g-2">
                  @csrf
                  @method('patch')
                  <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type_cotisation_requise" class="form-select">
                      @foreach(['journalier','hebdomadaire','mensuel'] as $t)
                        <option value="{{ $t }}" @selected($cond->type_cotisation_requise === $t)>{{ ucfirst($t) }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Ancienneté min</label>
                    <input type="number" class="form-control" name="duree_minimum_anciennete" value="{{ $cond->duree_minimum_anciennete }}">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Épargne min</label>
                    <input type="number" step="0.01" class="form-control" name="montant_epargne_minimum" value="{{ $cond->montant_epargne_minimum }}">
                  </div>
                  <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary">Enregistrer</button>
                  </div>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4">Aucune condition.</td>
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
@endsection
