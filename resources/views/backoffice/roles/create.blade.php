@extends('backoffice.layouts.app')

@section('title', 'Nouveau Rôle')
@section('page-title', 'Créer un Nouveau Rôle')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informations du Rôle</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom du rôle *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="ex: admin, agent, chef_service" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Le nom doit être unique et en minuscules sans espaces
                        </small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="1" 
                                  placeholder="Description du rôle..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Permissions associées</label>
                
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 fa-lg"></i>
                    <div>
                        <strong>Information :</strong> Sélectionnez les permissions que ce rôle pourra exercer.
                        Les permissions sont regroupées par module. <strong>{{ $permissions->count() }} permissions disponibles</strong>.
                    </div>
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
                                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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

                <!-- Actions globales -->
                <div class="text-center mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAll">
                        <i class="fas fa-check-square me-1"></i>Tout sélectionner
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAll">
                        <i class="fas fa-square me-1"></i>Tout désélectionner
                    </button>
                </div>
                
                @error('permissions')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                </a>
                
                <div>
                    <button type="reset" class="btn btn-outline-danger me-2">
                        <i class="fas fa-undo me-1"></i>Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Créer le rôle
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
    document.getElementById('selectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
    });
    
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
    });
    
    // Validation en temps réel du nom du rôle
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('blur', function() {
        const name = this.value.trim();
        if (name !== '') {
            // Vérifier que le nom est en minuscules sans espaces
            if (name !== name.toLowerCase() || name.includes(' ')) {
                alert('Le nom du rôle doit être en minuscules sans espaces. Ex: "chef_service"');
                this.value = name.toLowerCase().replace(/\s+/g, '_');
            }
        }
    });
});
</script>
@endpush
@endsection