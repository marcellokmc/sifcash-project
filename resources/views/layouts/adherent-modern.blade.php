<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIFCash-Burkina')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/adherent-mobile.css') }}">
    
    <style>
        /* Mobile-first Layout */
        body.sif-layout { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important; 
            min-height: 100vh !important;
            background-attachment: scroll !important;
            font-size: 14px;
        }
        
        body, html {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            overflow-x: hidden;
        }
        
        /* Header - Mobile first */
        .sif-header { 
            height: 60px; 
            background: white !important; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.08); 
            position: fixed; 
            top: 0; 
            width: 100%; 
            z-index: 1030; 
        }
        
        .sif-header h4 {
            font-size: 1rem;
        }
        
        .sif-header .dropdown img {
            width: 32px;
            height: 32px;
        }
        
        .sif-header .dropdown span {
            display: none;
        }
        
        /* Sidebar - Mobile first (hidden by default) */
        .sif-sidebar { 
            width: 260px; 
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%) !important; 
            position: fixed; 
            top: 60px; 
            left: 0; 
            bottom: 0; 
            overflow-y: auto; 
            z-index: 1020; 
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sif-sidebar.show { 
            transform: translateX(0);
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }
        
        /* Overlay pour mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1019;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Main content - Mobile first */
        .sif-main { 
            margin-left: 0;
            margin-top: 60px; 
            padding: 1rem; 
            background: transparent !important;
            min-height: calc(100vh - 60px);
            width: 100%;
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
        .sif-nav-dropdown.open .sif-nav-dropdown-menu { max-height: 500px; }
        .sif-nav-link { 
            color: rgba(255,255,255,0.8); 
            padding: 0.75rem 1rem; 
            display: flex; 
            align-items: center; 
            text-decoration: none; 
            border-radius: 8px; 
            margin: 0 0.5rem; 
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .sif-nav-link:hover, .sif-nav-link.active { 
            color: white; 
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%); 
        }
        .sif-nav-dropdown-item { 
            padding: 0.5rem 2.5rem; 
            color: rgba(255,255,255,0.7); 
            display: block; 
            text-decoration: none;
            font-size: 0.85rem;
        }
        .sif-nav-dropdown-item:hover { 
            color: white; 
            background: rgba(255,255,255,0.1); 
        }
        .sif-nav-title { 
            font-size: 0.7rem; 
            color: rgba(255,255,255,0.6); 
            text-transform: uppercase; 
            padding: 0 1rem; 
            margin: 0.75rem 0 0.5rem; 
        }
        
        /* Surcharge pour éviter les conflits */
        .container, .container-fluid, .row, .col, [class*="col-"] {
            background: transparent !important;
        }
        
        /* Tablet (>= 768px) */
        @media (min-width: 768px) {
            body.sif-layout {
                font-size: 15px;
            }
            
            .sif-header h4 {
                font-size: 1.1rem;
            }
            
            .sif-header .dropdown span {
                display: inline;
                font-size: 0.9rem;
            }
            
            .sif-main {
                padding: 1.5rem;
            }
        }
        
        /* Desktop (>= 992px) - Optimisé pour 15 pouces */
        @media (min-width: 992px) {
            body.sif-layout {
                font-size: 15px;
                background-attachment: fixed !important;
            }
            
            .sif-header {
                height: 65px;
            }
            
            .sif-header h4 {
                font-size: 1.15rem;
            }
            
            .sif-header .dropdown img {
                width: 36px;
                height: 36px;
            }
            
            .sif-header .dropdown span {
                font-size: 0.95rem;
            }
            
            .sif-sidebar {
                width: 260px;
                top: 65px;
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                display: none !important;
            }
            
            .sif-main {
                margin-left: 260px;
                margin-top: 65px;
                padding: 1.5rem;
                max-width: calc(100vw - 260px);
            }
            
            .sif-nav-link {
                padding: 0.75rem 0.875rem;
                margin: 0 0.5rem;
                border-radius: 10px;
                font-size: 0.9rem;
            }
            
            .sif-nav-dropdown-item {
                padding: 0.5rem 2.5rem;
                font-size: 0.85rem;
            }
            
            .sif-nav-title {
                font-size: 0.7rem;
                padding: 0 1rem;
                margin: 0.875rem 0 0.5rem;
            }
        }
        
        /* Large Desktop (>= 1400px) */
        @media (min-width: 1400px) {
            body.sif-layout {
                font-size: 16px;
            }
            
            .sif-header {
                height: 70px;
            }
            
            .sif-header h4 {
                font-size: 1.25rem;
            }
            
            .sif-sidebar {
                width: 280px;
                top: 70px;
            }
            
            .sif-main {
                margin-left: 280px;
                margin-top: 70px;
                padding: 2rem;
                max-width: calc(100vw - 280px);
            }
            
            .sif-nav-link {
                padding: 0.875rem 1rem;
                margin: 0 0.75rem;
                font-size: 1rem;
            }
            
            .sif-nav-dropdown-item {
                padding: 0.625rem 3rem;
                font-size: 0.9rem;
            }
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
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('img/SIF logo .jpg') }}" alt="SIFcash-Burkina Logo" class="me-2" style="height: 45px; width: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <h4 class="mb-0">SIFCash-Burkina</h4>
                    </div>
                </div>
                <div class="col"></div>
                
                <!-- Notifications Bell -->
                <div class="col-auto">
                    <a href="{{ route('adherent.notifications.index') }}" class="position-relative d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; text-decoration: none;">
                        <i class="fas fa-bell" style="font-size: 1.25rem; color: #64748b;"></i>
                        @php
                            try {
                                $unreadNotifCount = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
                            } catch (\Exception $e) {
                                $unreadNotifCount = 0;
                            }
                        @endphp
                        @if($unreadNotifCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25rem 0.4rem;">
                                {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                            </span>
                        @endif
                    </a>
                </div>
                
                <!-- User Profile -->
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
                    <i class="fas fa-home me-3"></i>Accueil
                </a>
                
                <div class="sif-nav-title">Compte & Profil</div>
                
                <!-- Mon Profil -->
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.profile*', 'adherent.password*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-user-circle me-3"></i>Profil
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.profile') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-id-card me-2"></i>Consulter
                        </a>
                        <a href="{{ route('adherent.profile.edit') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('adherent.password.edit') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-key me-2"></i>Mot de passe
                        </a>
                    </div>
                </div>
                
                <!-- Ayants Droit -->
                @php
                    $ayantsDroitEnAttente = $adherentId ? \App\Models\AyantDroit::where('adherent_id', $adherentId)->where('statut_validation', 'en_attente')->count() : 0;
                    $totalAyantsDroit = $adherentId ? \App\Models\AyantDroit::where('adherent_id', $adherentId)->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.ayants-droit*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-users me-3"></i>Bénéficiaires
                        @if($ayantsDroitEnAttente > 0)
                            <span class="badge bg-warning ms-2">{{ $ayantsDroitEnAttente }}</span>
                        @elseif($totalAyantsDroit > 0)
                            <span class="badge bg-info ms-2">{{ $totalAyantsDroit }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.ayants-droit.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-list me-2"></i>Liste
                            @if($totalAyantsDroit > 0)
                                <span class="badge bg-info ms-2">{{ $totalAyantsDroit }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.ayants-droit.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-user-plus me-2"></i>Ajouter
                        </a>
                    </div>
                </div>
                
                <!-- Documents -->
                @php
                    $documentsEnAttente = $adherentId ? \App\Models\Document::where('adherent_id', $adherentId)->where('statut', 'soumis')->count() : 0;
                    $totalDocuments = $adherentId ? \App\Models\Document::where('adherent_id', $adherentId)->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.documents*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-folder-open me-3"></i>Documents
                        @if($documentsEnAttente > 0)
                            <span class="badge bg-warning ms-2">{{ $documentsEnAttente }}</span>
                        @elseif($totalDocuments > 0)
                            <span class="badge bg-secondary ms-2">{{ $totalDocuments }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.documents.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-list me-2"></i>Liste
                            @if($totalDocuments > 0)
                                <span class="badge bg-secondary ms-2">{{ $totalDocuments }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.documents.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-upload me-2"></i>Ajouter
                        </a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Épargne & Investissement</div>
                
                <!-- Plans d'Épargne -->
                @php
                    $plansDisponibles = \App\Models\Plan::where('actif', true)->count();
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.plans*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-chart-pie me-3"></i>Plans
                        @if($plansDisponibles > 0)
                            <span class="badge bg-success ms-2">{{ $plansDisponibles }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.plans.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-eye me-2"></i>Catalogue
                            @if($plansDisponibles > 0)
                                <span class="badge bg-success ms-2">{{ $plansDisponibles }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- Mes Adhésions -->
                @php
                    $mesAdhesions = $adherentId ? \App\Models\Adhesion::where('adherent_id', $adherentId)->where('statut', 'actif')->count() : 0;
                    $adhesionsEnAttente = $adherentId ? \App\Models\Adhesion::where('adherent_id', $adherentId)->where('statut', 'en_attente')->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.adhesions*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-handshake me-3"></i>Adhésions
                        @if($adhesionsEnAttente > 0)
                            <span class="badge bg-warning ms-2">{{ $adhesionsEnAttente }}</span>
                        @elseif($mesAdhesions > 0)
                            <span class="badge bg-primary ms-2">{{ $mesAdhesions }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.adhesions.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-list me-2"></i>Liste
                            @if($mesAdhesions > 0)
                                <span class="badge bg-primary ms-2">{{ $mesAdhesions }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.adhesions.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-plus me-2"></i>Souscrire
                        </a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Transactions & Opérations</div>
                
                <!-- Dépôts -->
                @php
                    $pagementsEnAttente = $adherentId ? \App\Models\Paiement::where('adherent_id', $adherentId)->where('statut', 'soumis')->count() : 0;
                    $derniersPaiements = $adherentId ? \App\Models\Paiement::where('adherent_id', $adherentId)->where('created_at', '>=', now()->subDays(30))->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.paiements*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-plus-circle me-3"></i>Versements
                        @if($pagementsEnAttente > 0)
                            <span class="badge bg-warning ms-2">{{ $pagementsEnAttente }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.paiements.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-history me-2"></i>Historique
                            @if($derniersPaiements > 0)
                                <span class="badge bg-info ms-2">{{ $derniersPaiements }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.paiements.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-arrow-up me-2"></i>Verser
                        </a>
                    </div>
                </div>
                
                <!-- Retraits -->
                @php
                    $retraitsEnCours = $adherentId ? \App\Models\DemandeRetrait::where('adherent_id', $adherentId)->where('statut', 'en_attente')->count() : 0;
                    $totalRetraits = $adherentId ? \App\Models\DemandeRetrait::where('adherent_id', $adherentId)->count() : 0;
                @endphp
                <div class="sif-nav-dropdown {{ request()->routeIs('adherent.retraits*') ? 'open' : '' }}">
                    <div class="sif-nav-link">
                        <i class="fas fa-minus-circle me-3"></i>Retraits
                        @if($retraitsEnCours > 0)
                            <span class="badge bg-warning ms-2">{{ $retraitsEnCours }}</span>
                        @elseif($totalRetraits > 0)
                            <span class="badge bg-secondary ms-2">{{ $totalRetraits }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.retraits.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-list me-2"></i>Demandes
                            @if($totalRetraits > 0)
                                <span class="badge bg-secondary ms-2">{{ $totalRetraits }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.retraits.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-arrow-down me-2"></i>Retirer
                        </a>
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
                        <i class="fas fa-credit-card me-3"></i>Crédits
                        @if($totalCredits > 0)
                            <span class="badge {{ $creditsEnAttente > 0 ? 'bg-warning' : 'bg-success' }} ms-2">{{ $totalCredits }}</span>
                        @endif
                        <i class="fas fa-chevron-down ms-auto"></i>
                    </div>
                    <div class="sif-nav-dropdown-menu">
                        <a href="{{ route('adherent.credits.index') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-list me-2"></i>Demandes
                            @if($creditsEnAttente > 0)
                                <span class="badge bg-warning ms-2">{{ $creditsEnAttente }}</span>
                            @elseif($creditsActifs > 0)
                                <span class="badge bg-success ms-2">{{ $creditsActifs }}</span>
                            @endif
                        </a>
                        <a href="{{ route('adherent.credits.create') }}" class="sif-nav-dropdown-item">
                            <i class="fas fa-pen me-2"></i>Demander
                        </a>
                    </div>
                </div>
                
                <div class="sif-nav-title">Aide & Informations</div>
                
                <!-- Notifications -->
                <a href="{{ route('adherent.notifications.index') }}" class="sif-nav-link {{ request()->routeIs('adherent.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell me-3"></i>Notifications
                    @php
                        try {
                            $unreadCount = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
                        } catch (\Exception $e) {
                            $unreadCount = 0;
                        }
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-auto">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
                
                <!-- Contrat -->
                @if($adherent && $adherent->isActif())
                    <a href="{{ route('adherent.contrat.download') }}" class="sif-nav-link">
                        <i class="fas fa-file-pdf me-3"></i>Contrat
                    </a>
                @endif
                
                <!-- Aide -->
                <a href="#" class="sif-nav-link" onclick="alert('📞 Contact Support:\n\nTéléphone: +226 25 XX XX XX\nEmail: support@sifcash-burkina.bf\n\nDisponible du lundi au vendredi\nde 8h à 17h'); return false;">
                    <i class="fas fa-headset me-3"></i>Support
                </a>
                
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
    
    <!-- Overlay pour fermer la sidebar sur mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
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
        // Toggle sidebar mobile avec overlay (seulement sur mobile)
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        
        toggleBtn?.addEventListener('click', () => {
            // Ne toggle que sur mobile (< 992px)
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        });
        
        // Fermer la sidebar en cliquant sur l'overlay (mobile uniquement)
        overlay?.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
        
        // Dropdown navigation
        document.querySelectorAll('.sif-nav-dropdown').forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                if (e.target.closest('.sif-nav-dropdown-item')) return;
                dropdown.classList.toggle('open');
            });
        });
        
        // Nettoyer les classes au resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
