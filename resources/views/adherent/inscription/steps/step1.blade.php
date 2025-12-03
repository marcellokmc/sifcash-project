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

                        <div class="row g-4">
                            <!-- Section Informations Personnelles -->
                            <div class="col-12">
                                <div class="card border-light shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-user me-2"></i>Informations Personnelles
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="nom" class="form-label fw-bold">Nom *</label>
                                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                           id="nom" name="nom" value="{{ old('nom', $adherent->nom ?? '') }}" required>
                                                    @error('nom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="prenom" class="form-label fw-bold">Prénom *</label>
                                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                                           id="prenom" name="prenom" value="{{ old('prenom', $adherent->prenom ?? '') }}" required>
                                                    @error('prenom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="date_naissance" class="form-label fw-bold">Date de Naissance *</label>
                                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                                           id="date_naissance" name="date_naissance" 
                                                           value="{{ old('date_naissance', $adherent->date_naissance ?? '') }}" required>
                                                    @error('date_naissance')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="lieu_naissance" class="form-label fw-bold">Lieu de Naissance *</label>
                                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                                           id="lieu_naissance" name="lieu_naissance" 
                                                           value="{{ old('lieu_naissance', $adherent->lieu_naissance ?? '') }}" required>
                                                    @error('lieu_naissance')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="situation_famille" class="form-label fw-bold">Situation Familiale *</label>
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
                                                <div class="mb-3">
                                                    <label for="profession" class="form-label fw-bold">Profession *</label>
                                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                                           id="profession" name="profession" value="{{ old('profession', $adherent->profession ?? '') }}" required>
                                                    @error('profession')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="profession_exercee" class="form-label">Profession Exercée</label>
                                                    <input type="text" class="form-control @error('profession_exercee') is-invalid @enderror" 
                                                           id="profession_exercee" name="profession_exercee" value="{{ old('profession_exercee', $adherent->profession_exercee ?? '') }}">
                                                    @error('profession_exercee')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Coordonnées -->
                            <div class="col-12">
                                <div class="card border-light shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-address-card me-2"></i>Coordonnées
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="telephone" class="form-label fw-bold">Téléphone *</label>
                                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                                                           id="telephone" name="telephone" value="{{ old('telephone', $adherent->telephone ?? '') }}" required>
                                                    @error('telephone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="telephone_secondaire" class="form-label">Téléphone Secondaire</label>
                                                    <input type="text" class="form-control @error('telephone_secondaire') is-invalid @enderror" 
                                                           id="telephone_secondaire" name="telephone_secondaire" 
                                                           value="{{ old('telephone_secondaire', $adherent->telephone_secondaire ?? '') }}">
                                                    @error('telephone_secondaire')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email" class="form-label fw-bold">Email (optionnel)</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                           id="email" name="email" value="{{ old('email', $adherent->email ?? '') }}" 
                                                           placeholder="email@exemple.com">
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-text">L'email est optionnel</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="secteur_numero" class="form-label">Numéro de Secteur</label>
                                                    <input type="number" class="form-control @error('secteur_numero') is-invalid @enderror" 
                                                           id="secteur_numero" name="secteur_numero" value="{{ old('secteur_numero', $adherent->secteur_numero ?? '') }}" min="1">
                                                    @error('secteur_numero')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label for="adresse" class="form-label fw-bold">Adresse *</label>
                                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                                              id="adresse" name="adresse" rows="2" required>{{ old('adresse', $adherent->adresse ?? '') }}</textarea>
                                                    @error('adresse')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="residence" class="form-label fw-bold">Résidence *</label>
                                                    <input type="text" class="form-control @error('residence') is-invalid @enderror" 
                                                           id="residence" name="residence" value="{{ old('residence', $adherent->residence ?? '') }}" required>
                                                    @error('residence')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="commercial_code" class="form-label">Code Commercial</label>
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control @error('commercial_id') is-invalid @enderror" 
                                                               id="commercial_code" name="commercial_code" 
                                                               value="{{ old('commercial_code') }}" 
                                                               placeholder="Tapez pour rechercher ou cliquez pour voir la liste"
                                                               autocomplete="off">
                                                        <button type="button" class="btn btn-outline-secondary position-absolute" 
                                                                style="right: 5px; top: 2px; z-index: 10;" id="toggle-commercial-list">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                        <div id="commercial-dropdown" class="position-absolute w-100 bg-white border rounded shadow-lg" 
                                                             style="z-index: 1000; max-height: 300px; overflow-y: auto; display: none;">
                                                            <div class="p-2">
                                                                <input type="text" class="form-control form-control-sm" 
                                                                       id="commercial-search-input" 
                                                                       placeholder="Rechercher un commercial...">
                                                            </div>
                                                            <div id="commercial-list" class="list-group list-group-flush">
                                                                <!-- La liste sera chargée ici -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">Optionnel : Si vous avez été référencé par un commercial</small>
                                                    <div id="commercial-selection" class="mt-2"></div>
                                                    <input type="hidden" id="commercial_id" name="commercial_id" value="{{ old('commercial_id') }}">
                                                    @error('commercial_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- Section Contacts d'Urgence -->
                            <div class="col-12">
                                <div class="card border-light shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">
                                            <i class="fas fa-phone-alt me-2"></i>Contacts d'Urgence
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <!-- Contact d'Urgence Principal -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 bg-light">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-user-friends me-2"></i>Contact d'Urgence (Principal)
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label for="contact_urgence_nom" class="form-label fw-bold">Nom complet *</label>
                                                                <input type="text" class="form-control @error('contact_urgence_nom') is-invalid @enderror"
                                                                       id="contact_urgence_nom" name="contact_urgence_nom"
                                                                       value="{{ old('contact_urgence_nom', $adherent->contact_urgence_nom ?? '') }}" 
                                                                       placeholder="Nom et prénoms du contact" required>
                                                                @error('contact_urgence_nom')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="contact_urgence_lien" class="form-label fw-bold">Lien avec vous *</label>
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
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="contact_urgence_telephone" class="form-label fw-bold">Téléphone d'Urgence *</label>
                                                                <input type="text" class="form-control @error('contact_urgence_telephone') is-invalid @enderror" 
                                                                       id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                                                       value="{{ old('contact_urgence_telephone', $adherent->contact_urgence_telephone ?? '') }}" required>
                                                                @error('contact_urgence_telephone')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contact d'Urgence Secondaire -->
                                            <div class="col-md-6">
                                                <div class="border rounded p-3 bg-light">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-user-friends me-2"></i>Contact d'Urgence (Secondaire)
                                                        <small class="text-muted ms-2">(Optionnel)</small>
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-12">
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
                                                        </div>
                                                        <div class="col-md-6">
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
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label for="contact_urgence_2_telephone" class="form-label">Tél d'Urgence Secondaire</label>
                                                                <input type="text" class="form-control @error('contact_urgence_2_telephone') is-invalid @enderror" 
                                                                       id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                                                       value="{{ old('contact_urgence_2_telephone', $adherent->contact_urgence_2_telephone ?? '') }}">
                                                                @error('contact_urgence_2_telephone')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-arrow-right me-2"></i>Continuer vers les ayants droit
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commercialCodeInput = document.getElementById('commercial_code');
    const toggleButton = document.getElementById('toggle-commercial-list');
    const dropdown = document.getElementById('commercial-dropdown');
    const searchInput = document.getElementById('commercial-search-input');
    const commercialList = document.getElementById('commercial-list');
    const commercialSelection = document.getElementById('commercial-selection');
    const commercialIdInput = document.getElementById('commercial_id');
    
    let allCommercials = [];
    let filteredCommercials = [];
    let isOpen = false;

    // Données des commerciaux passées depuis le contrôleur (fallback)
    const serverCommercials = @json($commercials ?? []);
    console.log('Commerciaux du serveur:', serverCommercials);

    // Fonction pour initialiser les commerciaux
    function initializeCommercials(data) {
        allCommercials = data;
        filteredCommercials = [...allCommercials];
        console.log('Commerciaux initialisés:', allCommercials.length);
        renderCommercialList();
    }

    // Utiliser les données du serveur immédiatement si disponibles
    if (serverCommercials && serverCommercials.length > 0) {
        console.log('Utilisation des données du serveur');
        initializeCommercials(serverCommercials);
    } else {
        // Sinon, essayer de charger via API
        console.log('Tentative de chargement des commerciaux via API...');
        const apiUrl = '{{ url("/api/v1/commercials") }}';
        console.log('API URL:', apiUrl);
        
        // Afficher un indicateur de chargement
        commercialList.innerHTML = `
            <div class="list-group-item text-center text-muted py-3">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                Chargement des commerciaux...
            </div>
        `;
        
        fetch(apiUrl)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                if (data.success) {
                    initializeCommercials(data.data);
                } else {
                    console.error('La réponse API indique un échec:', data);
                    // Fallback: utiliser les données du serveur si disponibles
                    if (serverCommercials && serverCommercials.length > 0) {
                        console.log('Fallback vers les données du serveur');
                        initializeCommercials(serverCommercials);
                    } else {
                        throw new Error('Aucune donnée disponible');
                    }
                }
            })
            .catch(error => {
                console.error('Error loading commercials:', error);
                // Fallback: utiliser les données du serveur si disponibles
                if (serverCommercials && serverCommercials.length > 0) {
                    console.log('Fallback vers les données du serveur après erreur API');
                    initializeCommercials(serverCommercials);
                } else {
                    // Afficher un message d'erreur à l'utilisateur
                    commercialList.innerHTML = `
                        <div class="list-group-item text-center text-danger py-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Erreur de chargement: ${error.message}
                        </div>
                    `;
                }
            });
    }

    function renderCommercialList() {
        if (filteredCommercials.length === 0) {
            commercialList.innerHTML = `
                <div class="list-group-item text-center text-muted py-3">
                    <i class="fas fa-search me-2"></i>
                    Aucun commercial trouvé
                </div>
            `;
            return;
        }

        let html = '';
        filteredCommercials.forEach(commercial => {
            const nomComplet = `${commercial.nom} ${commercial.prenoms}`;
            html += `
                <div class="list-group-item list-group-item-action commercial-item" 
                     data-commercial-id="${commercial.id}" 
                     data-commercial-code="${commercial.code_commercial}"
                     data-commercial-name="${nomComplet}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-bold">${commercial.code_commercial}</div>
                            <div class="small text-muted">${nomComplet}</div>
                        </div>
                        <div class="small text-muted">${commercial.telephone}</div>
                    </div>
                </div>
            `;
        });
        
        commercialList.innerHTML = html;

        // Ajouter les écouteurs d'événements
        commercialList.querySelectorAll('.commercial-item').forEach(item => {
            item.addEventListener('click', function() {
                selectCommercial(this);
            });
        });
    }

    function selectCommercial(item) {
        const code = item.dataset.commercialCode;
        const id = item.dataset.commercialId;
        const name = item.dataset.commercialName;
        
        commercialCodeInput.value = code;
        commercialIdInput.value = id;
        
        commercialSelection.innerHTML = `
            <div class="alert alert-success py-2">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Commercial sélectionné:</strong> ${name} (${code})
                <button type="button" class="btn btn-sm btn-outline-danger float-end" id="clear-commercial">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        closeDropdown();

        // Ajouter l'écouteur pour le bouton de suppression
        document.getElementById('clear-commercial').addEventListener('click', function() {
            clearSelection();
        });
    }

    function clearSelection() {
        commercialCodeInput.value = '';
        commercialIdInput.value = '';
        commercialSelection.innerHTML = '';
    }

    function filterCommercials(searchTerm) {
        const term = searchTerm.toLowerCase();
        filteredCommercials = allCommercials.filter(commercial => 
            commercial.code_commercial.toLowerCase().includes(term) ||
            commercial.nom.toLowerCase().includes(term) ||
            commercial.prenoms.toLowerCase().includes(term) ||
            commercial.telephone.toLowerCase().includes(term)
        );
        renderCommercialList();
    }

    function openDropdown() {
        dropdown.style.display = 'block';
        searchInput.focus();
        isOpen = true;
        toggleButton.innerHTML = '<i class="fas fa-chevron-up"></i>';
    }

    function closeDropdown() {
        dropdown.style.display = 'none';
        searchInput.value = '';
        filteredCommercials = [...allCommercials];
        renderCommercialList();
        isOpen = false;
        toggleButton.innerHTML = '<i class="fas fa-chevron-down"></i>';
    }

    function toggleDropdown() {
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }

    // Événements
    toggleButton.addEventListener('click', toggleDropdown);

    commercialCodeInput.addEventListener('focus', function() {
        if (!isOpen) {
            openDropdown();
        }
    });

    commercialCodeInput.addEventListener('input', function() {
        const value = this.value;
        if (value.length > 0) {
            filterCommercials(value);
            if (!isOpen) {
                openDropdown();
            }
        }
    });

    searchInput.addEventListener('input', function() {
        filterCommercials(this.value);
    });

    // Fermer le dropdown quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && e.target !== commercialCodeInput && e.target !== toggleButton) {
            closeDropdown();
        }
    });

    // Empêcher la fermeture quand on clique dans le dropdown
    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Gérer les touches du clavier
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    commercialCodeInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            clearSelection();
            closeDropdown();
        }
    });
});
</script>
@endpush
