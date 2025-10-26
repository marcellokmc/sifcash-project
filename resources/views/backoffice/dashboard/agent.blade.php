@extends('backoffice.layouts.app')

@section('title', 'Tableau de bord Agent')
@section('page-title', 'Tableau de bord Agent')

@section('content')
<div class="row">
    <!-- Statistiques Agent -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Adhérents de l'agence</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $stats['total_adherents'] }}</div>
                        <div class="mt-1">
                            @if($stats['adherents_en_attente'] > 0)
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i>{{ $stats['adherents_en_attente'] }} en attente
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Documents à valider</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $stats['documents_en_attente'] }}</div>
                        <div class="mt-1">
                            @if($stats['documents_en_attente'] > 0)
                                <a href="{{ route('admin.validation.documents') }}" class="text-warning small text-decoration-none">
                                    <i class="fas fa-eye me-1"></i>Vérifier
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Ayants droit à valider</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">{{ $stats['ayants_droit_en_attente'] }}</div>
                        <div class="mt-1">
                            @if($stats['ayants_droit_en_attente'] > 0)
                                <a href="{{ route('admin.validation.ayants-droit') }}" class="text-info small text-decoration-none">
                                    <i class="fas fa-eye me-1"></i>Vérifier
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-friends fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Mon Agence</div>
                        <div class="h6 mb-0 font-weight-bold text-dark">{{ $agence->nom }}</div>
                        <div class="mt-1">
                            <small class="text-muted">{{ $stats['adherents_actifs'] }} adhérents actifs</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Actions Rapides -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.adherents.index') }}" class="btn btn-outline-primary text-start">
                        <i class="fas fa-users me-2"></i>Gérer les adhérents
                        @if($stats['adherents_en_attente'] > 0)
                            <span class="badge bg-warning ms-2">{{ $stats['adherents_en_attente'] }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.validation.documents') }}" class="btn btn-outline-warning text-start">
                        <i class="fas fa-file-alt me-2"></i>Valider les documents
                        @if($stats['documents_en_attente'] > 0)
                            <span class="badge bg-warning ms-2">{{ $stats['documents_en_attente'] }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.validation.ayants-droit') }}" class="btn btn-outline-info text-start">
                        <i class="fas fa-user-friends me-2"></i>Valider les ayants droit
                        @if($stats['ayants_droit_en_attente'] > 0)
                            <span class="badge bg-warning ms-2">{{ $stats['ayants_droit_en_attente'] }}</span>
                        @endif
                    </a>
                    
                    <button class="btn btn-outline-success text-start">
                        <i class="fas fa-credit-card me-2"></i>Évaluer les crédits
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations Agence -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations de l'Agence</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">Code:</th>
                        <td>{{ $agence->code }}</td>
                    </tr>
                    <tr>
                        <th>Province:</th>
                        <td>{{ $agence->province }}</td>
                    </tr>
                    <tr>
                        <th>Département:</th>
                        <td>{{ $agence->departement ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Adresse:</th>
                        <td>{{ $agence->adresse }}</td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td>{{ $agence->contact }}</td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            @if($agence->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Activité Récente -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
            </div>
            <div class="card-body">
                @if(isset($recentActivity) && $recentActivity->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivity->take(5) as $activity)
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-{{ $activity->action === 'login' ? 'sign-in-alt text-success' : 'user text-primary' }}"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <small class="d-block text-muted">
                                        {{ $activity->user->name }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($recentActivity->count() > 5)
                    <div class="text-center mt-2">
                        <a href="#" class="text-decoration-none small">
                            Voir toute l'activité
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune activité récente
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Alertes de validation -->
@if($stats['documents_en_attente'] > 0 || $stats['ayants_droit_en_attente'] > 0)
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Validations en attente</h6>
            <div class="row mt-2">
                @if($stats['documents_en_attente'] > 0)
                <div class="col-md-6">
                    <i class="fas fa-file-alt me-2"></i>
                    <strong>{{ $stats['documents_en_attente'] }}</strong> document(s) en attente de validation
                    <a href="{{ route('admin.validation.documents') }}" class="btn btn-sm btn-warning ms-2">
                        <i class="fas fa-check-circle me-1"></i>Valider
                    </a>
                </div>
                @endif
                @if($stats['ayants_droit_en_attente'] > 0)
                <div class="col-md-6">
                    <i class="fas fa-users me-2"></i>
                    <strong>{{ $stats['ayants_droit_en_attente'] }}</strong> ayant(s) droit en attente de validation
                    <a href="{{ route('admin.validation.ayants-droit') }}" class="btn btn-sm btn-info ms-2">
                        <i class="fas fa-check-circle me-1"></i>Valider
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistiques détaillées -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques de l'Agence</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h6>Total Adhérents</h6>
                            <h4 class="text-primary">{{ $stats['total_adherents'] }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                            <h6>Adhérents Actifs</h6>
                            <h4 class="text-success">{{ $stats['adherents_actifs'] }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h6>En Attente</h6>
                            <h4 class="text-warning">{{ $stats['adherents_en_attente'] }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                            <h6>Activité Aujourd'hui</h6>
                            <h4 class="text-info">{{ $stats['recent_activity'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection