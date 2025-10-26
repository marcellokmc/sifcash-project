@extends('backoffice.layouts.app')

@section('title', 'Audits')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Audits</h1>
</div>

<div class="card mb-3">
  <div class="card-header">Filtres</div>
  <div class="card-body">
    <form method="get" class="row g-2">
      <div class="col-md-2">
        <label class="form-label">Utilisateur (ID)</label>
        <input type="number" class="form-control" name="user_id" value="{{ request('user_id') }}" placeholder="ID user">
      </div>
      <div class="col-md-3">
        <label class="form-label">Action (nom de route)</label>
        <input type="text" class="form-control" name="action" value="{{ request('action') }}" placeholder="ex: admin.credits.approve">
      </div>
      <div class="col-md-3">
        <label class="form-label">Model type</label>
        <input type="text" class="form-control" name="model_type" value="{{ request('model_type') }}" placeholder="App\\Models\\Credit">
      </div>
      <div class="col-md-2">
        <label class="form-label">Du</label>
        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
      </div>
      <div class="col-md-2">
        <label class="form-label">Au</label>
        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
      </div>
      <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary">Filtrer</button>
        <a href="{{ route('admin.audits.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
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
            <th>Date</th>
            <th>User</th>
            <th>Action</th>
            <th>Model</th>
            <th>Model ID</th>
            <th>Status</th>
            <th>IP</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $a)
            <tr>
              <td>{{ optional($a->created_at)->format('d/m/Y H:i') }}</td>
              <td>{{ $a->user_id }}</td>
              <td><code>{{ $a->action }}</code></td>
              <td><small>{{ $a->model_type }}</small></td>
              <td>{{ $a->model_id }}</td>
              <td>{{ is_array($a->nouvelle_valeur) && isset($a->nouvelle_valeur['status']) ? $a->nouvelle_valeur['status'] : '—' }}</td>
              <td>{{ $a->ip }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4">Aucun audit.</td>
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
