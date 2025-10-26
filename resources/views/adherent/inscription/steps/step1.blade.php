@extends('layouts.adherent-modern')

@section('title', 'Inscription - Étape 1')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 1/3</h4>
                    <p class="text-muted mb-0">Informations personnelles</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 33%;" 
                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-primary">Profil</small>
                        <small class="text-muted">Ayants Droit</small>
                        <small class="text-muted">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('adherent.inscription.profile') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Informations Personnelles</h6>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $adherent->nom ?? '') }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $adherent->prenom ?? '') }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance *</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" 
                                           value="{{ old('date_naissance', $adherent->date_naissance ?? '') }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="lieu_naissance" class="form-label">Lieu de Naissance *</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" name="lieu_naissance" 
                                           value="{{ old('lieu_naissance', $adherent->lieu_naissance ?? '') }}" required>
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="situation_famille" class="form-label">Situation Familiale *</label>
                                    <select class="form-select @error('situation_famille') is-invalid @enderror" 
                                            id="situation_famille" name="situation_famille" required>
                                        <option value="">Choisir...</option>
                                        <option value="marié" {{ old('situation_famille', $adherent->situation_famille ?? '') == 'marié' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="celibataire" {{ old('situation_famille', $adherent->situation_famille ?? '') == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="veuf/veuve" {{ old('situation_famille', $adherent->situation_famille ?? '') == 'veuf/veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                                        <option value="divorcé" {{ old('situation_famille', $adherent->situation_famille ?? '') == 'divorcé' ? 'selected' : '' }}>Divorcé(e)</option>
                                    </select>
                                    @error('situation_famille')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Coordonnées</h6>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone *</label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone', $adherent->telephone ?? '') }}" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telephone_secondaire" class="form-label">Téléphone Secondaire</label>
                                    <input type="text" class="form-control @error('telephone_secondaire') is-invalid @enderror" 
                                           id="telephone_secondaire" name="telephone_secondaire" 
                                           value="{{ old('telephone_secondaire', $adherent->telephone_secondaire ?? '') }}">
                                    @error('telephone_secondaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $adherent->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse *</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" name="adresse" rows="3" required>{{ old('adresse', $adherent->adresse ?? '') }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="residence" class="form-label">Résidence *</label>
                                    <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                           id="residence" name="residence" value="{{ old('residence', $adherent->residence ?? '') }}" required>
                                    @error('residence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="secteur_numero" class="form-label">Numéro de Secteur</label>
                                    <input type="number" class="form-control @error('secteur_numero') is-invalid @enderror" 
                                           id="secteur_numero" name="secteur_numero" value="{{ old('secteur_numero', $adherent->secteur_numero ?? '') }}" min="1">
                                    @error('secteur_numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession" class="form-label">Profession *</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                           id="profession" name="profession" value="{{ old('profession', $adherent->profession ?? '') }}" required>
                                    @error('profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession_exercee" class="form-label">Profession Exercée</label>
                                    <input type="text" class="form-control @error('profession_exercee') is-invalid @enderror" 
                                           id="profession_exercee" name="profession_exercee" value="{{ old('profession_exercee', $adherent->profession_exercee ?? '') }}">
                                    @error('profession_exercee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact d'Urgence (Principal)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control @error('contact_urgence_nom') is-invalid @enderror"
                                           id="contact_urgence_nom" name="contact_urgence_nom"
                                           value="{{ old('contact_urgence_nom', $adherent->contact_urgence_nom ?? '') }}" 
                                           placeholder="Nom et prénoms du contact" required>
                                    @error('contact_urgence_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_lien" class="form-label">Lien avec vous *</label>
                                    <select class="form-select @error('contact_urgence_lien') is-invalid @enderror"
                                           id="contact_urgence_lien" name="contact_urgence_lien" required>
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                                        <option value="parent" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="enfant" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                                        <option value="frere" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'frere' ? 'selected' : '' }}>Frère</option>
                                        <option value="soeur" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'soeur' ? 'selected' : '' }}>Sœur</option>
                                        <option value="ami" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'ami' ? 'selected' : '' }}>Ami(e)</option>
                                        <option value="collegue" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'collegue' ? 'selected' : '' }}>Collègue</option>
                                        <option value="autre" {{ old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('contact_urgence_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone d'Urgence *</label>
                                    <input type="text" class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="{{ old('contact_urgence_telephone', $adherent->contact_urgence_telephone ?? '') }}" required>
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
                                           value="{{ old('contact_urgence_2_nom', $adherent->contact_urgence_2_nom ?? '') }}" 
                                           placeholder="Nom et prénoms du contact">
                                    @error('contact_urgence_2_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_lien" class="form-label">Lien avec vous</label>
                                    <select class="form-select @error('contact_urgence_2_lien') is-invalid @enderror"
                                           id="contact_urgence_2_lien" name="contact_urgence_2_lien">
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'conjoint' ? 'selected' : '' }}>Conjoint(e)</option>
                                        <option value="parent" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="enfant" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'enfant' ? 'selected' : '' }}>Enfant</option>
                                        <option value="frere" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'frere' ? 'selected' : '' }}>Frère</option>
                                        <option value="soeur" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'soeur' ? 'selected' : '' }}>Sœur</option>
                                        <option value="ami" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'ami' ? 'selected' : '' }}>Ami(e)</option>
                                        <option value="collegue" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'collegue' ? 'selected' : '' }}>Collègue</option>
                                        <option value="autre" {{ old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('contact_urgence_2_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_telephone" class="form-label">Téléphone d'Urgence Secondaire</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_telephone') is-invalid @enderror" 
                                           id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                           value="{{ old('contact_urgence_2_telephone', $adherent->contact_urgence_2_telephone ?? '') }}">
                                    @error('contact_urgence_2_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    Continuer vers les ayants droit <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
