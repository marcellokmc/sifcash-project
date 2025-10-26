@extends('backoffice.layouts.app')

@section('title', 'Détail Adhérent - ' . $adherent->membre_id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail de l'Adhérent : {{ $adherent->membre_id }}</h5>
                    <div>
                        @if($adherent->isEnAttente())
                        <form action="{{ route('admin.adherents.activate', $adherent) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Activer le compte
                            </button>
                        </form>
                        @elseif($adherent->isActif())
                        <form action="{{ route('admin.adherents.deactivate', $adherent) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-pause"></i> Désactiver
                            </button>
                        </form>
                        @endif
                        
                        @can('update', $adherent)
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="openResetPasswordModal({{ $adherent->id }}, '{{ $adherent->nom_complet }}', true)"
                                    title="Réinitialiser le mot de passe">
                                <i class="fas fa-key"></i>
                            </button>
                            
                            @if($adherent->isSuspended())
                                <button type="button" class="btn btn-success btn-sm" 
                                        onclick="openActivateModal({{ $adherent->id }}, '{{ $adherent->nom_complet }}', '{{ $adherent->suspended_at?->format('d/m/Y H:i') }}', '{{ $adherent->suspension_reason }}', '{{ $adherent->suspendedByUser?->name }}', true)"
                                        title="Réactiver le compte">
                                    <i class="fas fa-check-circle"></i> Réactiver
                                </button>
                            @else
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="openSuspendModal({{ $adherent->id }}, '{{ $adherent->nom_complet }}', true)"
                                        title="Suspendre le compte">
                                    <i class="fas fa-ban"></i>
                                </button>
                            @endif
                        </div>
                        @endcan
                        
                        <a href="{{ route('admin.adherents.affectation', $adherent) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-user-tie"></i> Affecter Agence/Agent
                        </a>
                        <a href="{{ route('admin.adherents.edit', $adherent) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('admin.adherents.contrat.download', $adherent) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-pdf"></i> Télécharger contrat
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Navigation par onglets -->
                    <ul class="nav nav-tabs" id="adherentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" 
                                    data-bs-target="#profile" type="button" role="tab">
                                Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ayants-droit-tab" data-bs-toggle="tab" 
                                    data-bs-target="#ayants-droit" type="button" role="tab">
                                Ayants Droit ({{ $adherent->ayantsDroit->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" 
                                    data-bs-target="#documents" type="button" role="tab">
                                Documents ({{ $adherent->documents->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="adhesions-tab" data-bs-toggle="tab" 
                                    data-bs-target="#adhesions" type="button" role="tab">
                                Adhésions ({{ $adherent->adhesions->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="adherentTabsContent">
                        <!-- Onglet Profil -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            @include('backoffice.adherents.partials.profile')
                        </div>

                        <!-- Onglet Ayants Droit -->
                        <div class="tab-pane fade" id="ayants-droit" role="tabpanel">
                            @include('backoffice.adherents.partials.ayants-droit')
                        </div>

                        <!-- Onglet Documents -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            @include('backoffice.adherents.partials.documents')
                        </div>

                        <!-- Onglet Adhésions -->
                        <div class="tab-pane fade" id="adhesions" role="tabpanel">
                            @include('backoffice.adherents.partials.adhesions')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('backoffice.partials.account-management-modals')
@endsection
