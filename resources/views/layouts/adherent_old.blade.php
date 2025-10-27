<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Adhérent - SIFCash-Burkina')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .adherent-sidebar {
            background: linear-gradient(135deg, #1cc88a 0%, #36b9cc 100%);
            color: white;
            min-height: 100vh;
        }
        .adherent-sidebar .nav-link { color: white; padding: 0.8rem 1rem; border-radius: 5px; margin: 2px 0; }
        .adherent-sidebar .nav-link:hover, .adherent-sidebar .nav-link.active { background: rgba(255,255,255,0.2); }
        .badge-document { font-size: 0.7em; }
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
                        <a class="nav-link {{ request()->routeIs('adherent.dashboard') ? 'active' : '' }}" href="{{ route('adherent.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adherent.profile*') ? 'active' : '' }}" href="{{ route('adherent.profile') }}">
                            <i class="fas fa-user me-2"></i>Mon Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adherent.ayants-droit*') ? 'active' : '' }}" href="{{ route('adherent.ayants-droit.index') }}">
                            <i class="fas fa-users me-2"></i>Mes Ayants Droit
                            @if(isset($adherent) && $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0)
                                <span class="badge bg-warning badge-document ms-1">{{ $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('adherent.documents*') ? 'active' : '' }}" href="{{ route('adherent.documents.index') }}">
                            <i class="fas fa-file-alt me-2"></i>Mes Documents
                            @if(isset($adherent) && $adherent->documents->where('statut', 'soumis')->count() > 0)
                                <span class="badge bg-warning badge-document ms-1">{{ $adherent->documents->where('statut', 'soumis')->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <!-- Placeholders for future links -->
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-piggy-bank me-2"></i>Mon Épargne</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-credit-card me-2"></i>Mes Crédits</a></li>
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

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 bg-light" style="min-height: 100vh;">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>@yield('title', 'Espace Adhérent')</h2>
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                        @if(isset($adherent))
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

            <!-- Global alerts (if any) -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
