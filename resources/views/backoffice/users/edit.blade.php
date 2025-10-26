@extends('backoffice.layouts.app')

@section('title', 'Modifier Utilisateur')
@section('page-title', 'Modifier l\'Utilisateur')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Modifier les Informations</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle *</label>
                       <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            <option value="">Sélectionner un rôle</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    {{ (old('role_id', $currentRole->id ?? '') == $role->id) ? 'selected' : '' }}>
                                    {{ $role->name }} - {{ $role->description }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Laisser vide pour ne pas changer">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" 
                               name="password_confirmation" placeholder="Confirmer le nouveau mot de passe">
                    </div>
                </div>
            </div>

            <div class="row" id="agent-fields">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="matricule" class="form-label">Matricule</label>
                        <input type="text" class="form-control @error('matricule') is-invalid @enderror" 
                               id="matricule" name="matricule" value="{{ old('matricule', $user->matricule) }}">
                        @error('matricule')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_embauche" class="form-label">Date d'embauche</label>
                        <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                               id="date_embauche" name="date_embauche" value="{{ old('date_embauche', $user->date_embauche ? $user->date_embauche->format('Y-m-d') : '') }}">
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
                                <option value="{{ $agence->id }}" {{ old('agence_id', $user->agence_id) == $agence->id ? 'selected' : '' }}>
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
                <div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info">
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