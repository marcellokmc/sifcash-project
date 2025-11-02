@extends('backoffice.layouts.app')

@section('title', 'Journal d\'audit')

@section('content')
<style>
.audit-item {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}
.audit-item:hover {
    background-color: #f8f9fa;
    border-left-color: #0d6efd;
    transform: translateX(3px);
}
.badge-category {
    min-width: 90px;
    display: inline-block;
    text-align: center;
}
</style>

<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Journal d'audit</h2>
            <p class="text-muted mb-0">Historique des actions sur le système</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.audit.stats') }}" class="btn btn-outline-primary">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
            <a href="{{ route('admin.audit.export', request()->query()) }}" class="btn btn-outline-success">
                <i class="fas fa-download"></i> Exporter
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.audit.index') }}" method="GET">
                <div class="row g-3">
                    <!-- Catégorie -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Catégorie</label>
                        <select name="action_category" class="form-select form-select-sm">
                            <option value="">Toutes</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('action_category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Utilisateur -->
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Utilisateur</label>
                        <select name="user_id" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Action</label>
                        <select name="action" class="form-select form-select-sm">
                            <option value="">Toutes</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Création</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Modification</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Suppression</option>
                            <option value="validated" {{ request('action') == 'validated' ? 'selected' : '' }}>Validation</option>
                            <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approbation</option>
                            <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>Rejet</option>
                        </select>
                    </div>

                    <!-- Dates -->
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Du</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">Au</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <!-- Recherche -->
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>

                    <!-- Boutons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">Filtrer</button>
                        @if(request()->hasAny(['user_id', 'action_category', 'action', 'date_from', 'date_to', 'search']))
                            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-1">Réinitialiser</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des audits -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ $audits->total() }} résultat(s)</h6>
                @if(request()->hasAny(['user_id', 'action_category', 'action', 'date_from', 'date_to', 'search']))
                    <span class="badge bg-primary">Filtres actifs</span>
                @endif
            </div>
        </div>
        <div class="list-group list-group-flush">
            @forelse($audits as $audit)
                <div class="list-group-item audit-item py-3">
                    <div class="row align-items-center">
                        <!-- Date et heure -->
                        <div class="col-md-2">
                            <div class="text-muted small">
                                <i class="fas fa-calendar-day"></i> {{ $audit->created_at->format('d/m/Y') }}
                                <br>
                                <i class="fas fa-clock"></i> {{ $audit->created_at->format('H:i') }}
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="col-md-2">
                            @php
                                $badgeClass = match($audit->action_category ?? 'autre') {
                                    'paiement' => 'success',
                                    'retrait' => 'warning',
                                    'adhesion' => 'info',
                                    'adherent' => 'primary',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} badge-category">
                                {{ ucfirst($audit->action_category ?? 'Autre') }}
                            </span>
                        </div>

                        <!-- Utilisateur et Action -->
                        <div class="col-md-4">
                            <div class="fw-bold">{{ $audit->user->name ?? 'Système' }}</div>
                            <div class="text-muted small">
                                @switch($audit->action)
                                    @case('created') <i class="fas fa-plus-circle text-success"></i> Création @break
                                    @case('updated') <i class="fas fa-edit text-warning"></i> Modification @break
                                    @case('deleted') <i class="fas fa-trash text-danger"></i> Suppression @break
                                    @case('validated') <i class="fas fa-check text-info"></i> Validation @break
                                    @case('approved') <i class="fas fa-thumbs-up text-primary"></i> Approbation @break
                                    @case('rejected') <i class="fas fa-times text-danger"></i> Rejet @break
                                    @default {{ $audit->action }} @break
                                @endswitch
                                
                                @if($audit->model_type && $audit->model_type != 'n/a')
                                    • <strong>{{ class_basename($audit->model_type) }}</strong>
                                    @if($audit->model_id && $audit->model_id != 0)
                                        #{{ $audit->model_id }}
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-3">
                            @php
                                $desc = $audit->description;
                                if ($desc && $desc != 'null') {
                                    if (str_contains($desc, '@')) {
                                        $parts = explode('@', $desc);
                                        $desc = end($parts);
                                    }
                                    if (str_contains($desc, 'Controller') || str_contains($desc, '\\')) {
                                        $desc = null;
                                    }
                                }
                            @endphp
                            <div class="small text-muted">
                                {{ $desc ? Str::limit($desc, 50) : '-' }}
                            </div>
                            @if($audit->targetUser)
                                <div class="small">
                                    <i class="fas fa-arrow-right text-muted"></i> <strong>{{ $audit->targetUser->name }}</strong>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#auditModal{{ $audit->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal détails -->
                <div class="modal fade" id="auditModal{{ $audit->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Détails de l'audit #{{ $audit->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="30%">Utilisateur</th>
                                        <td>{{ $audit->user->name ?? 'Système' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catégorie</th>
                                        <td>{{ ucfirst($audit->action_category ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td>{{ $audit->action }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modèle</th>
                                        <td>{{ $audit->model_type }} (ID: {{ $audit->model_id }})</td>
                                    </tr>
                                    <tr>
                                        <th>Utilisateur cible</th>
                                        <td>{{ $audit->targetUser->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Adresse IP</th>
                                        <td><code>{{ $audit->ip }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>URL</th>
                                        <td><code class="small">{{ $audit->url ?? '-' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $audit->description ?? '-' }}</td>
                                    </tr>
                                </table>

                                @if($audit->ancienne_valeur || $audit->nouvelle_valeur)
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <h6>Anciennes valeurs</h6>
                                            <pre class="bg-light p-2 small">{{ json_encode($audit->ancienne_valeur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Aucune' }}</pre>
                                        </div>
                                        <div class="col-6">
                                            <h6>Nouvelles valeurs</h6>
                                            <pre class="bg-light p-2 small">{{ json_encode($audit->nouvelle_valeur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: 'Aucune' }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center py-5 text-muted">
                    <i class="fas fa-search fa-3x mb-3 opacity-50"></i>
                    <h5>Aucun audit trouvé</h5>
                    <p>Modifiez vos critères de recherche</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($audits->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        {{ $audits->firstItem() }} - {{ $audits->lastItem() }} sur {{ $audits->total() }}
                    </div>
                    {{ $audits->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
