@extends('backoffice.layouts.app')

@section('title', 'Nouvel Utilisateur')
@section('page-title', 'Créer un Nouvel Utilisateur')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Informations Utilisateur</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
              <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Rôle *</label>
                        <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }} - {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                        <input type="password" class="form-control" id="password_confirmation" 
                               name="password_confirmation" required>
                    </div>
                </div>
            </div>

            <div class="row" id="agent-fields">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="matricule" class="form-label">Matricule</label>
                        <input type="text" class="form-control @error('matricule') is-invalid @enderror" 
                               id="matricule" name="matricule" value="{{ old('matricule') }}">
                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_embauche" class="form-label">Date d'embauche</label>
                        <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                               id="date_embauche" name="date_embauche" value="{{ old('date_embauche') }}">
                        @error('date_embauche')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row" id="agence-field">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="agence_id" class="form-label">Agence</label>
                        <select class="form-control @error('agence_id') is-invalid @enderror" id="agence_id" name="agence_id">
                            <option value="">Sélectionner une agence</option>
                            @foreach($agences as $agence)
                                <option value="{{ $agence->id }}" {{ old('agence_id') == $agence->id ? 'selected' : '' }}>
                                    {{ $agence->nom }} ({{ $agence->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('agence_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Créer l'utilisateur
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const agentFields = document.getElementById('agent-fields');
        const agenceField = document.getElementById('agence-field');

        function toggleFields() {
            const role = roleSelect.value;
            
            if (role === 'admin') {
                agentFields.style.display = 'none';
                agenceField.style.display = 'none';
            } else if (role === 'agent') {
                agentFields.style.display = 'flex';
                agenceField.style.display = 'flex';
            } else {
                agentFields.style.display = 'none';
                agenceField.style.display = 'flex';
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call
    });
</script>
@endpush
@endsection