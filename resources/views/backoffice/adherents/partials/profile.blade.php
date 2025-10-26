<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Informations Personnelles</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Numéro Membre:</th>
                <td><strong>{{ $adherent->membre_id }}</strong></td>
            </tr>
            <tr>
                <th>Nom Complet:</th>
                <td>{{ $adherent->nom_complet }}</td>
            </tr>
            <tr>
                <th>Date de Naissance:</th>
                <td>{{ $adherent->date_naissance->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Lieu de Naissance:</th>
                <td>{{ $adherent->lieu_naissance }}</td>
            </tr>
            <tr>
                <th>Situation Familiale:</th>
                <td>{{ ucfirst($adherent->situation_famille) }}</td>
            </tr>
            <tr>
                <th>Profession:</th>
                <td>{{ $adherent->profession }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Coordonnées</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Téléphone:</th>
                <td>{{ $adherent->telephone }}</td>
            </tr>
            <tr>
                <th>Téléphone Secondaire:</th>
                <td>{{ $adherent->telephone_secondaire ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $adherent->email }}</td>
            </tr>
            <tr>
                <th>Adresse:</th>
                <td>{{ $adherent->adresse }}</td>
            </tr>
            <tr>
                <th>Résidence:</th>
                <td>{{ $adherent->residence }}</td>
            </tr>
            @if($adherent->secteur_numero)
            <tr>
                <th>Secteur N°:</th>
                <td><span class="badge bg-secondary">{{ $adherent->secteur_numero }}</span></td>
            </tr>
            @endif
            @if($adherent->profession_exercee)
            <tr>
                <th>Profession exercée:</th>
                <td><span class="badge bg-info">{{ $adherent->profession_exercee }}</span></td>
            </tr>
            @endif
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Contact d'Urgence Principal</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Nom Complet:</th>
                <td>{{ $adherent->contact_urgence_nom ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Lien:</th>
                <td>
                    @if($adherent->contact_urgence_lien)
                        <span class="badge bg-info">{{ ucfirst($adherent->contact_urgence_lien) }}</span>
                    @else
                        Non renseigné
                    @endif
                </td>
            </tr>
            <tr>
                <th>Téléphone:</th>
                <td>{{ $adherent->contact_urgence_telephone ?? 'Non renseigné' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Contact d'Urgence Secondaire</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Nom Complet:</th>
                <td>{{ $adherent->contact_urgence_2_nom ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Lien:</th>
                <td>
                    @if($adherent->contact_urgence_2_lien)
                        <span class="badge bg-info">{{ ucfirst($adherent->contact_urgence_2_lien) }}</span>
                    @else
                        Non renseigné
                    @endif
                </td>
            </tr>
            <tr>
                <th>Téléphone:</th>
                <td>{{ $adherent->contact_urgence_2_telephone ?? 'Non renseigné' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">
            <i class="fas fa-building"></i> Affectation
            <a href="{{ route('admin.adherents.affectation', $adherent) }}" class="btn btn-sm btn-info float-end">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Agence:</th>
                <td>
                    @if($adherent->agence)
                        <i class="fas fa-building text-primary"></i> 
                        <strong>{{ $adherent->agence->nom }}</strong>
                        <br>
                        <small class="text-muted">{{ $adherent->agence->ville }}</small>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle"></i> Non affecté
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Agent Gestionnaire:</th>
                <td>
                    @if($adherent->agentGestionnaire)
                        <i class="fas fa-user-tie text-success"></i> 
                        <strong>{{ $adherent->agentGestionnaire->name }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ ucfirst(str_replace('_', ' ', $adherent->agentGestionnaire->role)) }}
                            @if($adherent->agentGestionnaire->agence)
                                - {{ $adherent->agentGestionnaire->agence->nom }}
                            @endif
                        </small>
                    @else
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle"></i> Non affecté
                        </span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Date d'Adhésion</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Date d'Inscription:</th>
                <td><strong>{{ $adherent->created_at->format('d/m/Y H:i') }}</strong></td>
            </tr>
            @if($adherent->adhesions->isNotEmpty())
            <tr>
                <th>Première Adhésion:</th>
                <td>{{ $adherent->adhesions->first()->created_at->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>

<div class="row mt-4">
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Statut du Compte</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Statut:</th>
                <td>
                    @if($adherent->isActif())
                        <span class="badge bg-success">Actif</span>
                    @elseif($adherent->isEnAttente())
                        <span class="badge bg-warning">En attente</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Date d'Activation:</th>
                <td>{{ $adherent->date_activation ? $adherent->date_activation->format('d/m/Y H:i') : 'Non activé' }}</td>
            </tr>
            <tr>
                <th>Date d'Inscription:</th>
                <td>{{ $adherent->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Dernière Modification:</th>
                <td>{{ $adherent->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</div>