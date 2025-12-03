

<?php $__env->startSection('title', 'Plans Disponibles'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-primary mb-1">Plans Disponibles</h2>
                    <p class="text-muted mb-0">Choisissez le plan qui correspond à vos objectifs financiers</p>
                </div>
                <div class="text-end">
                    <small class="text-muted"><?php echo e($plans->count()); ?> plan(s) disponible(s)</small>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-3">
                    <form method="GET" id="filterForm" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="periodicite" class="form-label small">Périodicité</label>
                            <select name="periodicite" id="periodicite" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Toutes périodicités</option>
                                <option value="hebdomadaire" <?php echo e(request('periodicite') == 'hebdomadaire' ? 'selected' : ''); ?>>Hebdomadaire</option>
                                <option value="mensuel" <?php echo e(request('periodicite') == 'mensuel' ? 'selected' : ''); ?>>Mensuel</option>
                                <option value="trimestriel" <?php echo e(request('periodicite') == 'trimestriel' ? 'selected' : ''); ?>>Trimestriel</option>
                                <option value="semestriel" <?php echo e(request('periodicite') == 'semestriel' ? 'selected' : ''); ?>>Semestriel</option>
                                <option value="annuel" <?php echo e(request('periodicite') == 'annuel' ? 'selected' : ''); ?>>Annuel</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="montant" class="form-label small">Montant max (FCFA)</label>
                            <input type="number" name="montant" id="montant" class="form-control form-control-sm" 
                                   value="<?php echo e(request('montant')); ?>" placeholder="Ex: 100000" 
                                   onchange="document.getElementById('filterForm').submit();">
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo e(route('adherent.plans.index')); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-refresh me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille des plans -->
    <?php if($plans->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 plan-card" data-plan-id="<?php echo e($plan->id); ?>">
                        <!-- En-tête de la carte -->
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1 text-dark">
                                        <i class="fas fa-piggy-bank text-success me-2"></i>
                                        <?php echo e($plan->nom); ?>

                                    </h5>
                                    <small class="text-muted">
                                        Plan d'épargne
                                    </small>
                                </div>
                                <span class="badge bg-success">
                                    Épargne
                                </span>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body">
                            <p class="card-text text-muted mb-3"><?php echo e(Str::limit($plan->description, 100)); ?></p>
                            
                            <!-- Informations financières -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="border-end">
                                        <div class="h6 text-success mb-0"><?php echo e($plan->taux_interet); ?>%</div>
                                        <small class="text-muted">Taux d'intérêt</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="h6 text-info mb-0"><?php echo e(ucfirst($plan->periodicite)); ?></div>
                                    <small class="text-muted">Périodicité</small>
                                </div>
                            </div>

                            <!-- Montants -->
                            <div class="bg-light rounded p-3 mb-3">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-muted small">Montant minimum</div>
                                        <div class="fw-bold text-dark"><?php echo e(number_format($plan->montant_min, 0, ',', ' ')); ?> FCFA</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">Montant maximum</div>
                                        <div class="fw-bold text-dark"><?php echo e(number_format($plan->montant_max, 0, ',', ' ')); ?> FCFA</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Frais -->
                            <?php if($plan->frais_adhesion > 0 || $plan->frais_retrait > 0): ?>
                                <div class="mb-3">
                                    <h6 class="text-muted small mb-2">FRAIS</h6>
                                    <div class="d-flex justify-content-between">
                                        <?php if($plan->frais_adhesion > 0): ?>
                                            <small class="text-muted">
                                                Adhésion: <span class="text-dark"><?php echo e(number_format($plan->frais_adhesion)); ?> FCFA</span>
                                            </small>
                                        <?php endif; ?>
                                        <?php if($plan->frais_retrait > 0): ?>
                                            <small class="text-muted">
                                                Retrait: <span class="text-dark"><?php echo e(number_format($plan->frais_retrait)); ?> FCFA</span>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Durée minimum -->
                            <?php if($plan->duree_min_jours): ?>
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Engagement minimum : <?php echo e($plan->duree_min_jours); ?> jours
                                    </small>
                                </div>
                            <?php endif; ?>

                            <!-- Mes adhésions à ce plan -->
                            <?php
                                $adherentId = auth()->user()->adherent?->id;
                                $mesAdhesions = $adherentId ? $plan->adhesions->where('adherent_id', $adherentId) : collect();
                            ?>
                            <?php if($mesAdhesions->count() > 0): ?>
                                <div class="alert alert-info py-2 mb-3">
                                    <small>
                                        <i class="fas fa-info-circle me-1"></i>
                                        Vous avez <?php echo e($mesAdhesions->count()); ?> adhésion(s) à ce plan
                                        <?php if($mesAdhesions->where('statut', 'actif')->count() > 0): ?>
                                            (<?php echo e($mesAdhesions->where('statut', 'actif')->count()); ?> active(s))
                                        <?php endif; ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer bg-white border-top">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('adherent.plans.show', $plan)); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Voir les détails
                                </a>
                                <?php if($mesAdhesions->where('statut', 'actif')->count() === 0): ?>
                                    <a href="<?php echo e(route('adherent.adhesions.create', ['plan' => $plan->id])); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i>Souscrire à ce plan
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('adherent.adhesions.index', ['plan' => $plan->id])); ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-cog me-1"></i>Gérer mes adhésions
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <?php if(method_exists($plans, 'hasPages') && $plans->hasPages()): ?>
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <?php echo e($plans->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- Aucun plan disponible -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucun plan disponible</h4>
                    <p class="text-muted mb-4">
                        <?php if(request()->hasAny(['type', 'periodicite', 'montant'])): ?>
                            Aucun plan ne correspond à vos critères de recherche.
                        <?php else: ?>
                            Il n'y a actuellement aucun plan disponible.
                        <?php endif; ?>
                    </p>
                    <?php if(request()->hasAny(['type', 'periodicite', 'montant'])): ?>
                        <a href="<?php echo e(route('adherent.plans.index')); ?>" class="btn btn-primary">
                            <i class="fas fa-refresh me-2"></i>Voir tous les plans
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.plan-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #dee2e6;
}

.plan-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

@media (max-width: 768px) {
    .plan-card {
        margin-bottom: 1rem !important;
    }
    
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au scroll
    const cards = document.querySelectorAll('.plan-card');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/adherent/plans/index.blade.php ENDPATH**/ ?>