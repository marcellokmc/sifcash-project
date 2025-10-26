@extends('backoffice.layouts.app')

@section('title', 'Journal d\'audit')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Journal d'audit</h5>
            <div class="d-flex">
                <form action="{{ route('admin.audit.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Utilisateur</span>
                        <select name="user_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Action</span>
                        <select name="action" class="form-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Création</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Modification</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Suppression</option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Type</span>
                        <select name="model_type" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            @foreach($modelTypes as $type)
                                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                    {{ class_basename($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Du</span>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" onchange="this.form.submit()">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Au</span>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" onchange="this.form.submit()">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Modèle</th>
                        <th>ID</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $audit->user->name ?? 'Système' }}</td>
                            <td>
                                @switch($audit->event)
                                    @case('created')
                                        <span class="badge bg-success">Création</span>
                                        @break
                                    @case('updated')
                                        <span class="badge bg-warning text-dark">Modification</span>
                                        @break
                                    @case('deleted')
                                        <span class="badge bg-danger">Suppression</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $audit->event }}</span>
                                @endswitch
                            </td>
                            <td>{{ class_basename($audit->auditable_type) }}</td>
                            <td>{{ $audit->auditable_id }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#auditModal{{ $audit->id }}">
                                    <i class="fas fa-eye"></i> Voir
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Anciennes valeurs</h6>
                                                <pre class="bg-light p-2">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Nouvelles valeurs</h6>
                                                <pre class="bg-light p-2">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <h6>URL</h6>
                                            <code>{{ $audit->url }}</code>
                                        </div>
                                        <div class="mt-3">
                                            <h6>Adresse IP</h6>
                                            <code>{{ $audit->ip_address }}</code>
                                        </div>
                                        <div class="mt-3">
                                            <h6>User Agent</h6>
                                            <code>{{ $audit->user_agent }}</code>
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
                            <td colspan="6" class="text-center">Aucun enregistrement d'audit trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($audits->hasPages())
            <div class="card-footer">
                {{ $audits->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
