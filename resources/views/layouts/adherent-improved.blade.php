<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SIF Burkina</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .main-sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .content-wrapper {
            min-height: 100vh;
            background: transparent;
        }
        
        .main-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        
        .user-avatar {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            border-radius: 50%;
            font-size: 14px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Flash Messages (cachés, gérés par JS) -->
    @if(session('success'))
        <div data-flash="success" style="display: none;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div data-flash="error" style="display: none;">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div data-flash="warning" style="display: none;">{{ session('warning') }}</div>
    @endif
    @if(session('info'))
        <div data-flash="info" style="display: none;">{{ session('info') }}</div>
    @endif

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="main-sidebar d-flex flex-column p-0" style="width: 280px; min-height: 100vh;">
            <!-- Logo -->
            <div class="p-4 border-bottom border-secondary">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle p-2 me-3">
                        <i class="fas fa-hand-holding-usd text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="text-white mb-0 fw-bold">SIF Burkina</h5>
                        <small class="text-light opacity-75">Espace Adhérent</small>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-grow-1 py-3">
                <ul class="nav flex-column sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.dashboard') ? 'active' : '' }}" 
                           href="{{ route('adherent.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-3"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.profile*') ? 'active' : '' }}" 
                           href="{{ route('adherent.profile') }}">
                            <i class="fas fa-user-circle me-3"></i>
                            <span>Mon Profil</span>
                            @if(auth()->user()->adherent && !auth()->user()->adherent->isActif())
                                <span class="badge bg-warning ms-auto">Incomplet</span>
                            @endif
                        </a>
                    </li>

                    <!-- Section Plans et Adhésions -->
                    <div class="nav-section mt-3">
                        <h6 class="px-4 text-uppercase text-light opacity-50 fw-bold small">Épargne</h6>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.plans*') ? 'active' : '' }}" 
                               href="{{ route('adherent.plans.index') }}">
                                <i class="fas fa-list-alt me-3"></i>
                                <span>Plans disponibles</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.adhesions*') ? 'active' : '' }}" 
                               href="{{ route('adherent.adhesions.index') }}">
                                <i class="fas fa-handshake me-3"></i>
                                <span>Mes adhésions</span>
                                @if(auth()->user()->adherent)
                                    @php
                                        $activeAdhesions = auth()->user()->adherent->adhesions()->where('statut', 'actif')->count();
                                    @endphp
                                    @if($activeAdhesions > 0)
                                        <span class="badge bg-success ms-auto">{{ $activeAdhesions }}</span>
                                    @endif
                                @endif
                            </a>
                        </li>
                    </div>

                    <!-- Section Crédits -->
                    <div class="nav-section mt-3">
                        <h6 class="px-4 text-uppercase text-light opacity-50 fw-bold small">Crédit</h6>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.credits*') ? 'active' : '' }}" 
                               href="{{ route('adherent.credits.index') }}">
                                <i class="fas fa-credit-card me-3"></i>
                                <span>Mes crédits</span>
                                @if(auth()->user()->adherent)
                                    @php
                                        $pendingCredits = auth()->user()->adherent->credits()->where('statut', 'en_attente')->count();
                                    @endphp
                                    @if($pendingCredits > 0)
                                        <span class="badge bg-warning ms-auto">{{ $pendingCredits }}</span>
                                    @endif
                                @endif
                            </a>
                        </li>
                    </div>

                    <!-- Section Services -->
                    <div class="nav-section mt-3">
                        <h6 class="px-4 text-uppercase text-light opacity-50 fw-bold small">Services</h6>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.documents*') ? 'active' : '' }}" 
                               href="{{ route('adherent.documents.index') }}">
                                <i class="fas fa-folder-open me-3"></i>
                                <span>Mes documents</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center py-3 px-4 {{ request()->routeIs('adherent.ayants-droit*') ? 'active' : '' }}" 
                               href="{{ route('adherent.ayants-droit.index') }}">
                                <i class="fas fa-users me-3"></i>
                                <span>Ayants droit</span>
                            </a>
                        </li>
                    </div>
                </ul>
            </div>

            <!-- User Info -->
            <div class="p-4 border-top border-secondary">
                @if(auth()->user()->adherent)
                    <div class="d-flex align-items-center text-light">
                        <div class="user-avatar me-3">
                            {{ substr(auth()->user()->adherent->prenom, 0, 1) }}{{ substr(auth()->user()->adherent->nom, 0, 1) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ auth()->user()->adherent->nom_complet }}</div>
                            <small class="text-light opacity-75">{{ auth()->user()->adherent->membre_id }}</small>
                        </div>
                    </div>
                @endif
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Header -->
            <header class="main-header sticky-top p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h4>
                        @hasSection('breadcrumb')
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 small">
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <!-- Notifications -->
                        <div class="dropdown" data-notification-dropdown>
                            <button class="btn btn-link text-dark position-relative p-2" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge d-none" 
                                      data-notification-badge>0</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow" style="width: 350px; max-height: 400px; overflow-y: auto;">
                                <div class="dropdown-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Notifications</h6>
                                    <button class="btn btn-sm btn-link text-primary p-0">Tout marquer lu</button>
                                </div>
                                <div class="notification-dropdown-content">
                                    <!-- Contenu chargé dynamiquement -->
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="dropdown">
                            <button class="btn btn-link text-dark d-flex align-items-center p-2" data-bs-toggle="dropdown">
                                <div class="user-avatar me-2">
                                    @if(auth()->user()->adherent)
                                        {{ substr(auth()->user()->adherent->prenom, 0, 1) }}{{ substr(auth()->user()->adherent->nom, 0, 1) }}
                                    @else
                                        {{ substr(auth()->user()->name, 0, 2) }}
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down small"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow">
                                <div class="dropdown-header">
                                    <strong>{{ auth()->user()->adherent->nom_complet ?? auth()->user()->name }}</strong>
                                    <div class="small text-muted">{{ auth()->user()->email }}</div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('adherent.profile') }}">
                                    <i class="fas fa-user me-2"></i>Mon profil
                                </a>
                                <a class="dropdown-item" href="{{ route('adherent.notifications.index') }}">
                                    <i class="fas fa-bell me-2"></i>Notifications
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"
                                            data-confirm="Êtes-vous sûr de vouloir vous déconnecter ?">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-4 content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>