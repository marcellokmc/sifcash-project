<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Espace Adhérent'); ?> - SIFCash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link {
            color: #e2e8f0;
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(4px);
        }
        
        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid #334155;
            margin-bottom: 1rem;
        }
        
        .content-wrapper {
            background: #ffffff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin: 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-menu {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                z-index: 1050;
                width: 280px;
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
    </style>
</head>
<body>
    <?php
        // Comptage des notifications non lues
        try {
            $nbNotificationsNonLues = \DB::table('notifications')->where('user_id', auth()->id())->where('lu', false)->count();
        } catch (\Exception $e) {
            $nbNotificationsNonLues = 0;
        }
    ?>
    
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar d-flex flex-column">
            <!-- Brand -->
            <div class="sidebar-brand text-center text-white">
                <h4 class="mb-1">
                    <i class="fas fa-piggy-bank me-2"></i>SIFCash-Burkina
                </h4>
                <small class="text-white-50">Espace Adhérent</small>
            </div>

            <!-- Navigation -->
            <ul class="nav flex-column flex-grow-1">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.dashboard') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Tableau de bord
                    </a>
                </li>

                <!-- Profil -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.profile.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.profile')); ?>">
                        <i class="fas fa-user-circle"></i>
                        Mon Profil
                    </a>
                </li>
                
                <!-- Changer mot de passe -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.password.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.password.edit')); ?>">
                        <i class="fas fa-key"></i>
                        Changer mot de passe
                    </a>
                </li>

                <hr class="border-secondary my-2 mx-3">

                <!-- Plans disponibles -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.plans.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.plans.index')); ?>">
                        <i class="fas fa-clipboard-list"></i>
                        Plans disponibles
                    </a>
                </li>

                <!-- Mes adhésions -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.adhesions.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.adhesions.index')); ?>">
                        <i class="fas fa-handshake"></i>
                        Mes adhésions
                        <?php ($nbAdhesionsActives = Auth::user()->adherent ? Auth::user()->adherent->adhesions()->where('statut', 'active')->count() : 0); ?>
                        <?php if($nbAdhesionsActives > 0): ?>
                            <span class="badge bg-success ms-auto"><?php echo e($nbAdhesionsActives); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <hr class="border-secondary my-2 mx-3">

                <!-- Épargne -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.paiements.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.paiements.index')); ?>">
                        <i class="fas fa-piggy-bank"></i>
                        Mon Épargne
                    </a>
                </li>

                <!-- Crédits -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.credits.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.credits.index')); ?>">
                        <i class="fas fa-credit-card"></i>
                        Mes Crédits
                        <?php ($nbCreditsEnAttente = Auth::user()->adherent ? Auth::user()->adherent->credits()->where('statut', 'en_attente')->count() : 0); ?>
                        <?php if($nbCreditsEnAttente > 0): ?>
                            <span class="badge bg-warning ms-auto"><?php echo e($nbCreditsEnAttente); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <!-- Retraits -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.retraits.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.retraits.index')); ?>">
                        <i class="fas fa-money-bill-wave"></i>
                        Mes Retraits
                    </a>
                </li>

                <hr class="border-secondary my-2 mx-3">

                <!-- Documents -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.documents.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.documents.index')); ?>">
                        <i class="fas fa-folder-open"></i>
                        Mes Documents
                    </a>
                </li>

                <!-- Ayants droit -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.ayants-droit.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.ayants-droit.index')); ?>">
                        <i class="fas fa-users"></i>
                        Ayants droit
                    </a>
                </li>

                <!-- Notifications -->
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('adherent.notifications.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('adherent.notifications.index')); ?>">
                        <i class="fas fa-bell"></i>
                        Notifications
                        <?php if($nbNotificationsNonLues > 0): ?>
                            <span class="badge bg-danger ms-auto"><?php echo e($nbNotificationsNonLues); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>

            <!-- User info & logout -->
            <div class="mt-auto p-3">
                <div class="d-flex align-items-center text-white mb-3">
                    <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?php echo e(Auth::user()->name); ?></div>
                        <small class="text-white-50"><?php echo e(Auth::user()->email); ?></small>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </nav>

        <!-- Mobile overlay -->
        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        <!-- Main content -->
        <div class="flex-grow-1">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <!-- Mobile toggle -->
                    <button class="navbar-toggler border-0 d-lg-none" type="button" id="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Page title -->
                    <div class="navbar-nav me-auto">
                        <h5 class="mb-0 fw-semibold text-dark"><?php echo $__env->yieldContent('page-title', 'Tableau de bord'); ?></h5>
                    </div>

                    <!-- Right navbar -->
                    <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
                        <!-- Quick actions -->
                        <div class="nav-item dropdown me-3">
                            <a class="nav-link btn btn-outline-primary btn-sm" href="<?php echo e(route('adherent.adhesions.create')); ?>">
                                <i class="fas fa-plus me-1"></i>Nouvelle adhésion
                            </a>
                        </div>

                        <!-- Notifications -->
                        <div class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="<?php echo e(route('adherent.notifications.index')); ?>">
                                <i class="fas fa-bell fs-5"></i>
                                <?php if($nbNotificationsNonLues > 0): ?>
                                    <span class="notification-badge"><?php echo e($nbNotificationsNonLues); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>

                        <!-- User menu -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                                </div>
                                <span class="fw-medium"><?php echo e(Auth::user()->name); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-menu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('adherent.profile')); ?>">
                                        <i class="fas fa-user me-2"></i>Mon Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>">
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
            </nav>

            <!-- Main content -->
            <main class="content-wrapper">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
            
            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('alert-dismissible')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/layouts/app.blade.php ENDPATH**/ ?>