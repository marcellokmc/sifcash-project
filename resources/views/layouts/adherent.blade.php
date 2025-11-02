<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIFCash-Burkina - Espace Adhérent')</title>
    
    <!-- CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- Import des assets compilés -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Variables CSS personnalisées */
        :root {
            --sif-primary: #3b82f6;
            --sif-secondary: #10b981;
            --sif-accent: #f59e0b;
            --sif-purple: #a855f7;
            --sif-pink: #ec4899;
            --sif-sidebar-width: 280px;
            --sif-header-height: 70px;
        }

        /* Layout principal */
        .sif-layout {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Header moderne */
        .sif-header {
            height: var(--sif-header-height);
            background: white;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            backdrop-filter: blur(10px);
        }

        .sif-logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--sif-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sidebar moderne */
        .sif-sidebar {
            width: var(--sif-sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            position: fixed;
            top: var(--sif-header-height);
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sif-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sif-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .adherent-sidebar .nav-link { 
            color: white; 
            padding: 0.8rem 1rem; 
            border-radius: 5px; 
            margin: 2px 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .adherent-sidebar .nav-link:hover, 
        .adherent-sidebar .nav-link.active { 
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        
        .adherent-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        
        .badge-document { 
            font-size: 0.7em;
            margin-left: auto;
        }
        
        .nav-link .badge {
            transition: all 0.3s ease;
        }
        
        .nav-link:hover .badge {
            transform: scale(1.1);
        }
        
        .plan-highlight {
            background: linear-gradient(45deg, rgba(28, 200, 138, 0.1), rgba(54, 185, 204, 0.1));
            border-left: 3px solid var(--primary-color);
        }
        
        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5rem 1rem;
            margin-bottom: 0.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-item .nav-link {
            font-size: 0.9rem;
        }
        
        .nav-item .nav-link i {
            font-size: 1rem;
        }
        
        .badge-document {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
            100% {
                opacity: 1;
            }
        }
        
        .main-content {
            min-height: 100vh;
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            border-left: 4px solid var(--success-color);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            padding: 0.25em 0.5em;
        }
        
        @media (max-width: 991.98px) {
            .adherent-sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                width: 280px;
            }
            
            .adherent-sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .nav-section-title {
                font-size: 0.65rem;
                padding: 0.4rem 0.8rem;
            }
            
            .adherent-sidebar .nav-link {
                padding: 0.6rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .badge-document {
                font-size: 0.65em;
            }
        }
        
        /* Overlay pour mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Overlay mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Toggle for Mobile -->
            <button class="navbar-toggler d-lg-none position-fixed btn btn-primary" style="top: 15px; left: 15px; z-index: 1001; border-radius: 50%; width: 45px; height: 45px;" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Sidebar Adhérent -->
            <nav class="col-md-3 col-lg-2 adherent-sidebar" id="sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-hand-holding-usd me-2"></i>SIFCash-Burkina</h4>
                        <small>Espace Adhérent</small>
                    </div>
                    
                    <!-- User Profile -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=random' }}" 
                                class="rounded-circle" 
                                width="80" 
                                height="80"
                                alt="Photo de profil">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white">
                                <i class="fas fa-check text-white" style="font-size: 0.8rem;"></i>
                            </span>
                        </div>
                        <h6 class="mt-2 mb-0">{{ Auth::user()->name }}</h6>
                        <small class="text-white-50">Membre depuis {{ Auth::user()->created_at->format('m/Y') }}</small>
                    </div>
                    <hr class="bg-white">
                    
                    <!-- Navigation -->
                    @php
                        // Variable partagée pour éviter de répéter la requête
                        $adherentId = Auth::user()->adherent?->id;
                    @endphp
                    <ul class="nav flex-column">
                        <!-- Tableau de bord -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.dashboard*') ? 'active' : '' }}" href="{{ route('adherent.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Tableau de bord</span>
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-title">MON PROFIL</div>
                        </li>
                        
                        <!-- Profil et informations personnelles -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.profile*') ? 'active' : '' }}" href="{{ route('adherent.profile') }}">
                                <i class="fas fa-user"></i>
                                <span>Mon Profil</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.ayants-droit*') ? 'active' : '' }}" href="{{ route('adherent.ayants-droit.index') }}">
                                <i class="fas fa-users"></i>
                                <span>Mes Ayants Droit</span>
                                @php
                                    $ayantsDroitEnAttente = $adherentId ? \App\Models\AyantDroit::where('adherent_id', $adherentId)->where('statut_validation', 'en_attente')->count() : 0;
                                @endphp
                                @if($ayantsDroitEnAttente > 0)
                                    <span class="badge bg-warning badge-document">{{ $ayantsDroitEnAttente }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.documents*') ? 'active' : '' }}" href="{{ route('adherent.documents.index') }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Mes Documents</span>
                                @php
                                    $documentsEnAttente = $adherentId ? \App\Models\Document::where('adherent_id', $adherentId)->where('statut', 'soumis')->count() : 0;
                                @endphp
                                @if($documentsEnAttente > 0)
                                    <span class="badge bg-warning badge-document">{{ $documentsEnAttente }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-title">ÉPARGNE & PLANS</div>
                        </li>
                        
                        <!-- Plans et adhésions -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.plans*') ? 'active' : '' }}" href="{{ route('adherent.plans.index') }}">
                                <i class="fas fa-chart-line"></i>
                                <span>Plans Disponibles</span>
                                @php
                                    $plansCount = \App\Models\Plan::where('actif', true)->count();
                                @endphp
                                @if($plansCount > 0)
                                    <span class="badge bg-info badge-document">{{ $plansCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.adhesions*') ? 'active' : '' }}" href="{{ route('adherent.adhesions.index') }}">
                                <i class="fas fa-handshake"></i>
                                <span>Mes Adhésions</span>
                                @php
                                    $adhesionsActives = $adherentId ? \App\Models\Adhesion::where('adherent_id', $adherentId)->where('statut', 'actif')->count() : 0;
                                @endphp
                                @if($adhesionsActives > 0)
                                    <span class="badge bg-success badge-document">{{ $adhesionsActives }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-title">TRANSACTIONS</div>
                        </li>
                        
                        <!-- Paiements et épargne -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.paiements*') ? 'active' : '' }}" href="{{ route('adherent.paiements.index') }}">
                                <i class="fas fa-piggy-bank"></i>
                                <span>Dépôts & Épargne</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.retraits*') ? 'active' : '' }}" href="{{ route('adherent.retraits.index') }}">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Retraits</span>
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-title">CRÉDITS</div>
                        </li>
                        
                        <!-- Crédits -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.credits.index', 'adherent.credits.show', 'adherent.credits.create') ? 'active' : '' }}" href="{{ route('adherent.credits.index') }}">
                                <i class="fas fa-credit-card"></i>
                                <span>Mes Crédits</span>
                                @php
                                    $creditsActifs = $adherentId ? \App\Models\Credit::where('adherent_id', $adherentId)->where('etat', 'actif')->count() : 0;
                                @endphp
                                @if($creditsActifs > 0)
                                    <span class="badge bg-primary badge-document">{{ $creditsActifs }}</span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Separator -->
                        <li class="nav-item mt-3">
                            <div class="nav-section-title">NOTIFICATIONS</div>
                        </li>
                        
                        <!-- Notifications -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('adherent.notifications*') ? 'active' : '' }}" href="{{ route('adherent.notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                <span>Mes Notifications</span>
                                @php
                                    $notificationsNonLues = \App\Models\Notification::where('user_id', Auth::id())->where('lu', false)->count();
                                @endphp
                                @if($notificationsNonLues > 0)
                                    <span class="badge bg-danger badge-document">{{ $notificationsNonLues }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Logout Button -->
                    <div class="mt-4 pt-3 border-top border-white-10">
                        <form method="POST" action="{{ route('logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Alerts -->
                @include('components.alerts')
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            
            // Empêcher le scroll du body quand le sidebar est ouvert
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.querySelector('.navbar-toggler');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target) && window.innerWidth <= 991.98) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
        
        // Fermer le sidebar sur changement d'orientation mobile
        window.addEventListener('orientationchange', function() {
            setTimeout(function() {
                if (window.innerWidth > 991.98) {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebar-overlay');
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }, 100);
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
