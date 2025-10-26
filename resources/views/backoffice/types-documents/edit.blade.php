@extends('backoffice.layouts.app')

@section('title', 'Modifier le Type de Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier : {{ $typesDocument->nom }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.types-documents.update', $typesDocument) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du document *</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $typesDocument->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $typesDocument->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="recto_requis" name="recto_requis" value="1" 
                                               {{ old('recto_requis', $typesDocument->recto_requis) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="recto_requis">
                                            Recto requis
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="verso_requis" name="verso_requis" value="1" 
                                               {{ old('verso_requis', $typesDocument->verso_requis) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="verso_requis">
                                            Verso requis
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="actif" name="actif" value="1" 
                                       {{ old('actif', $typesDocument->actif) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Type actif
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <small>
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Attention :</strong> La modification des champs requis (recto/verso) peut affecter 
                                les documents existants. Les adhérents devront peut-être re-uploader leurs documents.
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('admin.types-documents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection