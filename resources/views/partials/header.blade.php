<!-- Navigation principale améliorée -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- Logo et nom de la marque -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="brand-logo me-2">
                <img src="{{ asset('img/SIF logo .jpg') }}" alt="SIFcash-Burkina Logo" class="logo-img" style="height: 50px; width: auto; object-fit: contain; border-radius: 8px;">
            </div>
            <div class="brand-text">
                <h4 class="mb-0 text-white fw-bold">SIFcash-Burkina</h4>
                <small class="text-muted">Épargne & Crédit</small>
            </div>
        </a>

        <!-- Bouton menu mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu principal -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs(['about', 'privacy', 'data-protection', 'terms']) ? 'active' : '' }}" 
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-info-circle me-1"></i>À propos
                    </a>
                    <ul class="dropdown-menu shadow-sm">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                <i class="fas fa-building me-2"></i>Notre entreprise
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('privacy') ? 'active' : '' }}" href="{{ route('privacy') }}">
                                <i class="fas fa-shield-alt me-2"></i>Confidentialité
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('data-protection') ? 'active' : '' }}" href="{{ route('data-protection') }}">
                                <i class="fas fa-lock me-2"></i>Protection des données
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('terms') ? 'active' : '' }}" href="{{ route('terms') }}">
                                <i class="fas fa-file-contract me-2"></i>Conditions d'utilisation
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">
                        <i class="fas fa-handshake me-1"></i>Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <i class="fas fa-phone me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <!-- Actions utilisateur -->
            <div class="navbar-nav ms-auto">
                @guest
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i>S'inscrire
                        </a>
                        <a href="http://127.0.0.1:8000/adherent/login" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>Se connecter
                        </a>
                    </div>
                @else
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span>{{ auth()->user()->nom ?? 'Utilisateur' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            @if(auth()->user()->hasRole('admin'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord Admin
                                    </a>
                                </li>
                            @elseif(auth()->user()->hasRole('adherent'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('adherent.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Mon tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('adherent.profile') }}">
                                        <i class="fas fa-user me-2"></i>Mon profil
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

@if(isset($showBreadcrumb) && $showBreadcrumb)
<!-- Fil d'Ariane -->
<nav aria-label="breadcrumb" class="bg-light border-bottom">
    <div class="container">
        <ol class="breadcrumb py-3 mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i>Accueil
                </a>
            </li>
            @if(isset($breadcrumbs))
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['title'] }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ol>
    </div>
</nav>
@endif

<style>
.navbar-brand .brand-logo .logo-img {
    transition: transform 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.navbar-brand:hover .brand-logo .logo-img {
    transform: scale(1.05);
}

.navbar-nav .nav-link {
    position: relative;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #007bff !important;
    transform: translateY(-1px);
}

.navbar-nav .nav-link.active {
    color: #007bff !important;
    font-weight: 500;
}

.navbar-nav .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 30px;
    height: 3px;
    background: #007bff;
    border-radius: 2px;
}

.dropdown-menu {
    border: none;
    animation: fadeInDown 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    color: #007bff;
}

.dropdown-item.active {
    background-color: #e3f2fd;
    color: #1565c0;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 991.98px) {
    .navbar-nav .nav-link.active::after {
        display: none;
    }
    
    .brand-text h4 {
        font-size: 1.1rem;
    }
    
    .navbar-brand .brand-logo .logo-img {
        height: 40px;
    }
}
</style>