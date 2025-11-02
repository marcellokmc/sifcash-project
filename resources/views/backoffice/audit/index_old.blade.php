@extends('backoffice.layouts.app')

@section('title', 'Journal d\'audit')

@section('content')
<style>
.audit-item {
    transition: all 0.2s;
    border-left: 4px solid transparent;
}
.audit-item:hover {
    background-color: #f8f9fa;
    border-left-color: #0d6efd;
    transform: translateX(5px);
}
.badge-category {
    min-width: 100px;
    font-size: 0.85rem;
}
</style>
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1"><i class="fas fa-clipboard-list"></i> Journal d'audit</h3>
                    <p class="text-muted mb-0">Traçabilité complète des actions du système</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.audit.stats') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> Statistiques
                    </a>
                    <a href="{{ route('admin.audit.export', request()->query()) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Exporter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Total</h6>
                            <h4 class="mb-0">{{ number_format($audits->total()) }}</h4>
                        </div>
                        <i class="fas fa-database fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Paiements</h6>
                            <h4 class="mb-0">{{ \App\Models\Audit::where('action_category', 'paiement')->count() }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Retraits</h6>
                            <h4 class="mb-0">{{ \App\Models\Audit::where('action_category', 'retrait')->count() }}</h4>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Adhésions</h6>
                            <h4 class="mb-0">{{ \App\Models\Audit::where('action_category', 'adhesion')->count() }}</h4>
                        </div>
                        <i class="fas fa-id-card fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-filter"></i> Filtres de recherche</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.audit.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-tag"></i> Catégorie</label>
                        <select name="action_category" class="form-select">
                            <option value="">🔍 Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('action_category') == $category ? 'selected' : '' }}>
                                    @switch($category)
                                        @case('paiement') 💰 @break
                                        @case('retrait') 💵 @break
                                        @case('adhesion') 📋 @break
                                        @case('adherent') 👤 @break
                                        @case('affectation') 👥 @break
                                        @case('validation') ✅ @break
                                        @default 📌 @break
                                    @endswitch
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-user"></i> Auteur</label>
                        <select name="user_id" class="form-select">
                            <option value="">👤 Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-bullseye"></i> Cible</label>
                        <select name="target_user_id" class="form-select">
                            <option value="">🎯 Tous</option>
                            @foreach($targetUsers as $user)
                                <option value="{{ $user->id }}" {{ request('target_user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-cogs"></i> Action</label>
                        <select name="action" class="form-select">
                            <option value="">⚙️ Toutes les actions</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>➕ Création</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>✏️ Modification</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>🗑️ Suppression</option>
                            <option value="validated" {{ request('action') == 'validated' ? 'selected' : '' }}>✅ Validation</option>
                            <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>👍 Approbation</option>
                            <option value="rejected" {{ request('action') == 'rejected' ? 'selected' : '' }}>❌ Rejet</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Période</label>
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Début">
                            <span class="input-group-text">→</span>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Fin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold"><i class="fas fa-search"></i> Recherche</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Rechercher dans les descriptions...">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 me-2">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des audits -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-list"></i> Résultats ({{ $audits->total() }} audit{{ $audits->total() > 1 ? 's' : '' }})</h6>
                @if(request()->hasAny(['user_id', 'action_category', 'action', 'date_from', 'date_to', 'search']))
                    <span class="badge bg-info">Filtres actifs</span>
                @endif
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 120px;"><i class="fas fa-clock"></i> Date</th>
                        <th style="width: 100px;"><i class="fas fa-tag"></i> Catégorie</th>
                        <th style="width: 150px;"><i class="fas fa-user"></i> Auteur</th>
                        <th style="width: 120px;"><i class="fas fa-bolt"></i> Action</th>
                        <th><i class="fas fa-align-left"></i> Description</th>
                        <th style="width: 100px;"><i class="fas fa-cube"></i> Objet</th>
                        <th style="width: 120px;"><i class="fas fa-bullseye"></i> Cible</th>
                        <th style="width: 80px;" class="text-center"><i class="fas fa-eye"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td class="text-nowrap small">
                                <i class="fas fa-calendar text-muted"></i>
                                {{ $audit->created_at->format('d/m/Y') }}<br>
                                <small class="text-muted"><i class="fas fa-clock"></i> {{ $audit->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($audit->action_category)
                                    @php
                                        $badgeClass = match($audit->action_category) {
                                            'paiement' => 'success',
                                            'retrait' => 'warning',
                                            'adhesion' => 'info',
                                            'adherent' => 'primary',
                                            'affectation' => 'secondary',
                                            'validation' => 'success',
                                            default => 'secondary'
                                        };
                                        $icon = match($audit->action_category) {
                                            'paiement' => 'money-bill-wave',
                                            'retrait' => 'hand-holding-usd',
                                            'adhesion' => 'id-card',
                                            'adherent' => 'user',
                                            'affectation' => 'users',
                                            'validation' => 'check-circle',
                                            default => 'tag'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">
                                        <i class="fas fa-{{ $icon }}"></i> {{ ucfirst($audit->action_category) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $audit->user->name ?? 'Système' }}</strong>
                                        <br><small class="text-muted">{{ $audit->user->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @switch($audit->action)
                                    @case('created')
                                        <span class="badge bg-success"><i class="fas fa-plus-circle"></i> Création</span>
                                        @break
                                    @case('updated')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-edit"></i> Modification</span>
                                        @break
                                    @case('deleted')
                                        <span class="badge bg-danger"><i class="fas fa-trash"></i> Suppression</span>
                                        @break
                                    @case('validated')
                                        <span class="badge bg-info"><i class="fas fa-check"></i> Validation</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-primary"><i class="fas fa-thumbs-up"></i> Approbation</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-dark"><i class="fas fa-times-circle"></i> Rejet</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $audit->action }}</span>
                                @endswitch
                            </td>
                            <td>
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
                                @if($desc)
                                    <i class="fas fa-comment-alt text-muted"></i> {{ \Str::limit($desc, 50) }}
                                @else
                                    <span class="text-muted fst-italic"><i class="fas fa-minus"></i> Aucune description</span>
                                @endif
                            </td>
                            <td class="small">
                                @if($audit->model_type && $audit->model_type != 'n/a')
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-cube"></i> {{ class_basename($audit->model_type) }}
                                    </span>
                                    @if($audit->model_id && $audit->model_id != 0)
                                        <br><small class="text-muted">ID: {{ $audit->model_id }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($audit->targetUser)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-bullseye text-info me-1"></i>
                                        <span>{{ $audit->targetUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#auditModal{{ $audit->id }}" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="auditModal{{ $audit->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails de l'audit #{{ $audit->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <h6><i class="fas fa-info-circle"></i> Informations générales</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Utilisateur</th>
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
                                                    <th>Utilisateur cible</th>
                                                    <td>{{ $audit->targetUser->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td>{{ $audit->description ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="mb-3">
                                            <h6><i class="fas fa-network-wired"></i> Informations techniques</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Adresse IP</th>
                                                    <td><code>{{ $audit->ip }}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>URL</th>
                                                    <td><code class="small">{{ $audit->url ?? '-' }}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>User Agent</th>
                                                    <td><code class="small">{{ $audit->user_agent ?? '-' }}</code></td>
                                                </tr>
                                                <tr>
                                                    <th>Modèle</th>
                                                    <td>{{ $audit->model_type }} (ID: {{ $audit->model_id }})</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><i class="fas fa-history"></i> Anciennes valeurs</h6>
                                                <pre class="bg-light p-2 small" style="max-height: 300px; overflow-y: auto;">{{ json_encode($audit->ancienne_valeur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? 'Aucune' }}</pre>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="fas fa-edit"></i> Nouvelles valeurs</h6>
                                                <pre class="bg-light p-2 small" style="max-height: 300px; overflow-y: auto;">{{ json_encode($audit->nouvelle_valeur, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?? 'Aucune' }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-search fa-3x mb-3 opacity-50"></i>
                                    <h5>Aucun audit trouvé</h5>
                                    <p class="mb-0">Essayez de modifier vos filtres de recherche</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($audits->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de {{ $audits->firstItem() }} à {{ $audits->lastItem() }} sur {{ $audits->total() }} résultats
                    </div>
                    <div>
                        {{ $audits->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Personnalisation du tableau */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.avatar {
    transition: transform 0.2s;
}

.avatar:hover {
    transform: scale(1.1);
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.3s ease-in;
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive table {
        font-size: 0.875rem;
    }
}
</style>
@endsection
