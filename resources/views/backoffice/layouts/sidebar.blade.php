<div class="position-sticky pt-3">
    <!-- En-tête de la barre latérale -->
    <div class="text-center mb-4">
        <h4 class="text-white mb-0">
            <i class="fas fa-hand-holding-usr me-2"></i>SIFCash-Burkina
        </h4>
        <small class="text-white-50">Gestion des opérations</small>
    </div>

    <hr class="border-light opacity-25 my-3">

    <ul class="nav flex-column">
        <!-- Tableau de bord -->
        <li class="nav-item mb-2">
            @php
                $me = Auth::user();
                $dashboardRoute = ($me && $me->isAdmin()) ? 'admin.dashboard'
                    : (($me && $me->isChefService()) ? 'chef-service.dashboard'
                    : (($me && $me->isAgent()) ? 'agent.dashboard' : 'admin.dashboard'));
            @endphp
            <a class="nav-link d-flex align-items-center {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}" 
               href="{{ route($dashboardRoute) }}">
                <i class="fas fa-tachometer-alt me-3"></i>
                <span>Tableau de bord</span>
            </a>
        </li>

        <hr class="border-light opacity-25 my-2">

        <!-- Section Adhérents -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ (request()->routeIs('admin.adherents.*') || request()->routeIs('admin.validation.*')) ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#adherentsMenu" role="button" aria-controls="adherentsMenu" aria-expanded="{{ (request()->routeIs('admin.adherents.*') || request()->routeIs('admin.validation.*')) ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users me-3"></i>
                    <span>Adhérents</span>
                </div>
                @php
                    $user = Auth::user();
                    $nbAdherentsEnAttente = 0;
                    $nbAdherentsSansAgence = 0;
                    if ($user) {
                        if ($user->isAdmin()) {
                            $nbAdherentsEnAttente = \App\Models\Adherent::where('statut_compte','en_attente_de_verification')->count();
                            $nbAdherentsSansAgence = \App\Models\Adherent::whereNull('agence_id')->count();
                        } elseif ($user->isChefService()) {
                            $nbAdherentsEnAttente = \App\Models\Adherent::where('agence_id', $user->agence_id)->where('statut_compte','en_attente_de_verification')->count();
                            $nbAdherentsSansAgence = \App\Models\Adherent::where('agence_id', $user->agence_id)->whereNull('agent_gestionnaire_id')->count();
                        } elseif ($user->isAgent()) {
                            $nbAdherentsEnAttente = \App\Models\Adherent::where('statut_compte','en_attente_de_verification')
                                ->whereHas('agents', fn($q)=>$q->where('agent_id', $user->id))->count();
                            $nbAdherentsSansAgence = 0;
                        }
                    }
                @endphp
                @if(($nbAdherentsEnAttente ?? 0) > 0)
                    <span class="badge bg-warning rounded-pill me-2">{{ $nbAdherentsEnAttente }}</span>
                @endif
                <i class="fas fa-chevron-{{ (request()->routeIs('admin.adherents.*') || request()->routeIs('admin.validation.*')) ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ (request()->routeIs('admin.adherents.*') || request()->routeIs('admin.validation.*')) ? 'show' : '' }}" id="adherentsMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.adherents.index') && !request()->has('affectation') ? 'active' : '' }}" 
                           href="{{ route('admin.adherents.index') }}">
                            <i class="fas fa-list-ul me-2"></i>
                            <span>Tous les adhérents</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('status') === 'actif' ? 'active' : '' }}" 
                           href="{{ route('admin.adherents.index', ['status' => 'actif']) }}">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Actifs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('status') === 'en_attente' ? 'active' : '' }}" 
                           href="{{ route('admin.adherents.index', ['status' => 'en_attente']) }}">
                            <i class="fas fa-clock me-2"></i>
                            <span>En attente</span>
                            @if(($nbAdherentsEnAttente ?? 0) > 0)
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbAdherentsEnAttente }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item mt-2 pt-2 border-top">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.validation.documents') ? 'active' : '' }}" 
                           href="{{ route('admin.validation.documents') }}">
                            <i class="fas fa-file-alt me-2"></i>
                            <span>Documents en attente</span>
                            @php
                                $u = Auth::user();
                                if ($u && $u->isAdmin()) {
                                    $nbDocsPending = \App\Models\Document::where('statut','soumis')->count();
                                } elseif ($u && $u->isChefService()) {
                                    $nbDocsPending = \App\Models\Document::where('statut','soumis')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                                } elseif ($u && $u->isAgent()) {
                                    $nbDocsPending = \App\Models\Document::where('statut','soumis')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                                } else { $nbDocsPending = 0; }
                            @endphp
                            <span class="badge bg-danger rounded-pill ms-auto {{ ($nbDocsPending > 0) ? '' : 'd-none' }}">
                                {{ $nbDocsPending }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.validation.ayants-droit') ? 'active' : '' }}" 
                           href="{{ route('admin.validation.ayants-droit') }}">
                            <i class="fas fa-users me-2"></i>
                            <span>Ayants droit en attente</span>
                            @php
                                $u = Auth::user();
                                if ($u && $u->isAdmin()) {
                                    $nbAyantsPending = \App\Models\AyantDroit::where('statut_validation','en_attente')->count();
                                } elseif ($u && $u->isChefService()) {
                                    $nbAyantsPending = \App\Models\AyantDroit::where('statut_validation','en_attente')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                                } elseif ($u && $u->isAgent()) {
                                    $nbAyantsPending = \App\Models\AyantDroit::where('statut_validation','en_attente')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                                } else { $nbAyantsPending = 0; }
                            @endphp
                            <span class="badge bg-danger rounded-pill ms-auto {{ ($nbAyantsPending > 0) ? '' : 'd-none' }}">
                                {{ $nbAyantsPending }}
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Section Affectations (Admin et Chef de service uniquement) -->
        @if(Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isChefService()))
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.affectations.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#affectationsMenu" role="button" aria-controls="affectationsMenu" aria-expanded="{{ request()->routeIs('admin.affectations.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-tie me-3"></i>
                    <span>Affectations</span>
                </div>
                @php
                    $nbNonAffectes = \App\Models\Adherent::nonAffectes()->count();
                @endphp
                @if($nbNonAffectes > 0)
                    <span class="badge bg-danger rounded-pill me-2">{{ $nbNonAffectes }}</span>
                @endif
                <i class="fas fa-chevron-{{ request()->routeIs('admin.affectations.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.affectations.*') ? 'show' : '' }}" id="affectationsMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.affectations.index') ? 'active' : '' }}" 
                           href="{{ route('admin.affectations.index') }}">
                            <i class="fas fa-tasks me-2"></i>
                            <span>Affectation en masse</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.affectations.non-affectes') ? 'active' : '' }}" 
                           href="{{ route('admin.affectations.non-affectes') }}">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span>Non affectés</span>
                            @if($nbNonAffectes > 0)
                                <span class="badge bg-danger rounded-pill ms-auto">{{ $nbNonAffectes }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item mt-2 pt-2 border-top">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.affectations.par-agence') ? 'active' : '' }}" 
                           href="{{ route('admin.affectations.par-agence') }}">
                            <i class="fas fa-building me-2"></i>
                            <span>Par agence</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.affectations.par-agent') ? 'active' : '' }}" 
                           href="{{ route('admin.affectations.par-agent') }}">
                            <i class="fas fa-user-check me-2"></i>
                            <span>Par agent</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        <hr class="border-light opacity-25 my-2">

        <!-- Section Utilisateurs (Chef de service) -->
        @if(Auth::user() && Auth::user()->isChefService())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
               href="{{ route('admin.users.index') }}">
                <i class="fas fa-user-friends me-3"></i>
                <span>Utilisateurs de mon agence</span>
            </a>
        </li>
        @endif

        <!-- Section Épargne -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.epargnes.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#epargneMenu" role="button" aria-controls="epargneMenu" aria-expanded="{{ request()->routeIs('admin.epargnes.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-piggy-bank me-3"></i>
                    <span>Épargne</span>
                </div>
                @php
                    $u = Auth::user();
                    if ($u && $u->isAdmin()) {
                        $nbEpargnesPending = \App\Models\Epargne::where('statut','en_attente')->count();
                    } elseif ($u && $u->isChefService()) {
                        $nbEpargnesPending = \App\Models\Epargne::where('statut','en_attente')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                    } elseif ($u && $u->isAgent()) {
                        $nbEpargnesPending = \App\Models\Epargne::where('statut','en_attente')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                    } else { $nbEpargnesPending = 0; }
                @endphp
                <span class="badge bg-warning rounded-pill me-2 {{ ($nbEpargnesPending > 0) ? '' : 'd-none' }}">
                    {{ $nbEpargnesPending }}
                </span>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.epargnes.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.epargnes.*') ? 'show' : '' }}" id="epargneMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.epargnes.index') && !request()->has('statut') ? 'active' : '' }}" 
                           href="{{ route('admin.epargnes.index') }}">
                            <i class="fas fa-list-ul me-2"></i>
                            <span>Tous les comptes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'actif' ? 'active' : '' }}" 
                           href="{{ route('admin.epargnes.index', ['statut' => 'actif']) }}">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Actifs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'en_attente' ? 'active' : '' }}" 
                           href="{{ route('admin.epargnes.index', ['statut' => 'en_attente']) }}">
                            <i class="fas fa-clock me-2"></i>
                            <span>En attente</span>
                            @php
                                $nbEpargnesEnAttente = \App\Models\Epargne::where('statut', 'en_attente')->count();
                            @endphp
                            @if($nbEpargnesEnAttente > 0)
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbEpargnesEnAttente }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'bloque' ? 'active' : '' }}" 
                           href="{{ route('admin.epargnes.index', ['statut' => 'bloque']) }}">
                            <i class="fas fa-lock me-2"></i>
                            <span>Bloqués</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'cloture' ? 'active' : '' }}" 
                           href="{{ route('admin.epargnes.index', ['statut' => 'cloture']) }}">
                            <i class="fas fa-archive me-2"></i>
                            <span>Clôturés</span>
                        </a>
                    </li>
                    <li class="nav-item mt-2 pt-2 border-top">
                        <a class="nav-link d-flex align-items-center text-success" 
                           href="{{ route('admin.epargnes.create') }}">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span>Nouveau compte</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Section Crédits -->
        @if(auth()->user()->isAdmin() || auth()->user()->isAgent() || auth()->user()->isChefService())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.credits.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#creditsMenu" role="button" aria-controls="creditsMenu" aria-expanded="{{ request()->routeIs('admin.credits.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar me-3"></i>
                    <span>Crédits</span>
                </div>
                @php
                    $u = Auth::user();
                    if ($u && $u->isAdmin()) {
                        $nbCreditsPending = \App\Models\Credit::where('statut','en_attente')->count();
                    } elseif ($u && $u->isChefService()) {
                        $nbCreditsPending = \App\Models\Credit::where('statut','en_attente')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                    } elseif ($u && $u->isAgent()) {
                        $nbCreditsPending = \App\Models\Credit::where('statut','en_attente')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                    } else { $nbCreditsPending = 0; }
                @endphp
                <span class="badge bg-warning rounded-pill me-2 {{ ($nbCreditsPending > 0) ? '' : 'd-none' }}">
                    {{ $nbCreditsPending }}
                </span>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.credits.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.credits.*') ? 'show' : '' }}" id="creditsMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.credits.index') && !request()->has('statut') ? 'active' : '' }}" 
                           href="{{ route('admin.credits.index') }}">
                            <i class="fas fa-list-ul me-2"></i>
                            <span>Tous les crédits</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'en_attente' ? 'active' : '' }}" 
                           href="{{ route('admin.credits.index', ['statut' => 'en_attente']) }}">
                            <i class="fas fa-clock me-2"></i>
                            <span>En attente</span>
                            @if($nbCreditsEnAttente = \App\Models\Credit::where('statut', 'en_attente')->count())
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbCreditsEnAttente }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'approuve' ? 'active' : '' }}" 
                           href="{{ route('admin.credits.index', ['statut' => 'approuve']) }}">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Approuvés</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'rejete' ? 'active' : '' }}" 
                           href="{{ route('admin.credits.index', ['statut' => 'rejete']) }}">
                            <i class="fas fa-times-circle me-2"></i>
                            <span>Rejetés</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->get('statut') === 'rembourse' ? 'active' : '' }}" 
                           href="{{ route('admin.credits.index', ['statut' => 'rembourse']) }}">
                            <i class="fas fa-check-double me-2"></i>
                            <span>Remboursés</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        <hr class="border-light opacity-25 my-2">

        <!-- Section Plans et Adhésions -->
        <li class="nav-item">
            @php
                $nbAdhesionsEnAttenteSidebar = \App\Models\Adhesion::where('statut', 'en_attente_activation')->count();
            @endphp
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.plans.*', 'admin.adhesions.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#plansMenu" role="button" aria-controls="plansMenu" aria-expanded="{{ request()->routeIs('admin.plans.*', 'admin.adhesions.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-handshake me-3"></i>
                    <span>Adhésions</span>
                </div>
                @php
                    $u = Auth::user();
                    if ($u && $u->isAdmin()) {
                        $nbAdhesionsPending = \App\Models\Adhesion::where('statut','en_attente_activation')->count();
                    } elseif ($u && $u->isChefService()) {
                        $nbAdhesionsPending = \App\Models\Adhesion::where('statut','en_attente_activation')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                    } elseif ($u && $u->isAgent()) {
                        $nbAdhesionsPending = \App\Models\Adhesion::where('statut','en_attente_activation')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                    } else { $nbAdhesionsPending = 0; }
                @endphp
                <span class="badge bg-warning rounded-pill me-2 {{ ($nbAdhesionsPending > 0) ? '' : 'd-none' }}">
                    {{ $nbAdhesionsPending }}
                </span>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.plans.*', 'admin.adhesions.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.plans.*', 'admin.adhesions.*') ? 'show' : '' }}" id="plansMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.adhesions.*') ? 'active' : '' }}" 
                           href="{{ route('admin.adhesions.index') }}">
                            <i class="fas fa-handshake me-2"></i>
                            <span>Toutes les adhésions</span>
                            @php
                                $nbAdhesionsActives = \App\Models\Adhesion::where('statut', 'actif')->count();
                            @endphp
                            @if($nbAdhesionsActives > 0)
                                <span class="badge bg-success rounded-pill ms-auto">{{ $nbAdhesionsActives }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('admin.adhesions.index', ['statut' => 'en_attente_activation']) }}">
                            <i class="fas fa-clock me-2"></i>
                            <span>En attente</span>
                            @php
                                $u = Auth::user();
                                if ($u && $u->isAdmin()) {
                                    $nbAdhesionsEnAttente = \App\Models\Adhesion::where('statut', 'en_attente_activation')->count();
                                } elseif ($u && $u->isChefService()) {
                                    $nbAdhesionsEnAttente = \App\Models\Adhesion::where('statut', 'en_attente_activation')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                                } elseif ($u && $u->isAgent()) {
                                    $nbAdhesionsEnAttente = \App\Models\Adhesion::where('statut', 'en_attente_activation')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                                } else { $nbAdhesionsEnAttente = 0; }
                            @endphp
                            @if($nbAdhesionsEnAttente > 0)
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbAdhesionsEnAttente }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('admin.adhesions.index', ['statut' => 'suspendue']) }}">
                            <i class="fas fa-pause-circle me-2"></i>
                            <span>Suspendues</span>
                            @php
                                $nbAdhesionsSuspendues = \App\Models\Adhesion::where('statut', 'suspendue')->count();
                            @endphp
                            @if($nbAdhesionsSuspendues > 0)
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbAdhesionsSuspendues }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('admin.adhesions.index', ['statut' => 'terminee']) }}">
                            <i class="fas fa-check-double me-2"></i>
                            <span>Terminées</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <hr class="border-light opacity-25 my-2">

        <!-- Section Retraits -->
        @if(auth()->user()->isAdmin() || auth()->user()->isAgent())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.retraits.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#retraitsMenu" role="button" aria-controls="retraitsMenu" aria-expanded="{{ request()->routeIs('admin.retraits.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-money-bill-wave me-3"></i>
                    <span>Gestion des retraits</span>
                </div>
                @php
                    $u = Auth::user();
                    if ($u && $u->isAdmin()) {
                        $nbRetraitsPending = \App\Models\DemandeRetrait::where('statut','en_attente')->count();
                    } elseif ($u && $u->isChefService()) {
                        $nbRetraitsPending = \App\Models\DemandeRetrait::where('statut','en_attente')->whereHas('adherent', fn($q)=>$q->where('agence_id', $u->agence_id))->count();
                    } elseif ($u && $u->isAgent()) {
                        $nbRetraitsPending = \App\Models\DemandeRetrait::where('statut','en_attente')->whereHas('adherent.agents', fn($q)=>$q->where('agent_id', $u->id))->count();
                    } else { $nbRetraitsPending = 0; }
                @endphp
                <span class="badge bg-warning rounded-pill me-2 {{ ($nbRetraitsPending > 0) ? '' : 'd-none' }}">
                    {{ $nbRetraitsPending }}
                </span>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.retraits.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.retraits.*') ? 'show' : '' }}" id="retraitsMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.retraits.index') ? 'active' : '' }}" 
                           href="{{ route('admin.retraits.index') }}">
                            <i class="fas fa-list-ul me-2"></i>
                            <span>Toutes les demandes</span>
                            @php
                                $nbRetraitsEnAttente = \App\Models\DemandeRetrait::where('statut', 'en_attente')->count();
                            @endphp
                            @if($nbRetraitsEnAttente > 0)
                                <span class="badge bg-warning rounded-pill ms-auto">{{ $nbRetraitsEnAttente }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('admin.retraits.index', ['statut' => 'valide']) }}">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Validés</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" 
                           href="{{ route('admin.retraits.index', ['statut' => 'rejete']) }}">
                            <i class="fas fa-times-circle me-2"></i>
                            <span>Rejetés</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif


        <!-- Activité globale (Admin et Chef de service uniquement) -->
        @if(auth()->user()->isAdmin() || auth()->user()->isChefService())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.audits.*') ? 'active' : '' }}" 
               href="{{ route('admin.audits.index') }}">
                <i class="fas fa-stream me-3"></i>
                <span>Activité</span>
            </a>
        </li>
        @endif

        <!-- Notifications -->
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}" 
               href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-bell me-3"></i>
                <span>Notifications</span>
                @php
                    $unreadCount = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->where('lu', 0)->count() : 0;
                @endphp
                <span class="ms-auto {{ $unreadCount > 0 ? '' : 'd-none' }}" data-notification-badge>
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            </a>
        </li>

        <!-- Section Paramètres -->
        @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.parametres.*') || request()->routeIs('admin.plans.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#parametresMenu" role="button" aria-controls="parametresMenu" aria-expanded="{{ request()->routeIs('admin.parametres.*') || request()->routeIs('admin.plans.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-cog me-3"></i>
                    <span>Paramètres</span>
                </div>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.parametres.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.parametres.*') || request()->routeIs('admin.plans.*') ? 'show' : '' }}" id="parametresMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.plans.*') ? 'active' : '' }}" 
                           href="{{ route('admin.plans.index') }}">
                            <i class="fas fa-list-alt me-2"></i>
                            <span>Gestion des plans</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center text-success" 
                           href="{{ route('admin.plans.create') }}">
                            <i class="fas fa-plus-circle me-2"></i>
                            <span>Créer un plan</span>
                        </a>
                    </li>
                    
                    <li class="nav-item mt-2 pt-2 border-top">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.agences.*') ? 'active' : '' }}" 
                           href="{{ route('admin.agences.index') }}">
                            <i class="fas fa-building me-2"></i>
                            <span>Agences</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.types-documents.*') ? 'active' : '' }}" 
                           href="{{ route('admin.types-documents.index') }}">
                            <i class="fas fa-file-alt me-2"></i>
                            <span>Configuration documents</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>
        @endif

        <!-- Section Administration -->
        @if(auth()->user()->isAdmin())
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('admin.administration.*') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#" data-bs-target="#adminMenu" role="button" aria-controls="adminMenu" aria-expanded="{{ request()->routeIs('admin.administration.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt me-3"></i>
                    <span>Administration</span>
                </div>
                <i class="fas fa-chevron-{{ request()->routeIs('admin.administration.*') ? 'up' : 'down' }} small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.administration.*') ? 'show' : '' }}" id="adminMenu">
                <ul class="nav flex-column ms-4 py-2">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                           href="{{ route('admin.users.index') }}">
                            <i class="fas fa-user-shield me-2"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" 
                           href="{{ route('admin.roles.index') }}">
                            <i class="fas fa-user-tag me-2"></i>
                            <span>Rôles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" 
                           href="{{ route('admin.permissions.index') }}">
                            <i class="fas fa-key me-2"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" 
                           href="{{ route('admin.logs.connexions') }}">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span>Journaux de connexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        <!-- Section Rapports -->
    </ul>

    <!-- Conteneur principal avec flexbox -->
    <div class="d-flex flex-column h-100">
        <!-- Contenu principal -->
        <div class="flex-grow-1">
            <!-- Le contenu existant reste ici -->
        </div>

        <!-- Version et copyright -->
        <div class="mt-auto p-3 text-center text-white-50 small border-top border-secondary">
            <div class="mb-1">Version 1.0.0</div>
            <div>© {{ date('Y') }} SIFCash-Burkina</div>
        </div>
    </div>
</div>

<style>
/* Styles existants */
.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow-y: auto;
}

.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.8);
    border-radius: 0.25rem;
    padding: 0.5rem 1rem;
    margin-bottom: 0.25rem;
    transition: all 0.2s;
}

.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
}

.sidebar .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    font-weight: 500;
}

.sidebar .nav-link i {
    width: 20px;
    text-align: center;
}

.sidebar .nav-link .badge {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
}

.sidebar .nav-link[data-bs-toggle="collapse"]::after {
    display: none;
}

.sidebar .nav-link .fa-chevron-down,
.sidebar .nav-link .fa-chevron-up {
    transition: transform 0.2s;
}

.sidebar .nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

.sidebar .nav-link[aria-expanded="true"] .fa-chevron-up {
    transform: rotate(0deg);
}
/* Fix conflit Tailwind (.collapse) vs Bootstrap */
.sidebar .collapse,
.sidebar .collapsing,
.sidebar .collapse.show {
    visibility: visible !important; /* annule visibility: collapse de Tailwind */
}
/* S'assurer que le contenu reste cliquable et visible quand ouvert */
.sidebar .collapse.show {
    display: block; /* au cas où une autre règle forcerait display */
    overflow: visible;
}
</style>