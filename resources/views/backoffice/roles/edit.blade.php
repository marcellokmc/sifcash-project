@extends('backoffice.layouts.app')

@section('title', 'Modifier Rôle')
@section('page-title', 'Modifier le Rôle')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Modifier les Informations</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="1">{{ old('description', $role->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Permissions associées</label>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    La modification des permissions affectera immédiatement tous les utilisateurs ayant ce rôle.
                </div>
                
                <!-- Actions globales -->
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAll">
                        <i class="fas fa-check-square me-1"></i>Tout sélectionner
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAll">
                        <i class="fas fa-square me-1"></i>Tout désélectionner
                    </button>
                </div>
                
                @php
                    $grouped = $permissions->groupBy(function($item) {
                        $parts = explode('_', $item->name, 2);
                        return $parts[1] ?? 'autres';
                    });
                    
                    $colors = [
                        'users' => 'primary',
                        'adherents' => 'success',
                        'payments' => 'warning',
                        'credits' => 'info',
                        'withdrawals' => 'danger',
                        'agences' => 'secondary',
                        'roles' => 'dark',
                        'permissions' => 'primary',
                        'documents' => 'info',
                        'plans' => 'success',
                    ];
                    
                    $icons = [
                        'users' => 'fa-users',
                        'adherents' => 'fa-user-friends',
                        'payments' => 'fa-money-bill-wave',
                        'credits' => 'fa-credit-card',
                        'withdrawals' => 'fa-hand-holding-usd',
                        'agences' => 'fa-building',
                        'roles' => 'fa-user-shield',
                        'permissions' => 'fa-key',
                        'documents' => 'fa-file-alt',
                        'plans' => 'fa-clipboard-list',
                        'epargnes' => 'fa-piggy-bank',
                        'adhesions' => 'fa-handshake',
                        'ayants_droits' => 'fa-users-cog',
                        'echeances' => 'fa-calendar-check',
                        'penalties' => 'fa-exclamation-triangle',
                        'audit' => 'fa-history',
                        'logs' => 'fa-clipboard-list',
                        'notifications' => 'fa-bell',
                        'reports' => 'fa-chart-bar',
                        'dashboard' => 'fa-tachometer-alt',
                        'settings' => 'fa-cog',
                    ];
                @endphp

                @foreach($grouped->sortKeys() as $category => $categoryPermissions)
                <div class="card mb-3">
                    <div class="card-header bg-{{ $colors[$category] ?? 'secondary' }} {{ in_array($category, ['payments']) ? 'text-dark' : 'text-white' }}">
                        <h6 class="mb-0">
                            <i class="fas {{ $icons[$category] ?? 'fa-folder' }} me-2"></i>
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                            <span class="badge bg-light text-dark ms-2">{{ $categoryPermissions->count() }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($categoryPermissions as $permission)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        <code>{{ $permission->name }}</code>
                                        @if($permission->description)
                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                
                @error('permissions')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <div>
                    <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-info">
                        <i class="fas fa-eye me-1"></i>Voir
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection/désélection de toutes les permissions
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        });
    }
    
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    }
});
</script>
@endpush
@endsection
