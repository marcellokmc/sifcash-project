@extends('layouts.adherent-modern')

@section('title', 'Ajouter un Ayant Droit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter un Ayant Droit</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('adherent.ayants-droit.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}">
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lien_parente" class="form-label">Lien de Parenté *</label>
                                    <input type="text" class="form-control @error('lien_parente') is-invalid @enderror" 
                                           id="lien_parente" name="lien_parente" value="{{ old('lien_parente') }}" 
                                           placeholder="Ex: Conjoint, Enfant, Parent..." required>
                                    @error('lien_parente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control @error('contact') is-invalid @enderror" 
                                           id="contact" name="contact" value="{{ old('contact') }}">
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type_beneficiaire" class="form-label">Type de Bénéficiaire *</label>
                                    <select class="form-select @error('type_beneficiaire') is-invalid @enderror" 
                                            id="type_beneficiaire" name="type_beneficiaire" required>
                                        <option value="">Choisir...</option>
                                        <option value="vie" {{ old('type_beneficiaire') == 'vie' ? 'selected' : '' }}>Bénéficiaire Vie</option>
                                        <option value="deces" {{ old('type_beneficiaire') == 'deces' ? 'selected' : '' }}>Bénéficiaire Décès</option>
                                    </select>
                                    @error('type_beneficiaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Important :</strong> L'ayant droit sera soumis à validation par un agent. 
                                Vous ne pourrez pas le modifier une fois validé sans réapprobation.
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                            <a href="{{ route('adherent.ayants-droit.index') }}" class="btn btn-secondary">
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
