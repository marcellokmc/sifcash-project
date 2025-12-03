@extends('layouts.adherent-modern')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mon Profil Adhérent</h5>
                    <a href="{{ route('adherent.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    @if($adherent->isActif())
                    <a href="{{ route('adherent.contrat.download') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i> Télécharger mon contrat
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Informations Personnelles</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Numéro Membre:</th>
                                    <td>{{ $adherent->membre_id }}</td>
                                </tr>
                                <tr>
                                    <th>Nom:</th>
                                    <td>{{ $adherent->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Prénom:</th>
                                    <td>{{ $adherent->prenom }}</td>
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
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary">Coordonnées</h6>
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
                                <tr>
                                    <th>Profession:</th>
                                    <td>{{ $adherent->profession }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact d'Urgence Principal</h6>
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
                            <h6 class="text-primary">Contact d'Urgence Secondaire</h6>
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
                            <h6 class="text-primary">Statut du Compte</h6>
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
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary">Commercial Référent</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Commercial:</th>
                                    <td>
                                        @if($adherent->commercial)
                                            <span class="badge bg-primary">{{ $adherent->commercial->nom }} {{ $adherent->commercial->prenoms }}</span>
                                            <small class="text-muted d-block">Code: {{ $adherent->commercial->code_commercial }}</small>
                                        @else
                                            <span class="text-muted">Non assigné</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($adherent->commercial)
                                <tr>
                                    <th>Téléphone:</th>
                                    <td>{{ $adherent->commercial->telephone ?? 'Non renseigné' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

