<?php $__env->startSection('title', 'Accès Refusé - 403'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .error-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        position: relative;
        overflow: hidden;
    }
    
    .error-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><path d="M30 5L50 25L30 45L10 25Z" fill="%23ffffff" fill-opacity="0.05"/></svg>') repeat;
        animation: move 25s linear infinite;
    }
    
    @keyframes move {
        0% { transform: translateX(0) translateY(0); }
        100% { transform: translateX(-60px) translateY(-60px); }
    }
    
    .error-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(243, 156, 18, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .error-number {
        font-size: 8rem;
        font-weight: 900;
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    .error-icon {
        font-size: 4rem;
        color: #f39c12;
        animation: shake 3s ease-in-out infinite;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
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
    
    .btn-warning-modern {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }
    
    .permission-badge {
        background: rgba(243, 156, 18, 0.1);
        border: 2px solid #f39c12;
        border-radius: 15px;
        padding: 15px;
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
                <li class="breadcrumb-item active">Accès Refusé</li>
            </ol>
        </nav>
        
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="error-card p-4 p-md-5 text-center">
                    <!-- Numéro d'erreur -->
                    <div class="error-number mb-3">403</div>
                    
                    <!-- Icône -->
                    <div class="error-icon mb-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    
                    <!-- Message principal -->
                    <h2 class="h3 fw-bold text-dark mb-3">Accès Refusé</h2>
                    <p class="lead text-muted mb-4">
                        Désolé, vous n'avez pas les permissions nécessaires pour accéder à cette ressource.
                    </p>
                    
                    <!-- Informations sur les permissions -->
                    <div class="permission-badge mb-4">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-user-shield text-warning me-2"></i>
                            <strong class="text-warning">Permissions requises</strong>
                        </div>
                        <small class="text-muted">
                            Cette page est réservée aux utilisateurs autorisés.
                            <?php if(auth()->guard()->check()): ?>
                                <br>Votre rôle actuel : <span class="badge bg-info"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                            <?php endif; ?>
                        </small>
                    </div>
                    
                    <!-- Actions possibles -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Que pouvez-vous faire ?</h6>
                        <div class="row g-2 text-start">
                            <?php if(auth()->guard()->check()): ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user-cog text-info me-3"></i>
                                    <small>Vérifiez vos permissions avec votre administrateur</small>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-sign-in-alt text-info me-3"></i>
                                    <small>Connectez-vous avec un compte autorisé</small>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-arrow-left text-info me-3"></i>
                                    <small>Retournez à la page précédente</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-home text-info me-3"></i>
                                    <small>Retournez à la page d'accueil</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-4">
                        <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-warning-modern btn-modern">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </a>
                        <?php endif; ?>
                        <button onclick="history.back()" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </button>
                    </div>
                    
                    <!-- Informations de contact -->
                    <?php if(auth()->guard()->check()): ?>
                    <div class="pt-4 border-top">
                        <h6 class="text-muted mb-3">Besoin d'accès ?</h6>
                        <p class="small text-muted mb-3">
                            Si vous pensez avoir besoin d'accès à cette ressource, contactez votre administrateur.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <a href="mailto:admin@sif-burkina.bf" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-envelope me-2"></i>Contacter l'Admin
                            </a>
                            <?php if(auth()->user()->role !== 'admin'): ?>
                            <button class="btn btn-outline-secondary btn-sm" onclick="requestAccess()">
                                <i class="fas fa-paper-plane me-2"></i>Demander l'Accès
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
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

<!-- Modal de demande d'accès -->
<?php if(auth()->guard()->check()): ?>
<div class="modal fade" id="accessRequestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-paper-plane me-2"></i>Demander l'Accès
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="accessRequestForm">
                    <div class="mb-3">
                        <label class="form-label">Ressource demandée :</label>
                        <input type="text" class="form-control" value="<?php echo e(request()->fullUrl()); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Justification :</label>
                        <textarea class="form-control" rows="3" placeholder="Expliquez pourquoi vous avez besoin de cet accès..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning">Envoyer la Demande</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.error-card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(40px) rotateX(15deg)';
    
    setTimeout(() => {
        card.style.transition = 'all 0.7s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0) rotateX(0)';
    }, 250);
    
    // Animation des boutons
    document.querySelectorAll('.btn-modern').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = 'none';
        });
    });
});

// Fonction de demande d'accès
function requestAccess() {
    const modal = new bootstrap.Modal(document.getElementById('accessRequestModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/errors/403.blade.php ENDPATH**/ ?>