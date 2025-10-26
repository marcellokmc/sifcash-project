@extends('layouts.adherent-modern')

@section('title', 'Détail Ayant Droit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail de l'Ayant Droit</h5>
                    <a href="{{ route('adherent.ayants-droit.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom:</th>
                                    <td>{{ $ayantDroit->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Prénom:</th>
                                    <td>{{ $ayantDroit->prenom }}</td>
                                </tr>
                                <tr>
                                    <th>Date de naissance:</th>
                                    <td>{{ $ayantDroit->date_naissance ? $ayantDroit->date_naissance->format('d/m/Y') : 'Non renseignée' }}</td>
                                </tr>
                                <tr>
                                    <th>Lien de parenté:</th>
                                    <td>{{ $ayantDroit->lien_parente }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Contact:</th>
                                    <td>{{ $ayantDroit->contact ?? 'Non renseigné' }}</td>
                                </tr>
                                <tr>
                                    <th>Type bénéficiaire:</th>
                                    <td>
                                        @if($ayantDroit->type_beneficiaire == 'vie')
                                            <span class="badge bg-info">Vie</span>
                                        @else
                                            <span class="badge bg-secondary">Décès</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut:</th>
                                    <td>
                                        @if($ayantDroit->isValide())
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($ayantDroit->isEnAttente())
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($ayantDroit->validateur)
                                <tr>
                                    <th>Validé par:</th>
                                    <td>{{ $ayantDroit->validateur->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($ayantDroit->isEnAttente())
                    <div class="mt-4">
                        <a href="{{ route('adherent.ayants-droit.edit', $ayantDroit) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('adherent.ayants-droit.destroy', $ayantDroit) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet ayant droit ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
