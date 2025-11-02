<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-hand-holding-usd me-2"></i>
            <span>SIFCash-Burkina</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                    </a>
                </li>
                
                @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.adherents.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i> Adhérents
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.adherents.index') ? 'active' : '' }}" 
                               href="{{ route('admin.adherents.index') }}">
                                Tous les adhérents
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.adherents.create') ? 'active' : '' }}" 
                               href="{{ route('admin.adherents.create') }}">
                                Nouvel adhérent
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.adherents.attente') ? 'active' : '' }}" 
                               href="{{ route('admin.adherents.index', ['statut' => 'en_attente']) }}">
                                En attente de validation
                                @php($nbAdherentsEnAttente = \App\Models\Adherent::where('statut_compte', 'en_attente_de_verification')->count())
                                @if($nbAdherentsEnAttente > 0)
                                    <span class="badge bg-warning rounded-pill ms-2">{{ $nbAdherentsEnAttente }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isChefService())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.epargnes.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-piggy-bank me-1"></i> Épargne
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.epargnes.index') ? 'active' : '' }}" 
                               href="{{ route('admin.epargnes.index') }}">
                                Tous les comptes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.epargnes.create') ? 'active' : '' }}" 
                               href="{{ route('admin.epargnes.create') }}">
                                Nouveau compte
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.epargnes.index', ['statut' => 'en_attente']) }}">
                                Comptes en attente
                                @php($nbEpargnesEnAttente = \App\Models\Epargne::where('statut', 'en_attente')->count())
                                @if($nbEpargnesEnAttente > 0)
                                    <span class="badge bg-warning rounded-pill ms-2">{{ $nbEpargnesEnAttente }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.credits.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Crédits
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.credits.index') ? 'active' : '' }}" 
                               href="{{ route('admin.credits.index') }}">
                                Tous les crédits
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.credits.index', ['statut' => 'en_attente']) }}">
                                En attente
                                @php($nbCreditsEnAttente = \App\Models\Credit::where('statut', 'en_attente')->count())
                                @if($nbCreditsEnAttente > 0)
                                    <span class="badge bg-warning rounded-pill ms-2">{{ $nbCreditsEnAttente }}</span>
                                @endif
                            </a>
                        </li>
                        
                    </ul>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.agences.*', 'admin.users.*', 'admin.roles.*', 'admin.permissions.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1"></i> Administration
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.agences.*') ? 'active' : '' }}" 
                               href="{{ route('admin.agences.index') }}">
                                <i class="fas fa-building me-1"></i> Agences
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                               href="{{ route('admin.users.index') }}">
                                <i class="fas fa-user-shield me-1"></i> Utilisateurs
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
                               href="{{ route('admin.roles.index') }}">
                                <i class="fas fa-user-tag me-1"></i> Rôles
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" 
                               href="{{ route('admin.permissions.index') }}">
                                <i class="fas fa-key me-1"></i> Permissions
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <div class="me-2 d-none d-md-inline">
                            <div class="text-end">
                                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                <small class="text-white-50">{{ ucfirst(Auth::user()->role) }}</small>
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <i class="fas fa-user-edit me-2"></i> Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

@push('styles')
<style>
.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
    padding: 0.5rem 0;
}

.navbar-brand {
    font-weight: 600;
    font-size: 1.25rem;
}

.nav-link {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    transition: all 0.2s;
}

.nav-link:hover, .nav-link:focus {
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

/* Dropdowns: assurer texte lisible */
.navbar-dark .dropdown-menu .dropdown-item {
    color: #212529;
}
.navbar-dark .dropdown-menu .dropdown-item:hover,
.navbar-dark .dropdown-menu .dropdown-item:focus {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
    z-index: 1050;
}

.dropdown-item {
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
    border-radius: 0.25rem;
    margin: 0 0.5rem;
    width: auto;
}

.dropdown-item.active, .dropdown-item:active {
    background-color: #f8f9fa;
    color: #0d6efd;
}

.dropdown-divider {
    margin: 0.5rem 0;
}

.badge {
    font-size: 0.65em;
    vertical-align: middle;
}

/* Mobile: garder les sous-menus ouverts et cliquables */
@media (max-width: 991.98px) {
    .navbar-collapse {
        padding: 1rem 0;
    }
    
    .nav-item {
        margin: 0.25rem 0;
    }
    
    .navbar-nav .dropdown-menu {
        position: static !important;
        float: none;
        display: none;
        margin-left: 1rem;
        box-shadow: none;
    }
    .navbar-nav .dropdown-menu.show {
        display: block;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Améliorer le comportement des dropdowns sur mobile (empêcher la fermeture immédiate)
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.navbar .dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                // Fermer les autres menus
                document.querySelectorAll('.navbar .dropdown-menu.show').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
            }
        });
    });
});
</script>
@endpush
