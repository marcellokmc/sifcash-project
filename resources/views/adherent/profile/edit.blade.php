@extends('layouts.adherent-modern')

@section('title', 'Modifier Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier Mon Profil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('adherent.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Informations Personnelles</h6>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom', $adherent->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom', $adherent->prenom) }}" required>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de Naissance *</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" 
                                           value="{{ old('date_naissance', $adherent->date_naissance->format('Y-m-d')) }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="lieu_naissance" class="form-label">Lieu de Naissance *</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" name="lieu_naissance" 
                                           value="{{ old('lieu_naissance', $adherent->lieu_naissance) }}" required>
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="situation_famille" class="form-label">Situation Familiale *</label>
                                    <select class="form-select @error('situation_famille') is-invalid @enderror" 
                                            id="situation_famille" name="situation_famille" required>
                                        <option value="">Choisir...</option>
                                        <option value="marié" {{ old('situation_famille', $adherent->situation_famille) == 'marié' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="celibataire" {{ old('situation_famille', $adherent->situation_famille) == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="veuf/veuve" {{ old('situation_famille', $adherent->situation_famille) == 'veuf/veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                                        <option value="divorcé" {{ old('situation_famille', $adherent->situation_famille) == 'divorcé' ? 'selected' : '' }}>Divorcé(e)</option>
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
                                           id="telephone" name="telephone" value="{{ old('telephone', $adherent->telephone) }}" required>
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="telephone_secondaire" class="form-label">Téléphone Secondaire</label>
                                    <input type="text" class="form-control @error('telephone_secondaire') is-invalid @enderror" 
                                           id="telephone_secondaire" name="telephone_secondaire" 
                                           value="{{ old('telephone_secondaire', $adherent->telephone_secondaire) }}">
                                    @error('telephone_secondaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email (optionnel)</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $adherent->email) }}" 
                                           placeholder="email@exemple.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">L'email est optionnel</div>
                                </div>

                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse *</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" name="adresse" rows="3" required>{{ old('adresse', $adherent->adresse) }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="residence" class="form-label">Résidence *</label>
                                    <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                           id="residence" name="residence" value="{{ old('residence', $adherent->residence) }}" required>
                                    @error('residence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="secteur_numero" class="form-label">Secteur N°</label>
                                    <input type="text" class="form-control @error('secteur_numero') is-invalid @enderror" 
                                           id="secteur_numero" name="secteur_numero" 
                                           value="{{ old('secteur_numero', $adherent->secteur_numero) }}" 
                                           placeholder="Exemple: Secteur 15, Zone 3, etc.">
                                    <div class="form-text">Indiquez le secteur de résidence (facultatif)</div>
                                    @error('secteur_numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession_exercee" class="form-label">Profession exercée</label>
                                    <input type="text" class="form-control @error('profession_exercee') is-invalid @enderror" 
                                           id="profession_exercee" name="profession_exercee" 
                                           value="{{ old('profession_exercee', $adherent->profession_exercee) }}" 
                                           placeholder="Exemple: Enseignant, Commerçant, Agriculteur, etc.">
                                    <div class="form-text">Spécifiez la profession actuellement exercée (facultatif)</div>
                                    @error('profession_exercee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profession" class="form-label">Profession *</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                           id="profession" name="profession" value="{{ old('profession', $adherent->profession) }}" required>
                                    @error('profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact d'Urgence (Principal)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('contact_urgence_nom') is-invalid @enderror"
                                           id="contact_urgence_nom" name="contact_urgence_nom"
                                           value="{{ old('contact_urgence_nom', $adherent->contact_urgence_nom) }}">
                                    @error('contact_urgence_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_prenoms" class="form-label">Prénoms</label>
                                    <input type="text" class="form-control @error('contact_urgence_prenoms') is-invalid @enderror"
                                           id="contact_urgence_prenoms" name="contact_urgence_prenoms"
                                           value="{{ old('contact_urgence_prenoms', $adherent->contact_urgence_prenoms) }}">
                                    @error('contact_urgence_prenoms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_lien_parente" class="form-label">Lien de parenté</label>
                                    <input type="text" class="form-control @error('contact_urgence_lien_parente') is-invalid @enderror"
                                           id="contact_urgence_lien_parente" name="contact_urgence_lien_parente"
                                           value="{{ old('contact_urgence_lien_parente', $adherent->contact_urgence_lien_parente) }}">
                                    @error('contact_urgence_lien_parente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone d'Urgence *</label>
                                    <input type="text" class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="{{ old('contact_urgence_telephone', $adherent->contact_urgence_telephone) }}" required>
                                    @error('contact_urgence_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <a href="{{ route('adherent.profile') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
