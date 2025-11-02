

<?php $__env->startSection('title', 'Mes Crédits'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.credit-card {
    transition: all 0.3s ease;
    border-left: 4px solid #dee2e6;
}
.credit-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.credit-card.en_attente { border-left-color: #ffc107; }
.credit-card.approuve { border-left-color: #28a745; }
.credit-card.rejete { border-left-color: #dc3545; }
.credit-card.cloture { border-left-color: #6c757d; }

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.filter-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    padding: 10px 20px;
}

.filter-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background: none;
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                <div class="mb-3 mb-lg-0">
                    <h2 class="mb-1 text-dark">
                        <i class="mdi mdi-bank me-2 text-primary"></i>
                        Mes Crédits
                    </h2>
                    <p class="text-secondary mb-0 fw-medium">Gestion et suivi de vos demandes de crédit</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('adherent.credits.create')); ?>" class="btn btn-primary">
                        <i class="mdi mdi-plus me-2"></i>Nouvelle demande
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0 text-dark fw-bold"><?php echo e($credits->count()); ?></h3>
                            <p class="mb-0 small text-dark fw-semibold">Total demandes</p>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-chart-line fa-2x text-primary opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white" style="background: #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo e($credits->where('statut', 'en_attente')->count()); ?></h3>
                            <p class="mb-0 small">En attente</p>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white" style="background: #28a745;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo e($credits->where('statut', 'approuvé')->count()); ?></h3>
                            <p class="mb-0 small">Approuvés</p>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white" style="background: #dc3545;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo e($credits->where('statut', 'rejeté')->count()); ?></h3>
                            <p class="mb-0 small">Rejetés</p>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-times-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white" style="background: #17a2b8;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h3 class="mb-0"><?php echo e(number_format($credits->whereIn('statut', ['approuvé','remboursé'])->sum('montant_accorde'), 0, ',', ' ')); ?></h3>
                            <p class="mb-0 small">Total accordé (FCFA)</p>
                        </div>
                        <div class="ms-auto">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres par onglets -->
    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs filter-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="filterCredits('all')">
                        <i class="mdi mdi-format-list-bulleted"></i> Tous les crédits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="filterCredits('en_attente')">
                        <i class="mdi mdi-clock"></i> En attente
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="filterCredits('approuve')">
                        <i class="mdi mdi-check-circle"></i> Approuvés
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="filterCredits('rejete')">
                        <i class="mdi mdi-close-circle"></i> Rejetés
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="filterCredits('cloture')">
                        <i class="mdi mdi-archive"></i> Clôturés
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Liste des crédits en cartes -->
    <?php if($credits->count() > 0): ?>
    <div class="row" id="credits-container">
        <?php $__currentLoopData = $credits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $statusKey = \Illuminate\Support\Str::slug($credit->statut ?? 'en_attente', '_');
            if ($credit->etat === 'cloture') { $statusKey = 'cloture'; }
        ?>
        <div class="col-lg-6 col-xl-4 mb-4 credit-item" data-status="<?php echo e($statusKey); ?>">
            <div class="credit-card card h-100 <?php echo e($statusKey); ?>">
                <div class="card-body">
                    <!-- En-tête de la carte -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Crédit #<?php echo e($credit->id); ?></h6>
                            <small class="text-secondary"><?php echo e($credit->date_demande->format('d/m/Y')); ?></small>
                        </div>
                        <div>
                            <?php
                                $status = \Illuminate\Support\Str::slug($credit->statut ?? 'en_attente','_');
                                if ($credit->etat === 'cloture') { $status = 'cloture'; }
                                $statusConfig = [
                                    'en_attente' => ['class' => 'warning', 'icon' => 'fas fa-clock', 'text' => 'En attente'],
                                    'approuve' => ['class' => 'success', 'icon' => 'fas fa-check-circle', 'text' => 'Approuvé'],
                                    'rejete' => ['class' => 'danger', 'icon' => 'fas fa-times-circle', 'text' => 'Rejeté'],
                                    'cloture' => ['class' => 'secondary', 'icon' => 'fas fa-archive', 'text' => 'Clôturé'],
                                ];
                                $config = $statusConfig[$status] ?? ['class' => 'secondary', 'icon' => 'fas fa-question-circle', 'text' => ucfirst($credit->statut ?? '—')];
                            ?>
                            <span class="badge bg-<?php echo e($config['class']); ?>">
                                <i class="<?php echo e($config['icon']); ?> me-1"></i>
                                <?php echo e($config['text']); ?>

                            </span>
                        </div>
                    </div>

                    <!-- Montants -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <small class="text-secondary fw-semibold d-block">Demandé</small>
                                <strong class="text-primary fs-5"><?php echo e(number_format($credit->montant_demande, 0, ',', ' ')); ?></strong>
                                <small class="text-secondary d-block">FCFA</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 <?php echo e($credit->montant_accorde ? 'bg-success bg-opacity-10' : 'bg-light'); ?> rounded">
                                <small class="text-secondary fw-semibold d-block">Accordé</small>
                                <?php if($credit->montant_accorde): ?>
                                    <strong class="text-success fs-5"><?php echo e(number_format($credit->montant_accorde, 0, ',', ' ')); ?></strong>
                                    <small class="text-secondary d-block">FCFA</small>
                                <?php else: ?>
                                    <strong class="text-secondary">—</strong>
                                    <small class="text-secondary d-block">En attente</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Détails du crédit -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="text-center">
                                <i class="fas fa-percent text-info mb-1 fs-5"></i>
                                <div class="fw-bold text-dark"><?php echo e(number_format($credit->taux, 1)); ?>%</div>
                                <small class="text-secondary fw-medium">Taux</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <i class="fas fa-calendar text-warning mb-1 fs-5"></i>
                                <div class="fw-bold text-dark"><?php echo e($credit->duree); ?></div>
                                <small class="text-secondary fw-medium">Mois</small>
                            </div>
                        </div>
                    </div>

                    <!-- Barre de progression pour les crédits actifs -->

                    <!-- Actions -->
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="<?php echo e(route('adherent.credits.show', $credit)); ?>" 
                           class="btn btn-outline-primary btn-sm" 
                           title="Voir les détails">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if(in_array($credit->statut, ['rejeté']) || ($credit->etat === 'cloture')): ?>
                        <button class="btn btn-outline-secondary btn-sm" 
                                title="Demander des informations" 
                                onclick="showContactModal()">
                            <i class="fas fa-question-circle"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if($credit->motif || $credit->motif_rejet): ?>
                <div class="card-footer bg-light border-0 small">
                    <?php if($credit->motif_rejet): ?>
                    <div class="text-danger fw-semibold">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Motif de rejet:</strong> <?php echo e($credit->motif_rejet); ?>

                    </div>
                    <?php elseif($credit->motif): ?>
                    <div class="text-dark">
                        <i class="fas fa-sticky-note me-1 text-primary"></i>
                        <strong>Motif:</strong> <?php echo e(Str::limit($credit->motif, 60)); ?>

                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="text-center">
            <i class="fas fa-credit-card text-primary" style="font-size: 4rem; opacity: 0.5;"></i>
            <h4 class="text-dark my-3">Aucun crédit demandé</h4>
            <p class="text-secondary mb-4 fw-medium">Vous n'avez pas encore fait de demande de crédit.<br>Commencez dès maintenant pour financer vos projets.</p>
            <a href="<?php echo e(route('adherent.credits.create')); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Faire ma première demande
            </a>
        </div>
    </div>
    <?php endif; ?>
    <!-- Pagination -->
    <?php if(method_exists($credits, 'links') && $credits->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($credits->links('pagination::bootstrap-4')); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Modal de contact pour informations -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-help-circle-outline me-2"></i>
                    Besoin d'aide ?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-info-lighten rounded-circle mx-auto mb-3">
                        <i class="mdi mdi-account-supervisor-circle avatar-title text-info display-6"></i>
                    </div>
                    <h5>Notre équipe est là pour vous aider</h5>
                    <p class="text-muted">Contactez-nous pour toute question sur votre crédit</p>
                </div>
                
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <i class="mdi mdi-phone text-success mb-2 h4"></i>
                            <h6>Téléphone</h6>
                            <p class="small text-muted mb-0">+225 XX XX XX XX</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded">
                            <i class="mdi mdi-email text-primary mb-2 h4"></i>
                            <h6>Email</h6>
                            <p class="small text-muted mb-0">support@sif.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="mailto:support@sif.com" class="btn btn-primary">
                    <i class="mdi mdi-email-outline me-2"></i>Envoyer un email
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialiser les tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Fonction de filtrage des crédits
function filterCredits(status) {
    const creditItems = document.querySelectorAll('.credit-item');
    const navLinks = document.querySelectorAll('.filter-tabs .nav-link');
    
    // Mise à jour des onglets actifs
    navLinks.forEach(link => link.classList.remove('active'));
    event.target.classList.add('active');
    
    // Filtrage des cartes
    creditItems.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        if (status === 'all' || itemStatus === status) {
            item.style.display = 'block';
            // Animation d'apparition
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        } else {
            item.style.display = 'none';
        }
    });
    
    // Mise à jour du compteur (si nécessaire)
    const visibleCount = document.querySelectorAll('.credit-item[style*="display: block"], .credit-item:not([style*="display: none"])').length;
    console.log(`Affichage de ${visibleCount} crédit(s) pour le filtre: ${status}`);
}

// Modal de contact
function showContactModal() {
    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
}

// Animations au scroll pour les cartes
function animateOnScroll() {
    const cards = document.querySelectorAll('.credit-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';
        observer.observe(card);
    });
}

// Initialiser les animations au chargement
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(animateOnScroll, 100);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/credits/index.blade.php ENDPATH**/ ?>