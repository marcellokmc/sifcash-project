<div class="d-flex justify-content-between align-items-center py-3 px-4">
    <!-- Left Section: Toggle & Search -->
    <div class="d-flex align-items-center gap-3 flex-grow-1">
        <!-- Mobile Toggle -->
        <button class="sidebar-toggle" type="button">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                @if(View::hasSection('breadcrumb'))
                    @yield('breadcrumb')
                @else
                    <li class="breadcrumb-item active">@yield('page-title', 'Dashboard')</li>
                @endif
            </ol>
        </nav>
        
        <!-- Search Bar (Desktop only) -->
        <div class="search-bar ms-auto d-none d-lg-block">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="🔍 Rechercher..." id="globalSearch">
        </div>
    </div>
    
    <!-- Right Section: Actions & Profile -->
    <div class="d-flex align-items-center gap-3">
        <!-- Notifications -->
        <div class="dropdown">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell" style="color: var(--sif-primary); font-size: 1.1rem;"></i>
                @php
                    try {
                        $notificationCount = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
                        $hasNotifications = true;
                    } catch (\Exception $e) {
                        $notificationCount = 0;
                        $hasNotifications = false;
                    }
                @endphp
                @if($notificationCount > 0)
                    <span class="notification-badge">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold" style="color: var(--sif-primary);">🔔 Notifications</h6>
                        @if($notificationCount > 0)
                            <span class="badge bg-primary rounded-pill">{{ $notificationCount }}</span>
                        @endif
                    </div>
                </li>
                @if($hasNotifications && $notificationCount > 0)
                    @php
                        $notifications = \DB::table('notifications')
                            ->where('user_id', auth()->id())
                            ->where('lu', false)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @forelse($notifications as $notification)
                        <li>
                            <a class="dropdown-item py-3" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-info-circle text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 small fw-semibold">{{ $notification->titre ?? 'Notification' }}</p>
                                        <p class="mb-0 text-muted" style="font-size: 0.8rem;">{{ $notification->message ?? '' }}</p>
                                        <p class="mb-0 text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="px-3 py-4 text-center text-muted">
                            <i class="fas fa-bell-slash mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="mb-0 small">Aucune notification</p>
                        </li>
                    @endforelse
                    @if($notificationCount > 0 && Route::has('admin.notifications.index'))
                        <li class="border-top">
                            <a class="dropdown-item text-center py-2 fw-semibold" href="{{ route('admin.notifications.index') }}" style="color: var(--sif-primary);">
                                Voir toutes les notifications
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </li>
                    @endif
                @else
                    <li class="px-3 py-4 text-center text-muted">
                        <i class="fas fa-bell-slash mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                        <p class="mb-0 small">Aucune notification</p>
                        <p class="mb-0" style="font-size: 0.7rem; color: #cbd5e1;">Système de notifications en cours de configuration</p>
                    </li>
                @endif
            </ul>
        </div>
        
        <!-- Quick Actions -->
        <div class="dropdown d-none d-md-block">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus" style="color: var(--sif-primary); font-size: 1.1rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">⚡ Actions Rapides</h6></li>
                @if(auth()->user()->can('create', App\Models\Adherent::class))
                    <li><a class="dropdown-item" href="{{ route('admin.adherents.create') }}"><i class="fas fa-user-plus me-2"></i>Nouvel adhérent</a></li>
                @endif
                @if(auth()->user()->can('viewAny', App\Models\Credit::class))
                    <li><a class="dropdown-item" href="{{ route('admin.credits.index') }}"><i class="fas fa-file-invoice-dollar me-2"></i>Voir les crédits</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('admin.logs.connexions') }}"><i class="fas fa-history me-2"></i>Logs de connexions</a></li>
            </ul>
        </div>
        
        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info d-none d-sm-block">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @if(auth()->user()->isAdmin())
                            👑 Administrateur
                        @elseif(auth()->user()->isAgent())
                            👨‍💼 Agent
                        @elseif(auth()->user()->isChefService())
                            👨‍💼 Chef Service
                        @else
                            {{ auth()->user()->role ?? 'Utilisateur' }}
                        @endif
                    </div>
                </div>
                <i class="fas fa-chevron-down ms-2 d-none d-sm-block" style="font-size: 0.75rem; color: #94a3b8;"></i>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                </li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Mon profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-question-circle me-2"></i>Aide</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
