<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIF Burkina')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    
    <style>
        /* Layout principal avec background fixe */
        body.sif-layout { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important; 
            min-height: 100vh !important;
            background-attachment: fixed !important;
        }
        
        /* Surcharge pour s'assurer que le background reste */
        body, html {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
        }
        
        .sif-header { 
            height: 70px; 
            background: white !important; 
            box-shadow: 0 2px 20px rgba(0,0,0,0.08); 
            position: fixed; 
            top: 0; 
            width: 100%; 
            z-index: 1030; 
        }
        
        .sif-sidebar { 
            width: 280px; 
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%) !important; 
            position: fixed; 
            top: 70px; 
            left: 0; 
            bottom: 0; 
            overflow-y: auto; 
            z-index: 1020; 
        }
        
        .sif-main { 
            margin-left: 280px; 
            margin-top: 70px; 
            padding: 2rem; 
            background: transparent !important;
            min-height: calc(100vh - 70px);
        }
        
        /* Navigation styles */
        .sif-nav-dropdown { cursor: pointer; }
        .sif-nav-dropdown-menu { 
            max-height: 0; 
            overflow: hidden; 
            transition: max-height 0.3s ease; 
            background: rgba(0,0,0,0.2); 
            border-radius: 8px; 
        }
        .sif-nav-dropdown.open .sif-nav-dropdown-menu { max-height: 300px; }
        .sif-nav-link { 
            color: rgba(255,255,255,0.8); 
            padding: 0.875rem 1rem; 
            display: flex; 
            align-items: center; 
            text-decoration: none; 
            border-radius: 12px; 
            margin: 0 0.75rem; 
            transition: all 0.3s ease; 
        }
        .sif-nav-link:hover, .sif-nav-link.active { 
            color: white; 
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%); 
        }
        .sif-nav-dropdown-item { 
            padding: 0.625rem 3rem; 
            color: rgba(255,255,255,0.7); 
            display: block; 
            text-decoration: none; 
        }
        .sif-nav-dropdown-item:hover { 
            color: white; 
            background: rgba(255,255,255,0.1); 
        }
        .sif-nav-title { 
            font-size: 0.75rem; 
            color: rgba(255,255,255,0.6); 
            text-transform: uppercase; 
            padding: 0 1.5rem; 
            margin: 1rem 0 0.5rem; 
        }
        
        /* Responsive */
        @media (max-width: 991.98px) {
            .sif-sidebar { transform: translateX(-100%); }
            .sif-sidebar.show { transform: translateX(0); }
            .sif-main { margin-left: 0; }
        }
        
        /* Surcharge pour éviter les conflits avec d'autres styles */
        .container, .container-fluid, .row, .col, [class*="col-"] {
            background: transparent !important;
        }
        
        /* Cards et autres éléments conservent leur background */
        .card, .btn, .form-control, .badge {
            /* Garder leur background normal */
        }
    </style>
</head>
<body class="sif-layout sif-layout-bg-force">
    <!-- Header -->
    <header class="sif-header">
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
                <div class="col-auto d-lg-none">
                    <button class="btn btn-link" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="col-auto">
                    <h4 class="mb-0"><i class="fas fa-hand-holding-usd me-2 text-primary"></i>SIF Burkina</h4>
                </div>
                <div class="col"></div>
                <div class="col-auto">
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User') }}" 
                                 class="rounded-circle me-2" width="40" height="40">
                            <span>{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('adherent.profile') }}"><i class="fas fa-user me-2"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <aside class="sif-sidebar" id="sidebar">
        <div class="p-3">
            <!-- Profile -->
            <div class="text-center mb-4 pb-3 border-bottom border-secondary">
                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User') }}" 
                     class="rounded-circle mb-2" width="60" height="60">
                <h6 class="text-white mb-0">{{ auth()->user()->name ?? 'Utilisateur' }}</h6>
                <small class="text-white-50">Membre SIF</small>
            </div>
            
            <!-- Navigation -->
            <nav>
                @php $adherent = auth()->user()->adherent; $adherentId = $adherent?->id; @endphp
                
                <!-- Dashboard -->
                <a href="{{ route('adherent.dashboard') }}" class="sif-nav-link {{ request()->routeIs('adherent.dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-3"></i>Tableau de bord
                </a>
                
                <div class="sif-nav-title">Mon Compte</div>
                
                <!-- Mon Profil -->
                @php
                    $ayantsDroitEnAttente = $adherentId ? \App\Models\AyantDroit::where('adherent_id', $adherentId)->where('statut_validation', 'en_attente')->count() : 0;
                    $documentsEnAttente = $adherentId ? \App\Models\Document::where('adherent_id', $adherentId)->where('statut', 'soumis')->count() : 0;
                    $totalProfileAttente = $ayantsDroitEnAttente + $documentsEnAttente;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.profile*', 'adherent.ayants-droit*', 'adherent.documents*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-user me-3"></i>Mon Profil
                        @if($totalProfileAttente > 0)
                            <span class="badge bg-danger ms-2">{{ $totalProfileAttente > 9 ? '9+' : $totalProfileAttente }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.profile') }}" class="sif-nav-dropdown-item">Informations personnelles</a>
                        <a href="{{ route('adherent.profile.edit') }}" class="sif-nav-dropdown-item">Modifier profil</a>
                        <a href="{{ route('adherent.ayants-droit.index') }}" class="sif-nav-dropdown-item">
                            Mes Ayants Droit
                            @if($ayantsDroitEnAttente > 0)
                                <span class="badge bg-warning ms-2">{{ $ayantsDroitEnAttente }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.documents.index') }}" class="sif-nav-dropdown-item">
                            Mes Documents
                            @if($documentsEnAttente > 0)
                                <span class="badge bg-info ms-2">{{ $documentsEnAttente }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Épargne & Plans</div>
                
                <!-- Plans & Adhésions -->
                @php
                    $plansDisponibles = \App\Models\Plan::where('actif', true)->count();
                    $mesAdhesions = $adherentId ? \App\Models\Adhesion::where('adherent_id', $adherentId)->where('statut', 'actif')->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.plans*', 'adherent.adhesions*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-chart-line me-3"></i>Plans & Adhésions
                        @if($plansDisponibles > 0)
                            <span class="badge bg-success ms-2">{{ $plansDisponibles }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.plans.index') }}" class="sif-nav-dropdown-item">
                            Plans disponibles
                            @if($plansDisponibles > 0)
                                <span class="badge bg-success ms-2">{{ $plansDisponibles }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.adhesions.index') }}" class="sif-nav-dropdown-item">
                            Mes adhésions
                            @if($mesAdhesions > 0)
                                <span class="badge bg-primary ms-2">{{ $mesAdhesions }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.adhesions.create') }}" class="sif-nav-dropdown-item">Nouvelle adhésion</a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Transactions</div>
                
                <!-- Dépôts & Retraits -->
                @php
                    $retraitsEnCours = $adherentId ? \App\Models\DemandeRetrait::where('adherent_id', $adherentId)->where('statut', 'en_attente')->count() : 0;
                    $derniersPaiements = $adherentId ? \App\Models\Paiement::where('adherent_id', $adherentId)->where('created_at', '>=', now()->subDays(30))->count() : 0;
                    $totalTransactions = $retraitsEnCours; // On compte seulement les transactions en attente
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.paiements*', 'adherent.retraits*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-exchange-alt me-3"></i>Dépôts & Retraits
                        @if($totalTransactions > 0)
                            <span class="badge bg-warning ms-2">{{ $totalTransactions }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.paiements.index') }}" class="sif-nav-dropdown-item">
                            Historique dépôts
                            @if($derniersPaiements > 0)
                                <span class="badge bg-success ms-2">{{ $derniersPaiements }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.paiements.create') }}" class="sif-nav-dropdown-item">Nouveau dépôt</a>
                        <a href="{{ route('adherent.retraits.index') }}" class="sif-nav-dropdown-item">
                            Mes retraits
                            @if($retraitsEnCours > 0)
                                <span class="badge bg-warning ms-2">{{ $retraitsEnCours }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.retraits.create') }}" class="sif-nav-dropdown-item">Demander retrait</a>
                    </div>
                </div>
                
                <!-- Crédits -->
                @php
                    $creditsActifs = $adherentId ? \App\Models\Credit::where('adherent_id', $adherentId)->whereIn('statut', ['approuvé', 'actif'])->count() : 0;
                    $creditsEnAttente = $adherentId ? \App\Models\Credit::where('adherent_id', $adherentId)->where('statut', 'en_attente')->count() : 0;
                    $totalCredits = $creditsActifs + $creditsEnAttente;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.credits*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-credit-card me-3"></i>Mes Crédits
                        @if($totalCredits > 0)
                            <span class="badge {{ $creditsEnAttente > 0 ? 'bg-warning' : 'bg-success' }} ms-2">{{ $totalCredits }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.credits.index') }}" class="sif-nav-dropdown-item">
                            Mes demandes
                            @if($creditsEnAttente > 0)
                                <span class="badge bg-warning ms-2">{{ $creditsEnAttente }}</span>
                            @elseif($creditsActifs > 0)
                                <span class="badge bg-success ms-2">{{ $creditsActifs }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.credits.create') }}" class="sif-nav-dropdown-item">Nouvelle demande</a>
                        <a href="{{ route('adherent.credits.paiements.index') }}" class="sif-nav-dropdown-item">Paiements crédit</a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Support</div>
                
                <a href="{{ route('adherent.notifications.index') }}" class="sif-nav-link {{ request()->routeIs('adherent.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell me-3"></i>Notifications
                    @php
                        $unreadCount = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
                
                @if($adherent && $adherent->isActif())
                    <a href="{{ route('adherent.contrat.download') }}" class="sif-nav-link">
                        <i class="fas fa-file-pdf me-3"></i>Télécharger contrat
                    </a>
                @endif
                
                <!-- Déconnexion -->
                <hr class="border-secondary my-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sif-nav-link w-100 bg-transparent border-0 text-start text-danger">
                        <i class="fas fa-sign-out-alt me-3"></i>Déconnexion
                    </button>
                </form>
            </nav>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="sif-main">
        @include('components.alerts')
        @yield('content')
    </main>
    
    <!-- Quick Actions FAB -->
    <div class="position-fixed" style="bottom: 2rem; right: 2rem; z-index: 1025;">
        <div class="dropdown dropup">
            <button class="btn btn-primary rounded-circle" style="width: 60px; height: 60px;" data-bs-toggle="dropdown">
                <i class="fas fa-plus fa-lg"></i>
            </button>
            <ul class="dropdown-menu mb-2">
                <li><a class="dropdown-item" href="{{ route('adherent.paiements.create') }}">
                    <i class="fas fa-plus-circle text-success me-2"></i>Nouveau dépôt
                </a></li>
                <li><a class="dropdown-item" href="{{ route('adherent.credits.create') }}">
                    <i class="fas fa-hand-holding-usd text-warning me-2"></i>Demander crédit
                </a></li>
                <li><a class="dropdown-item" href="{{ route('adherent.retraits.create') }}">
                    <i class="fas fa-money-bill-wave text-info me-2"></i>Demander retrait
                </a></li>
            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Dropdown navigation
        document.querySelectorAll('.sif-nav-dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                if (e.target.closest('.sif-nav-dropdown-item')) return;
                dropdown.classList.toggle('open');
            });
        });
    </script>
</body>
</html>