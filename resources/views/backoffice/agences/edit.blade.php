@extends('backoffice.layouts.app')

@section('title', 'Modifier Agence')
@section('page-title', 'Modifier l\'Agence')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Modifier les Informations</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.agences.update', $agence) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code Agence *</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $agence->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de l'agence *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom', $agence->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="province" class="form-label">Province *</label>
                        <input type="text" class="form-control @error('province') is-invalid @enderror" 
                               id="province" name="province" value="{{ old('province', $agence->province) }}" required>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="departement" class="form-label">Département</label>
                        <input type="text" class="form-control @error('departement') is-invalid @enderror" 
                               id="departement" name="departement" value="{{ old('departement', $agence->departement) }}">
                        @error('departement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse complète *</label>
                <textarea class="form-control @error('adresse') is-invalid @enderror" 
                          id="adresse" name="adresse" rows="3" required>{{ old('adresse', $agence->adresse) }}</textarea>
                @error('adresse')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact" class="form-label">Contact *</label>
                        <input type="text" class="form-control @error('contact') is-invalid @enderror" 
                               id="contact" name="contact" value="{{ old('contact', $agence->contact) }}" required>
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="active" class="form-label">Statut</label>
                        <select class="form-control @error('active') is-invalid @enderror" id="active" name="active">
                            <option value="1" {{ old('active', $agence->active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('active', $agence->active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.agences.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <div>
                    <a href="{{ route('admin.agences.show', $agence) }}" class="btn btn-info">
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
@endsection