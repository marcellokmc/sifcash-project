@extends('backoffice.layouts.app')

@section('title', 'Adhésion #'.$adhesion->id)
@section('page-title', 'Détails de l\'Adhésion')

@section('content')
@include('components.backoffice.alerts')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Adhésion #{{ $adhesion->id }}</h1>
        <div class="text-muted">
            <i class="fas fa-user me-1"></i>{{ $adhesion->adherent->nom ?? 'N/A' }} {{ $adhesion->adherent->prenom ?? '' }}
            @if($adhesion->adherent->telephone)
                | <i class="fas fa-phone me-1"></i>{{ $adhesion->adherent->telephone }}
            @endif
        </div>
    </div>
    <div>
        <a href="{{ route('admin.adhesions.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
        </a>
        @can('update', $adhesion)
            <a href="{{ route('admin.adhesions.edit', $adhesion) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
        @endcan
    </div>
</div>

<div class="row">
    <!-- Informations principales -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Informations de l'Adhésion
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Plan d'épargne</h6>
                        <p class="mb-0">
                            <strong>{{ $adhesion->plan->nom ?? 'Plan supprimé' }}</strong>
                            @if($adhesion->plan)
                                <br><small class="text-muted">
                                    Taux: {{ $adhesion->plan->taux_interet }}% - {{ ucfirst($adhesion->plan->periodicite) }}
                                </small>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Montant souscrit</h6>
                        <p class="mb-0">
                            <strong class="text-primary h5">{{ number_format($adhesion->montant_souscrit, 0, ',', ' ') }} FCFA</strong>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de début</h6>
                        <p class="mb-0">
                            {{ $adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'Non définie' }}
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de fin</h6>
                        <p class="mb-0">
                            {{ $adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'Non définie' }}
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Statut</h6>
                        <p class="mb-0">
                            @php
                                $statutClass = match($adhesion->statut) {
                                    'actif' => 'bg-success',
                                    'en_attente_activation' => 'bg-warning text-dark',
                                    'suspendue' => 'bg-warning text-dark', 
                                    'terminee' => 'bg-secondary',
                                    'annulee' => 'bg-danger',
                                    default => 'bg-info'
                                };
                            @endphp
                            <span class="badge {{ $statutClass }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $adhesion->statut)) }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Renouvelable</h6>
                        <p class="mb-0">
                            @if($adhesion->renouvelable)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-times me-1"></i>Non
                                </span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Date de création</h6>
                        <p class="mb-0">{{ $adhesion->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted">Dernière mise à jour</h6>
                        <p class="mb-0">{{ $adhesion->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
                
                @if($adhesion->notes)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted">Notes</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-sticky-note me-2"></i>{{ $adhesion->notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Historique des versements si disponible -->
        @if(isset($adhesion->versements) && $adhesion->versements->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2 text-success"></i>Historique des Versements
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Type</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adhesion->versements as $versement)
                            <tr>
                                <td>{{ $versement->date_versement->format('d/m/Y') }}</td>
                                <td>{{ number_format($versement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ ucfirst($versement->type) }}</td>
                                <td>
                                    <span class="badge bg-{{ $versement->statut === 'valide' ? 'success' : 'warning' }}">
                                        {{ ucfirst($versement->statut) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar avec actions et infos adhérent -->
    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>Actions
                </h6>
            </div>
            <div class="card-body">
                @can('update', $adhesion)
                    @if($adhesion->statut === 'en_attente_activation')
                        <form method="POST" action="{{ route('admin.adhesions.activate', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Activer cette adhésion ?')">
                                <i class="fas fa-play me-1"></i>Activer l'adhésion
                            </button>
                        </form>
                    @elseif($adhesion->statut === 'actif')
                        <form method="POST" action="{{ route('admin.adhesions.suspend', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Suspendre cette adhésion ?')">
                                <i class="fas fa-pause me-1"></i>Suspendre
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.adhesions.close', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100" onclick="return confirm('Clôturer cette adhésion ?')">
                                <i class="fas fa-stop me-1"></i>Clôturer
                            </button>
                        </form>
                    @elseif($adhesion->statut === 'suspendue')
                        <form method="POST" action="{{ route('admin.adhesions.resume', $adhesion) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Reprendre cette adhésion ?')">
                                <i class="fas fa-play me-1"></i>Reprendre
                            </button>
                        </form>
                    @endif
                    
                    @if($adhesion->renouvelable && in_array($adhesion->statut, ['actif', 'terminee']))
                        <form method="POST" action="{{ route('admin.adhesions.renew', $adhesion) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Renouveler cette adhésion ?')">
                                <i class="fas fa-redo me-1"></i>Renouveler
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>
        
        <!-- Informations adhérent -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>Informations Adhérent
                </h6>
            </div>
            <div class="card-body">
                @if($adhesion->adherent)
                <div class="text-center mb-3">
                    <img src="{{ $adhesion->adherent->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($adhesion->adherent->nom.' '.$adhesion->adherent->prenom).'&background=random' }}" 
                         class="rounded-circle mb-2" 
                         width="80" height="80"
                         alt="Photo de l'adhérent">
                    <h6 class="mb-0">{{ $adhesion->adherent->nom }} {{ $adhesion->adherent->prenom }}</h6>
                    <small class="text-muted">{{ $adhesion->adherent->user->email ?? '' }}</small>
                </div>
                
                <div class="mb-2">
                    <strong>Matricule:</strong> {{ $adhesion->adherent->matricule ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Téléphone:</strong> {{ $adhesion->adherent->telephone ?? 'N/A' }}
                </div>
                <div class="mb-2">
                    <strong>Statut:</strong> 
                    <span class="badge bg-{{ $adhesion->adherent->statut_validation === 'valide' ? 'success' : 'warning' }}">
                        {{ ucfirst($adhesion->adherent->statut_validation ?? 'En cours') }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Membre depuis:</strong> {{ $adhesion->adherent->created_at->format('m/Y') }}
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('admin.adherents.show', $adhesion->adherent) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-user me-1"></i>Voir le profil complet
                    </a>
                </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Adhérent non trouvé
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection