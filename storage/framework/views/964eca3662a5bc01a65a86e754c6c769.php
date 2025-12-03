<?php $__env->startSection('title', 'Page non trouvée - 404'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .error-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .error-container::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="%23ffffff" fill-opacity="0.1"/></svg>') repeat;
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .error-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        z-index: 1;
    }
    
    .error-number {
        font-size: 8rem;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        line-height: 1;
    }
    
    .error-icon {
        font-size: 4rem;
        color: #667eea;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    .btn-modern {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-modern:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover:before {
        left: 100%;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item.active {
        color: white;
    }
    
    @media (max-width: 768px) {
        .error-number {
            font-size: 5rem;
        }
        
        .error-icon {
            font-size: 2.5rem;
        }
        
        .btn-modern {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="error-container d-flex align-items-center">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item">
                    <a href="<?php echo e(route('home')); ?>" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Erreur 404</li>
            </ol>
        </nav>
        
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="error-card p-4 p-md-5 text-center">
                    <!-- Numéro d'erreur -->
                    <div class="error-number mb-3">404</div>
                    
                    <!-- Icône -->
                    <div class="error-icon mb-4">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    
                    <!-- Message principal -->
                    <h2 class="h3 fw-bold text-dark mb-3">Page non trouvée</h2>
                    <p class="lead text-muted mb-4">
                        Oups ! La page que vous recherchez semble s'être perdue dans les méandres du web.
                    </p>
                    
                    <!-- Message secondaire -->
                    <p class="text-muted mb-4">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Vérifiez l'URL ou utilisez les boutons ci-dessous pour naviguer.
                    </p>
                    
                    <!-- URL demandée (si pas sensible) -->
                    <?php if(request()->path() != '/'): ?>
                    <div class="alert alert-light border-0 mb-4">
                        <small class="text-muted">
                            <i class="fas fa-link me-2"></i>URL demandée : 
                            <code class="text-danger"><?php echo e(request()->fullUrl()); ?></code>
                        </small>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <button onclick="history.back()" class="btn btn-secondary-modern btn-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </button>
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-home me-2"></i>Accueil
                        </a>
                    </div>
                    
                    <!-- Liens utiles -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="text-muted mb-3">Liens utiles</h6>
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-home d-block mb-1"></i>
                                    <small>Accueil</small>
                                </a>
                            </div>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->role === 'admin'): ?>
                                <div class="col-6 col-md-3">
                                    <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-users d-block mb-1"></i>
                                        <small>Adhérents</small>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="<?php echo e(route('admin.credits.index')); ?>" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-credit-card d-block mb-1"></i>
                                        <small>Crédits</small>
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php if(auth()->user()->role === 'adherent'): ?>
                                <div class="col-6 col-md-3">
                                    <a href="<?php echo e(route('adherent.credits.index')); ?>" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-credit-card d-block mb-1"></i>
                                        <small>Mes crédits</small>
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="col-6 col-md-3">
                                <a href="mailto:contact@sif-burkina.bf" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="fas fa-envelope d-block mb-1"></i>
                                    <small>Contact</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer d'erreur -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-white-50 small mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    SIFCash-Burkina - Système Intégré de Finance <?php echo e(date('Y')); ?>

                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.error-card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
        card.style.transition = 'all 0.6s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 200);
    
    // Animation des boutons au survol
    document.querySelectorAll('.btn-modern').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/errors/404.blade.php ENDPATH**/ ?>