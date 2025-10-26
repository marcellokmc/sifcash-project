@extends('backoffice.layouts.app')

@section('title', 'Nouvel Adhérent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Créer un Nouvel Adhérent</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.adherents.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Informations Personnelles</h6>
                                
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Compte Utilisateur *</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" name="user_id" required>
                                        <option value="">Choisir un utilisateur...</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Sélectionnez l'utilisateur qui sera associé à cet adhérent
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance *</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="lieu_naissance" class="form-label">Lieu de Naissance *</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance') }}" required>
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="situation_famille" class="form-label">Situation Familiale *</label>
                                    <select class="form-select @error('situation_famille') is-invalid @enderror" 
                                            id="situation_famille" name="situation_famille" required>
                                        <option value="">Choisir...</option>
                                        <option value="marié" {{ old('situation_famille') == 'marié' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="celibataire" {{ old('situation_famille') == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="veuf/veuve" {{ old('situation_famille') == 'veuf/veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                                        <option value="divorcé" {{ old('situation_famille') == 'divorcé' ? 'selected' : '' }}>Divorcé(e)</option>
                                    </select>
                                    @error('situation_famille')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession" class="form-label">Profession *</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                           id="profession" name="profession" value="{{ old('profession') }}" required>
                                    @error('profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Coordonnées</h6>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone *</label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telephone_secondaire" class="form-label">Téléphone Secondaire</label>
                                    <input type="text" class="form-control @error('telephone_secondaire') is-invalid @enderror" 
                                           id="telephone_secondaire" name="telephone_secondaire" value="{{ old('telephone_secondaire') }}">
                                    @error('telephone_secondaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse *</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" name="adresse" rows="3" required>{{ old('adresse') }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="residence" class="form-label">Résidence *</label>
                                    <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                           id="residence" name="residence" value="{{ old('residence') }}" required>
                                    @error('residence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="secteur_numero" class="form-label">Secteur N°</label>
                                    <input type="text" class="form-control @error('secteur_numero') is-invalid @enderror" 
                                           id="secteur_numero" name="secteur_numero" value="{{ old('secteur_numero') }}" 
                                           placeholder="Exemple: Secteur 15, Zone 3, etc.">
                                    <div class="form-text">Indiquez le secteur de résidence (facultatif)</div>
                                    @error('secteur_numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession_exercee" class="form-label">Profession exercée</label>
                                    <input type="text" class="form-control @error('profession_exercee') is-invalid @enderror" 
                                           id="profession_exercee" name="profession_exercee" value="{{ old('profession_exercee') }}" 
                                           placeholder="Exemple: Enseignant, Commerçant, Agriculteur, etc.">
                                    <div class="form-text">Spécifiez la profession actuellement exercée (facultatif)</div>
                                    @error('profession_exercee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact d'Urgence (Principal)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control @error('contact_urgence_nom') is-invalid @enderror" 
                                           id="contact_urgence_nom" name="contact_urgence_nom" 
                                           value="{{ old('contact_urgence_nom') }}" 
                                           placeholder="Nom et prénoms du contact" required>
                                    @error('contact_urgence_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_lien" class="form-label">Lien avec l'adhérent *</label>
                                    <select class="form-select @error('contact_urgence_lien') is-invalid @enderror" 
                                            id="contact_urgence_lien" name="contact_urgence_lien" required>
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" {{ old('contact_urgence_lien') == 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                                        <option value="parent" {{ old('contact_urgence_lien') == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="enfant" {{ old('contact_urgence_lien') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                                        <option value="frere" {{ old('contact_urgence_lien') == 'frere' ? 'selected' : '' }}>Frère</option>
                                        <option value="soeur" {{ old('contact_urgence_lien') == 'soeur' ? 'selected' : '' }}>Sœur</option>
                                        <option value="ami" {{ old('contact_urgence_lien') == 'ami' ? 'selected' : '' }}>Ami(e)</option>
                                        <option value="collegue" {{ old('contact_urgence_lien') == 'collegue' ? 'selected' : '' }}>Collègue</option>
                                        <option value="autre" {{ old('contact_urgence_lien') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('contact_urgence_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone d'Urgence *</label>
                                    <input type="text" class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="{{ old('contact_urgence_telephone') }}" required>
                                    @error('contact_urgence_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact d'Urgence (Secondaire)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_nom" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_nom') is-invalid @enderror" 
                                           id="contact_urgence_2_nom" name="contact_urgence_2_nom" 
                                           value="{{ old('contact_urgence_2_nom') }}" 
                                           placeholder="Nom et prénoms du contact">
                                    @error('contact_urgence_2_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_lien" class="form-label">Lien avec l'adhérent</label>
                                    <select class="form-select @error('contact_urgence_2_lien') is-invalid @enderror" 
                                            id="contact_urgence_2_lien" name="contact_urgence_2_lien">
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" {{ old('contact_urgence_2_lien') == 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                                        <option value="parent" {{ old('contact_urgence_2_lien') == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="enfant" {{ old('contact_urgence_2_lien') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                                        <option value="frere" {{ old('contact_urgence_2_lien') == 'frere' ? 'selected' : '' }}>Frère</option>
                                        <option value="soeur" {{ old('contact_urgence_2_lien') == 'soeur' ? 'selected' : '' }}>Sœur</option>
                                        <option value="ami" {{ old('contact_urgence_2_lien') == 'ami' ? 'selected' : '' }}>Ami(e)</option>
                                        <option value="collegue" {{ old('contact_urgence_2_lien') == 'collegue' ? 'selected' : '' }}>Collègue</option>
                                        <option value="autre" {{ old('contact_urgence_2_lien') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('contact_urgence_2_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_telephone" class="form-label">Téléphone Secondaire d'Urgence</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_telephone') is-invalid @enderror" 
                                           id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                           value="{{ old('contact_urgence_2_telephone') }}">
                                    @error('contact_urgence_2_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Le numéro de membre sera généré automatiquement après la création.
                            Le statut du compte sera défini sur "En attente" par défaut.
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer l'adhérent
                            </button>
                            <a href="{{ route('admin.adherents.index') }}" class="btn btn-secondary">
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