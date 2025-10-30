@extends('backoffice.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Notifications</h1>
  <div class="d-flex gap-2">
    <button class="btn btn-sm btn-success" id="markAllNotificationsRead">
      <i class="fas fa-check-double me-1"></i>Tout marquer comme lues
    </button>
  </div>
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
            <th class="text-end">Actions</th>
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
                  <span class="badge bg-success" data-status>{{ 'Oui' }}</span>
                @else
                  <span class="badge bg-secondary" data-status>{{ 'Non' }}</span>
                @endif
              </td>
              <td class="text-end">
                @if(!$n->lu)
                  <button class="btn btn-sm btn-outline-success" data-mark-read data-id="{{ $n->id }}" data-url="{{ route('admin.notifications.mark-read', $n->id) }}">
                    <i class="fas fa-check me-1"></i>Marquer comme lue
                  </button>
                @else
                  <button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="fas fa-check-double me-1"></i>Déjà lue
                  </button>
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

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Bouton tout marquer comme lues
    const btn = document.getElementById('markAllNotificationsRead');
    if (btn) {
      btn.addEventListener('click', async function(e) {
        e.preventDefault();
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
        try {
          await fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
        } catch (err) {}
        location.reload();
      });
    }

    // Action par ligne: marquer comme lue
    document.querySelectorAll('[data-mark-read]').forEach((button) => {
      button.addEventListener('click', async function(e) {
        e.preventDefault();
        const row = this.closest('tr');
        const status = row.querySelector('[data-status]');
        const url = this.dataset.url;
        const original = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>...';
        try {
          await fetch(url, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          if (status) {
            status.className = 'badge bg-success';
            status.textContent = 'Oui';
          }
          this.className = 'btn btn-sm btn-outline-secondary';
          this.innerHTML = '<i class="fas fa-check-double me-1"></i>Déjà lue';
        } catch (err) {
          this.disabled = false;
          this.innerHTML = original;
        }
      });
    });
      e.preventDefault();
      const original = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Traitement...';
      try {
        const res = await fetch('/api/notifications/mark-all-read', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
      } catch (err) {}
      location.reload();
    });
  });
</script>
@endsection
