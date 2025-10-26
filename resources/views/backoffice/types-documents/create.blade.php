@extends('backoffice.layouts.app')

@section('title', 'Nouveau Type de Document')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Créer un nouveau type de document</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.types-documents.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du document *</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom') }}" 
                                   placeholder="Ex: CNIB, Passeport, Permis de conduire..." required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Description détaillée du document...">{{ old('description') }}</textarea>
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
                                               {{ old('recto_requis', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="recto_requis">
                                            Recto requis
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Le recto de ce document est-il obligatoire ?
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="verso_requis" name="verso_requis" value="1" 
                                               {{ old('verso_requis') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="verso_requis">
                                            Verso requis
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Le verso de ce document est-il obligatoire ?
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="actif" name="actif" value="1" 
                                       {{ old('actif', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">
                                    Type actif
                                </label>
                            </div>
                            <div class="form-text">
                                Les documents de ce type pourront-ils être uploadés ?
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Conseil :</strong> Pensez à bien configurer les champs requis (recto/verso) 
                                selon la nature du document. Par exemple, une CNIB nécessite généralement recto ET verso.
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer le type de document
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