<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Adhérent - SIFCash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .adherent-sidebar {
            background: linear-gradient(135deg, #1cc88a 0%, #36b9cc 100%);
            color: white;
            min-height: 100vh;
        }
        .adherent-sidebar .nav-link {
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 5px;
            margin: 2px 0;
        } 
        .adherent-sidebar .nav-link:hover,
        .adherent-sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .stat-card {
            border-left: 4px solid #1cc88a;
        }
        .badge-document {
            font-size: 0.7em;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Adhérent -->
            <nav class="col-md-3 col-lg-2 adherent-sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-hand-holding-usd me-2"></i>SIFCash-Burkina</h4>
                        <small>Espace Adhérent</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('adherent.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adherent.profile') }}">
                                <i class="fas fa-user me-2"></i>Mon Profil
                            </a>
                        </li>
                        <li class="nav-item">
    <a class="nav-link" href="{{ route('adherent.ayants-droit.index') }}">
        <i class="fas fa-users me-2"></i>Mes Ayants Droit
        @if($adherent && $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0)
            <span class="badge bg-warning badge-document ms-1">
                {{ $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() }}
            </span>
        @endif
    </a>
</li>
                        <li class="nav-item">
    <a class="nav-link" href="{{ route('adherent.documents.index') }}">
        <i class="fas fa-file-alt me-2"></i>Mes Documents
        @if($adherent && $adherent->documents->where('statut', 'soumis')->count() > 0)
            <span class="badge bg-warning badge-document ms-1">
                {{ $adherent->documents->where('statut', 'soumis')->count() }}
            </span>
        @endif
    </a>
</li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adherent.paiements.index') }}">
                                <i class="fas fa-piggy-bank me-2"></i>Mon Épargne
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('adherent.credits.index') }}">
                                <i class="fas fa-credit-card me-2"></i>Mes Crédits
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-item nav-link" href="{{ route('adherent.retraits.index') }}">
                                <i class="fas fa-history me-2"></i>Historique
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link text-start w-100 bg-transparent border-0">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light" style="min-height: 100vh;">
                <!-- Header -->
                <header class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Tableau de Bord Adhérent</h2>
                    <div class="dropdown">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                            @if($adherent)
                                <small class="ms-1">({{ $adherent->membre_id }})</small>
                            @endif
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('adherent.profile') }}">
                                    <i class="fas fa-user me-2"></i>Mon Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </header>

                <!-- Alerts -->
                @include('components.backoffice.alerts')

                <!-- Vérification du profil complet -->
                @if(!$adherent)
                    <div class="alert alert-warning">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Profil incomplet</h5>
                        <p class="mb-2">Votre profil adhérent n'est pas encore complété. Vous devez finaliser votre inscription pour accéder à toutes les fonctionnalités.</p>
                        <a href="{{ route('adherent.inscription') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>Compléter mon inscription
                        </a>
                    </div>
                @endif

                <!-- Statistiques -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Statut Compte</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            @if($adherent)
                                                @if($adherent->isActif())
                                                    <span class="text-success">Actif</span>
                                                @elseif($adherent->isEnAttente())
                                                    <span class="text-warning">En attente</span>
                                                @else
                                                    <span class="text-danger">Inactif</span>
                                                @endif
                                            @else
                                                <span class="text-warning">Incomplet</span>
                                            @endif
                                        </div>
                                        @if($adherent)
                                            <small class="text-muted">{{ $adherent->membre_id }}</small>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Ayants Droit</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $adherent ? $adherent->ayantsDroit->count() : 0 }}/2
                                        </div>
                                        @if($adherent && $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0)
                                            <small class="text-warning">
                                                {{ $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() }} en attente
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Documents</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $validatedRequiredCount ?? 0 }}/{{ $requiredTypesCount ?? 0 }}
                                        </div>
                                        @if($adherent && $adherent->documents->where('statut', 'soumis')->count() > 0)
                                            <small class="text-warning">
                                                {{ $adherent->documents->where('statut', 'soumis')->count() }} en attente
                                            </small>
                                        @endif
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card stat-card shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Solde Épargne</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ number_format($stats['solde_epargne'] ?? 0, 0, ',', ' ') }} FCFA
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-piggy-bank fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-rocket me-2"></i>Actions Rapides</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if($adherent)
                                        <a href="{{ route('adherent.profile') }}" class="btn btn-outline-success text-start">
                                            <i class="fas fa-user me-2"></i>Gérer mon Profil
                                        </a>
                                        <a href="{{ route('adherent.ayants-droit.index') }}" class="btn btn-outline-primary text-start">
                                            <i class="fas fa-users me-2"></i>Gérer mes Ayants Droit
                                            @if($adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0)
                                                <span class="badge bg-warning ms-2">
                                                    {{ $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() }}
                                                </span>
                                            @endif
                                        </a>
                                        <a href="{{ route('adherent.documents.index') }}" class="btn btn-outline-warning text-start">
                                            <i class="fas fa-file-alt me-2"></i>Gérer mes Documents
                                            @if($adherent->documents->where('statut', 'soumis')->count() > 0)
                                                <span class="badge bg-warning ms-2">
                                                    {{ $adherent->documents->where('statut', 'soumis')->count() }}
                                                </span>
                                            @endif
                                        </a>
                                    @else
                                        <a href="{{ route('adherent.inscription') }}" class="btn btn-primary text-start">
                                            <i class="fas fa-user-plus me-2"></i>Compléter mon inscription
                                        </a>
                                    @endif
                                    <button class="btn btn-outline-info text-start">
                                        <i class="fas fa-piggy-bank me-2"></i>Consulter mon Épargne
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-clock me-2"></i>Activité Récente</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @if($adherent)
                                        @if($adherent->documents->where('statut', 'soumis')->count() > 0)
                                            <div class="list-group-item">
                                                <small class="text-warning"><i class="fas fa-clock me-1"></i>En attente</small>
                                                <p class="mb-1">{{ $adherent->documents->where('statut', 'soumis')->count() }} document(s) en validation</p>
                                            </div>
                                        @endif
                                        @if($adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0)
                                            <div class="list-group-item">
                                                <small class="text-warning"><i class="fas fa-clock me-1"></i>En attente</small>
                                                <p class="mb-1">{{ $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() }} ayant(s) droit en validation</p>
                                            </div>
                                        @endif
                                        @if($adherent->isActif())
                                            <div class="list-group-item">
                                                <small class="text-success"><i class="fas fa-check me-1"></i>Terminé</small>
                                                <p class="mb-1">Votre compte a été activé avec succès</p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="list-group-item">
                                            <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Action requise</small>
                                            <p class="mb-1">Complétez votre profil pour activer votre compte</p>
                                        </div>
                                    @endif
                                    <div class="list-group-item">
                                        <small class="text-muted">Bienvenue</small>
                                        <p class="mb-1">Vous êtes connecté à votre espace adhérent</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- État de complétion du profil -->
                @if($adherent)
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0 font-weight-bold"><i class="fas fa-tasks me-2"></i>État de votre dossier</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-user fa-2x {{ $adherent ? 'text-success' : 'text-muted' }} mb-2"></i>
                                            <h6>Profil</h6>
                                            <span class="badge bg-{{ $adherent ? 'success' : 'secondary' }}">
                                                {{ $adherent ? 'Complet' : 'Incomplet' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-users fa-2x {{ $adherent->ayantsDroit->count() > 0 ? 'text-success' : 'text-warning' }} mb-2"></i>
                                            <h6>Ayants Droit</h6>
                                            <span class="badge bg-{{ $adherent->ayantsDroit->count() > 0 ? 'success' : 'warning' }}">
                                                {{ $adherent->ayantsDroit->count() }}/2
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        @php
                                            $documentsValides = $validatedRequiredCount ?? 0;
                                            $documentsTotal = $requiredTypesCount ?? 0;
                                            $documentsColor = $documentsTotal > 0 ? 
                                                ($documentsValides == $documentsTotal ? 'success' : 
                                                ($documentsValides > 0 ? 'warning' : 'danger')) : 
                                                'secondary';
                                            $documentsStatus = $documentsValides == $documentsTotal ? 'Complets' : 'En attente';
                                        @endphp
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-file-alt fa-2x text-{{ $documentsColor }} mb-2"></i>
                                            <h6>Documents</h6>
                                            <span class="badge bg-{{ $documentsColor }}">
                                                {{ $documentsValides }}/{{ $documentsTotal }} {{ $documentsStatus }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
