@extends('backoffice.layouts.app')

@section('title', 'Nouvelle Permission')
@section('page-title', 'Créer une Nouvelle Permission')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informations Permission</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la permission *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="ex: view_users, create_credits" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Utilisez le format: action_resource (ex: view_users, edit_payments)
                        </small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="1">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Actions disponibles :</label>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="viewCheck">
                            <label class="form-check-label" for="viewCheck">
                                <code>view_</code> - Visualisation
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="createCheck">
                            <label class="form-check-label" for="createCheck">
                                <code>create_</code> - Création
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editCheck">
                            <label class="form-check-label" for="editCheck">
                                <code>edit_</code> - Modification
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="deleteCheck">
                            <label class="form-check-label" for="deleteCheck">
                                <code>delete_</code> - Suppression
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Créer la permission
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const checks = {
            view: document.getElementById('viewCheck'),
            create: document.getElementById('createCheck'),
            edit: document.getElementById('editCheck'),
            delete: document.getElementById('deleteCheck')
        };

        // Ajouter un préfixe lorsqu'une case est cochée
        Object.keys(checks).forEach(action => {
            checks[action].addEventListener('change', function() {
                if (this.checked) {
                    nameInput.value = action + '_';
                    // Décocher les autres cases
                    Object.keys(checks).forEach(otherAction => {
                        if (otherAction !== action) {
                            checks[otherAction].checked = false;
                        }
                    });
                }
            });
        });

        // Si l'utilisateur tape manuellement, décocher les cases
        nameInput.addEventListener('input', function() {
            Object.keys(checks).forEach(action => {
                checks[action].checked = false;
            });
        });
    });
</script>
@endpush
@endsection