

<?php $__env->startSection('title', 'Gestion des Plans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-1">Gestion des Plans</h2>
            <p class="text-muted mb-0">Configuration des plans d'épargne et de crédit</p>
        </div>
        <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Plan
        </a>
    </div>

    <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.plans.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Type de plan</label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="epargne" <?php echo e(request('type') == 'epargne' ? 'selected' : ''); ?>>Épargne</option>
                            <option value="credit" <?php echo e(request('type') == 'credit' ? 'selected' : ''); ?>>Crédit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="1" <?php echo e(request('statut') == '1' ? 'selected' : ''); ?>>Actif</option>
                            <option value="0" <?php echo e(request('statut') == '0' ? 'selected' : ''); ?>>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nom du plan..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des plans -->
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 <?php echo e($plan->actif ? 'border-success' : 'border-secondary'); ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas <?php echo e($plan->type_plan == 'epargne' ? 'fa-piggy-bank text-success' : 'fa-credit-card text-primary'); ?> me-2"></i>
                        <?php echo e($plan->nom); ?>

                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.plans.show', $plan)); ?>">
                                <i class="fas fa-eye me-2"></i>Voir détails
                            </a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.plans.edit', $plan)); ?>">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if($plan->actif): ?>
                            <li>
                                <form action="<?php echo e(route('admin.plans.deactivate', $plan)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-warning" 
                                            onclick="return confirm('Désactiver ce plan ?')">
                                        <i class="fas fa-pause me-2"></i>Désactiver
                                    </button>
                                </form>
                            </li>
                            <?php else: ?>
                            <li>
                                <form action="<?php echo e(route('admin.plans.activate', $plan)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-success" 
                                            onclick="return confirm('Activer ce plan ?')">
                                        <i class="fas fa-play me-2"></i>Activer
                                    </button>
                                </form>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted"><?php echo e($plan->description); ?></p>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Montant min.</small>
                            <strong><?php echo e(number_format($plan->montant_min, 0, ',', ' ')); ?> FCFA</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Montant max.</small>
                            <strong><?php echo e(number_format($plan->montant_max, 0, ',', ' ')); ?> FCFA</strong>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Taux d'intérêt</small>
                            <strong class="text-success"><?php echo e($plan->taux_interet); ?>%</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Périodicité</small>
                            <strong><?php echo e(ucfirst($plan->periodicite)); ?></strong>
                        </div>
                    </div>
                    
                    <?php if($plan->frais_adhesion > 0): ?>
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <small class="text-muted d-block">Frais d'adhésion</small>
                            <strong class="text-warning"><?php echo e(number_format($plan->frais_adhesion, 0, ',', ' ')); ?> FCFA</strong>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <span class="badge <?php echo e($plan->actif ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($plan->actif ? 'Actif' : 'Inactif'); ?>

                        </span>
                        <span class="badge bg-info">
                            <?php echo e($plan->adhesions_count ?? $plan->adhesions->count()); ?> adhésion(s)
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            Créé le <?php echo e($plan->created_at->format('d/m/Y')); ?>

                        </small>
                        <div>
                            <a href="<?php echo e(route('admin.plans.show', $plan)); ?>" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                            <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-sm btn-primary">
                                Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                    <h4 class="text-muted mt-3">Aucun plan trouvé</h4>
                    <p class="text-muted">Commencez par créer votre premier plan d'épargne ou de crédit.</p>
                    <a href="<?php echo e(route('admin.plans.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer un plan
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if(method_exists($plans, 'links')): ?>
        <div class="d-flex justify-content-center">
            <?php echo e($plans->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.border-success {
    border-left: 4px solid #28a745 !important;
}
.border-secondary {
    border-left: 4px solid #6c757d !important;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/plans/index.blade.php ENDPATH**/ ?>