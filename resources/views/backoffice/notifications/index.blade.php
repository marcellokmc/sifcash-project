@extends('backoffice.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Notifications</h1>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th>Date</th>
            <th>Utilisateur</th>
            <th>Titre</th>
            <th>Message</th>
            <th>Type</th>
            <th>Lu</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $n)
            <tr>
              <td>{{ $n->created_at?->format('d/m/Y H:i') }}</td>
              <td>{{ $n->user?->name ?? '—' }}</td>
              <td>{{ $n->titre }}</td>
              <td>{{ Str::limit($n->message, 150) }}</td>
              <td><span class="badge bg-{{ $n->type === 'alert' ? 'danger' : 'info' }}">{{ $n->type }}</span></td>
              <td>
                @if($n->lu)
                  <span class="badge bg-success">Oui</span>
                @else
                  <span class="badge bg-secondary">Non</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4">Aucune notification.</td>
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
