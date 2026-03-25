

<?php $__env->startSection('title', 'Détails Plan - ' . $plan->nom); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-1"><?php echo e($plan->nom); ?></h2>
            <p class="text-muted mb-0">
                <i class="fas <?php echo e($plan->type_plan == 'epargne' ? 'fa-piggy-bank text-success' : 'fa-credit-card text-primary'); ?> me-2"></i>
                Plan <?php echo e($plan->type_plan == 'epargne' ? "d'épargne" : 'de crédit'); ?> - 
                <?php echo e($plan->actif ? 'Actif' : 'Inactif'); ?>

            </p>
        </div>
        <div>
            <?php if($plan->actif): ?>
                <form action="<?php echo e(route('admin.plans.deactivate', $plan)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Désactiver ce plan ?')">
                        <i class="fas fa-pause me-1"></i>Désactiver
                    </button>
                </form>
            <?php else: ?>
                <form action="<?php echo e(route('admin.plans.activate', $plan)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success me-2" onclick="return confirm('Activer ce plan ?')">
                        <i class="fas fa-play me-1"></i>Activer
                    </button>
                </form>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Modifier
            </a>
            <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations du plan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Informations du Plan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nom du plan</label>
                                <div class="fw-bold"><?php echo e($plan->nom); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Type de plan</label>
                                <div>
                                    <span class="badge <?php echo e($plan->type_plan == 'epargne' ? 'bg-success' : 'bg-primary'); ?>">
                                        <i class="fas <?php echo e($plan->type_plan == 'epargne' ? 'fa-piggy-bank' : 'fa-credit-card'); ?> me-1"></i>
                                        Plan <?php echo e($plan->type_plan == 'epargne' ? "d'épargne" : 'de crédit'); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Périodicité</label>
                                <div><?php echo e(ucfirst($plan->periodicite)); ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Statut</label>
                                <div>
                                    <span class="badge <?php echo e($plan->actif ? 'bg-success' : 'bg-secondary'); ?>">
                                        <?php echo e($plan->actif ? 'Actif' : 'Inactif'); ?>

                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Durée minimum</label>
                                <div><?php echo e($plan->duree_min_jours); ?> jours</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Ordre d'affichage</label>
                                <div><?php echo e($plan->ordre_affichage); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label text-muted">Description</label>
                                <div class="border-start border-3 border-primary ps-3 bg-light p-3 rounded-end">
                                    <?php echo e($plan->description); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration financière -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator me-2 text-success"></i>Configuration Financière
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center border-end">
                                <div class="h4 text-primary mb-1"><?php echo e(number_format($plan->montant_min, 0, ',', ' ')); ?></div>
                                <div class="text-muted small">Montant Minimum (FCFA)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center border-end">
                                <div class="h4 text-primary mb-1"><?php echo e(number_format($plan->montant_max, 0, ',', ' ')); ?></div>
                                <div class="text-muted small">Montant Maximum (FCFA)</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="h4 text-success mb-1"><?php echo e($plan->taux_interet); ?>%</div>
                                <div class="text-muted small">Taux d'Intérêt</div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($plan->frais_adhesion > 0 || $plan->frais_retrait > 0): ?>
                    <hr class="my-4">
                    <div class="row">
                        <?php if($plan->frais_adhesion > 0): ?>
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h5 text-warning mb-1"><?php echo e(number_format($plan->frais_adhesion, 0, ',', ' ')); ?> FCFA</div>
                                <div class="text-muted small">Frais d'Adhésion</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($plan->frais_retrait > 0): ?>
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="h5 text-info mb-1"><?php echo e(number_format($plan->frais_retrait, 0, ',', ' ')); ?> FCFA</div>
                                <div class="text-muted small">Frais de Retrait</div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Conditions et règles -->
            <?php if($plan->conditions): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gavel me-2 text-warning"></i>Conditions et Règles
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                        $conditions = is_string($plan->conditions) ? json_decode($plan->conditions, true) : $plan->conditions;
                    ?>
                    
                    <?php if(is_array($conditions)): ?>
                        <div class="row">
                            <?php $__currentLoopData = $conditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-muted"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?>:</span>
                                    <strong class="ms-2"><?php echo e(is_bool($condition) ? ($condition ? 'Oui' : 'Non') : $condition); ?></strong>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="border-start border-3 border-warning ps-3 bg-light p-3 rounded-end">
                            <?php echo e($plan->conditions); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Liste des adhésions -->
            <?php if($plan->adhesions && $plan->adhesions->count() > 0): ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-info"></i>
                        Adhésions à ce Plan (<?php echo e($plan->adhesions->count()); ?>)
                    </h5>
                    <a href="<?php echo e(route('admin.adhesions.index', ['plan_id' => $plan->id])); ?>" class="btn btn-sm btn-outline-info">
                        Voir toutes les adhésions
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Adhérent</th>
                                    <th>Montant</th>
                                    <th>Date adhésion</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $plan->adhesions->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adhesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($adhesion->adherent->nom_complet)); ?>&size=32" 
                                                     class="rounded-circle" width="32" height="32">
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?php echo e($adhesion->adherent->nom_complet); ?></div>
                                                <small class="text-muted"><?php echo e($adhesion->adherent->membre_id); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo e(number_format($adhesion->montant, 0, ',', ' ')); ?> FCFA</strong>
                                    </td>
                                    <td><?php echo e($adhesion->date_adhesion ? $adhesion->date_adhesion->format('d/m/Y') : 'Non renseignée'); ?></td>
                                    <td>
                                        <?php switch($adhesion->statut):
                                            case ('active'): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Active
                                                </span>
                                                <?php break; ?>
                                            <?php case ('suspendue'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-pause me-1"></i>Suspendue
                                                </span>
                                                <?php break; ?>
                                            <?php case ('close'): ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Clôturée
                                                </span>
                                                <?php break; ?>
                                            <?php default: ?>
                                                <span class="badge bg-info"><?php echo e(ucfirst($adhesion->statut)); ?></span>
                                        <?php endswitch; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.adhesions.show', $adhesion)); ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($plan->adhesions->count() > 10): ?>
                <div class="card-footer text-center">
                    <a href="<?php echo e(route('admin.adhesions.index', ['plan_id' => $plan->id])); ?>" class="btn btn-outline-primary">
                        Voir les <?php echo e($plan->adhesions->count() - 10); ?> autres adhésions
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar droite -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h3 text-primary mb-1"><?php echo e($plan->adhesions->count()); ?></div>
                            <div class="text-muted small">Adhésions totales</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h3 text-success mb-1"><?php echo e($plan->adhesions->where('statut', 'active')->count()); ?></div>
                            <div class="text-muted small">Adhésions actives</div>
                        </div>
                        <div class="col-6">
                            <div class="h3 text-warning mb-1"><?php echo e($plan->adhesions->where('statut', 'suspendue')->count()); ?></div>
                            <div class="text-muted small">Suspendues</div>
                        </div>
                        <div class="col-6">
                            <div class="h3 text-secondary mb-1"><?php echo e($plan->adhesions->where('statut', 'close')->count()); ?></div>
                            <div class="text-muted small">Clôturées</div>
                        </div>
                    </div>

                    <?php if($plan->adhesions->count() > 0): ?>
                    <hr>
                    <div class="text-center">
                        <div class="h4 text-info mb-1">
                            <?php echo e(number_format($plan->adhesions->sum('montant'), 0, ',', ' ')); ?> FCFA
                        </div>
                        <div class="text-muted small">Montant total des adhésions</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.plans.edit', $plan)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Modifier ce plan
                        </a>
                        <a href="<?php echo e(route('admin.adhesions.index', ['plan_id' => $plan->id])); ?>" class="btn btn-outline-info">
                            <i class="fas fa-users me-2"></i>Voir les adhésions
                        </a>
                        <a href="<?php echo e(route('adherent.plans.show', $plan)); ?>" class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Vue adhérent
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-secondary"></i>Informations Système
                    </h5>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <div class="mb-2">
                            <strong>ID:</strong> #<?php echo e($plan->id); ?>

                        </div>
                        <div class="mb-2">
                            <strong>Créé le:</strong> <?php echo e($plan->created_at->format('d/m/Y H:i')); ?>

                        </div>
                        <div class="mb-2">
                            <strong>Modifié le:</strong> <?php echo e($plan->updated_at->format('d/m/Y H:i')); ?>

                        </div>
                        <div>
                            <strong>Statut:</strong> 
                            <span class="badge <?php echo e($plan->actif ? 'bg-success' : 'bg-secondary'); ?> ms-1">
                                <?php echo e($plan->actif ? 'Actif' : 'Inactif'); ?>

                            </span>
                        </div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/plans/show.blade.php ENDPATH**/ ?>