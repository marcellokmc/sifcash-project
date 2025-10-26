@extends('backoffice.layouts.app')

@section('title', 'Modifier Adhérent')

@push('styles')
<style>
    .section-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .section-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .section-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.adherents.index') }}">Adhérents</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.adherents.show', $adherent) }}">{{ $adherent->membre_id }}</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Modifier l'Adhérent : {{ $adherent->membre_id }}
                        </h5>
                        <span class="badge bg-light text-dark">{{ $adherent->nom_complet }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.adherents.update', $adherent) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-user"></i>
                                    <span>Informations Personnelles</span>
                                </div>
                                
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

                                <div class="mb-3">
                                    <label for="profession" class="form-label">Profession *</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                           id="profession" name="profession" value="{{ old('profession', $adherent->profession) }}" required>
                                    @error('profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-address-book"></i>
                                    <span>Coordonnées</span>
                                </div>

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
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $adherent->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>Contact d'Urgence Principal</span>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control @error('contact_urgence_nom') is-invalid @enderror" 
                                           id="contact_urgence_nom" name="contact_urgence_nom" 
                                           value="{{ old('contact_urgence_nom', $adherent->contact_urgence_nom) }}" required>
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
                                    <label for="contact_urgence_lien" class="form-label">Lien de parenté *</label>
                                    <input type="text" class="form-control @error('contact_urgence_lien') is-invalid @enderror" 
                                           id="contact_urgence_lien" name="contact_urgence_lien" 
                                           value="{{ old('contact_urgence_lien', $adherent->contact_urgence_lien_parente) }}" 
                                           placeholder="Ex: Père, Mère, Frère, Sœur, Ami, etc." required>
                                    @error('contact_urgence_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone *</label>
                                    <input type="text" class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="{{ old('contact_urgence_telephone', $adherent->contact_urgence_telephone) }}" required>
                                    @error('contact_urgence_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-phone-square-alt"></i>
                                    <span>Contact d'Urgence Secondaire</span>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_nom') is-invalid @enderror" 
                                           id="contact_urgence_2_nom" name="contact_urgence_2_nom" 
                                           value="{{ old('contact_urgence_2_nom', $adherent->contact_urgence_secondaire_nom) }}">
                                    @error('contact_urgence_2_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_prenoms" class="form-label">Prénoms</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_prenoms') is-invalid @enderror" 
                                           id="contact_urgence_2_prenoms" name="contact_urgence_2_prenoms" 
                                           value="{{ old('contact_urgence_2_prenoms', $adherent->contact_urgence_secondaire_prenoms) }}">
                                    @error('contact_urgence_2_prenoms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_lien" class="form-label">Lien de parenté</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_lien') is-invalid @enderror" 
                                           id="contact_urgence_2_lien" name="contact_urgence_2_lien" 
                                           value="{{ old('contact_urgence_2_lien', $adherent->contact_urgence_secondaire_lien_parente) }}" 
                                           placeholder="Ex: Père, Mère, Frère, Sœur, Ami, etc.">
                                    @error('contact_urgence_2_lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control @error('contact_urgence_2_telephone') is-invalid @enderror" 
                                           id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                           value="{{ old('contact_urgence_2_telephone', $adherent->contact_urgence_secondaire_telephone) }}">
                                    @error('contact_urgence_2_telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">

                                <div class="section-title">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Statut du Compte</span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="statut_compte" class="form-label">Statut *</label>
                                    <select class="form-select @error('statut_compte') is-invalid @enderror" 
                                            id="statut_compte" name="statut_compte" required>
                                        <option value="actif" {{ old('statut_compte', $adherent->statut_compte) == 'actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="inactif" {{ old('statut_compte', $adherent->statut_compte) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                        <option value="en_attente_de_verification" {{ old('statut_compte', $adherent->statut_compte) == 'en_attente_de_verification' ? 'selected' : '' }}>En attente</option>
                                    </select>
                                    @error('statut_compte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($adherent->isActif() && $adherent->date_activation)
                                    <div class="mb-3">
                                        <label class="form-label">Date d'activation</label>
                                        <input type="text" class="form-control" value="{{ $adherent->date_activation->format('d/m/Y H:i') }}" readonly>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                    <a href="{{ route('admin.adherents.show', $adherent) }}" class="btn btn-secondary btn-lg ms-2">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Les champs marqués d'un * sont obligatoires
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection