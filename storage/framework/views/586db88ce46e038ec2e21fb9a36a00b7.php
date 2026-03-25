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
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <?php if(View::hasSection('breadcrumb')): ?>
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                <?php else: ?>
                    <li class="breadcrumb-item active"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></li>
                <?php endif; ?>
            </ol>
        </nav>
        
        <!-- Search Bar (Desktop only) -->
        <div class="search-bar ms-auto d-none d-lg-block">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Rechercher..." id="globalSearch">
        </div>
    </div>   
    
    <!-- Right Section: Actions & Profile -->
    <div class="d-flex align-items-center gap-3">
        <!-- Notifications -->
        <div class="dropdown" data-notification-dropdown>
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell" style="color: var(--sif-primary); font-size: 1.1rem;"></i>
                <?php
                    try {
                        $notificationCount = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
                    } catch (\Exception $e) {
                        $notificationCount = 0;
                    }
                ?>
                <?php if($notificationCount > 0): ?>
                    <span class="notification-badge" data-notification-badge><?php echo e($notificationCount > 9 ? '9+' : $notificationCount); ?></span>
                <?php else: ?>
                    <span class="notification-badge d-none" data-notification-badge></span>
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 320px; max-height: 400px; overflow-y: auto;">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold" style="color: var(--sif-primary);">🔔 Notifications</h6>
                    </div>
                </li>
                <li class="p-0">
                    <div class="notification-dropdown-content"></div>
                </li>
                <li class="border-top">
                    <a class="dropdown-item text-center py-2 fw-semibold" href="<?php echo e(route('admin.notifications.index')); ?>" style="color: var(--sif-primary);">
                        Voir toutes les notifications
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Quick Actions -->
        <div class="dropdown d-none d-md-block">
            <button class="notification-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-plus" style="color: var(--sif-primary); font-size: 1.1rem;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">⚡ Actions Rapides</h6></li>
                <?php if(auth()->user()->can('create', App\Models\Adherent::class)): ?>
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.adherents.create')); ?>"><i class="fas fa-user-plus me-2"></i>Nouvel adhérent</a></li>
                <?php endif; ?>
                <?php if(auth()->user()->can('viewAny', App\Models\Credit::class)): ?>
                    <li><a class="dropdown-item" href="<?php echo e(route('admin.credits.index')); ?>"><i class="fas fa-file-invoice-dollar me-2"></i>Voir les crédits</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo e(route('admin.logs.connexions')); ?>"><i class="fas fa-history me-2"></i>Logs de connexions</a></li>
            </ul>
        </div>
        
        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <div class="user-profile" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                </div>
                <div class="user-info d-none d-sm-block">
                    <div class="user-name"><?php echo e(auth()->user()->name); ?></div>
                    <div class="user-role">
                        <?php if(auth()->user()->isAdmin()): ?>
                            👑 Administrateur
                        <?php elseif(auth()->user()->isAgent()): ?>
                            👨‍💼 Agent
                        <?php elseif(auth()->user()->isChefService()): ?>
                            👨‍💼 Chef Service
                        <?php else: ?>
                            <?php echo e(auth()->user()->role ?? 'Utilisateur'); ?>

                        <?php endif; ?>
                    </div>
                </div>
                <i class="fas fa-chevron-down ms-2 d-none d-sm-block" style="font-size: 0.75rem; color: #94a3b8;"></i>
            </div>
            
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.875rem;">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size: 0.9rem;"><?php echo e(auth()->user()->name); ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;"><?php echo e(auth()->user()->email); ?></div>
                        </div>
                    </div>
                </li>
                
               <!--  <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i>Mon profil</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-question-circle me-2"></i>Aide</a></li>
                <li><hr class="dropdown-divider"></li> -->
                
                <li>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="m-0">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/layouts/header.blade.php ENDPATH**/ ?>