<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('adherent.dashboard') }}">
            <i class="fas fa-hand-holding-usd me-2"></i>
            <span>Mon Espace Adhérent</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('adherent.dashboard') ? 'active' : '' }}" 
                       href="{{ route('adherent.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Tableau de bord
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('adherent.epargne.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-piggy-bank me-1"></i> Mon Épargne
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.epargne.solde') ? 'active' : '' }}" 
                               href="{{ route('adherent.epargne.solde') }}">
                                <i class="fas fa-wallet me-2"></i> Mon solde
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.epargne.depot') ? 'active' : '' }}" 
                               href="{{ route('adherent.epargne.depot') }}">
                                <i class="fas fa-plus-circle me-2"></i> Faire un dépôt
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.epargne.retrait') ? 'active' : '' }}" 
                               href="{{ route('adherent.epargne.retrait') }}">
                                <i class="fas fa-minus-circle me-2"></i> Demander un retrait
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.epargne.historique') ? 'active' : '' }}" 
                               href="{{ route('adherent.epargne.historique') }}">
                                <i class="fas fa-history me-2"></i> Historique
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('adherent.credits.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Mes Crédits
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.credits.index') ? 'active' : '' }}" 
                               href="{{ route('adherent.credits.index') }}">
                                <i class="fas fa-list-ul me-2"></i> Mes demandes
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.credits.nouveau') ? 'active' : '' }}" 
                               href="{{ route('adherent.credits.nouveau') }}">
                                <i class="fas fa-plus-circle me-2"></i> Nouvelle demande
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.credits.remboursements') ? 'active' : '' }}" 
                               href="{{ route('adherent.credits.remboursements') }}">
                                <i class="fas fa-credit-card me-2"></i> Remboursements
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.credits.echeances') ? 'active' : '' }}" 
                               href="{{ route('adherent.credits.echeances') }}">
                                <i class="fas fa-calendar-alt me-2"></i> Échéances
                                @php($echeancesEnRetard = auth()->user()->adherent->echeancesEnRetard()->count())
                                @if($echeancesEnRetard > 0)
                                    <span class="badge bg-danger rounded-pill ms-2">{{ $echeancesEnRetard }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('adherent.documents.*') ? 'active' : '' }}" 
                       href="{{ route('adherent.documents.index') }}">
                        <i class="fas fa-file-alt me-1"></i> Mes Documents
                        @php($documentsEnAttente = auth()->user()->adherent->documents()->where('statut', 'en_attente')->count())
                        @if($documentsEnAttente > 0)
                            <span class="badge bg-warning rounded-pill ms-1">{{ $documentsEnAttente }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('adherent.messages.*') ? 'active' : '' }}" 
                       href="{{ route('adherent.messages.index') }}">
                        <i class="fas fa-envelope me-1"></i> Messagerie
                        @php($messagesNonLus = auth()->user()->messagesRecus()->whereNull('lu_le')->count())
                        @if($messagesNonLus > 0)
                            <span class="badge bg-primary rounded-pill ms-1">{{ $messagesNonLus }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <div class="me-2 d-none d-md-inline">
                            <div class="text-end">
                                <div class="fw-semibold">{{ auth()->user()->adherent->nom_complet ?? auth()->user()->name }}</div>
                                <small class="text-white-50">Adhérent</small>
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.profile') ? 'active' : '' }}" 
                               href="{{ route('adherent.profile') }}">
                                <i class="fas fa-user-edit me-2"></i> Mon profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('adherent.parametres') ? 'active' : '' }}" 
                               href="{{ route('adherent.parametres') }}">
                                <i class="fas fa-cog me-2"></i> Paramètres
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

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
    padding: 0.5rem 0;
    margin-top: 0.5rem;
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

/* Style spécifique pour les appareils mobiles */
@media (max-width: 991.98px) {
    .navbar-collapse {
        padding: 1rem 0;
    }
    
    .nav-item {
        margin: 0.25rem 0;
    }
    
    .dropdown-menu {
        margin-left: 1rem;
        box-shadow: none;
    }
    
    .navbar-nav .dropdown-menu {
        position: static !important;
        float: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gestion du menu mobile
    const mobileMenuButton = document.querySelector('.navbar-toggler');
    const mobileMenu = document.querySelector('.navbar-collapse');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
        
        // Fermer le menu quand on clique à l'extérieur
        document.addEventListener('click', function(event) {
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.remove('show');
            }
        });
    }
    
    // Gestion des sous-menus sur mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth < 992) { // Seulement sur mobile
                e.preventDefault();
                const parent = this.closest('.dropdown');
                const menu = this.nextElementSibling;
                
                // Fermer les autres menus ouverts
                document.querySelectorAll('.dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('show');
                });
                
                // Basculer le menu actuel
                menu.classList.toggle('show');
            }
        });
    });
});
</script>
@endpush
