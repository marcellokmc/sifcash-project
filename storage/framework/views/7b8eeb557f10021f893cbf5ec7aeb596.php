

<?php $__env->startSection('title', 'Affectations par agence'); ?>
<?php $__env->startSection('page-title', 'Affectations par Agence'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Liste des agences -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building me-2"></i>Agences
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $agences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('admin.affectations.par-agence', $agence->id)); ?>" 
                           class="list-group-item list-group-item-action <?php echo e($agenceSelectionnee && $agenceSelectionnee->id == $agence->id ? 'active' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-building me-2"></i>
                                    <strong><?php echo e($agence->nom); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($agence->ville ?? $agence->departement); ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary rounded-pill"><?php echo e($agence->adherents_count); ?></span>
                                    <br>
                                    <small class="text-muted"><?php echo e($agence->users_count); ?> agents</small>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de l'agence sélectionnée -->
    <div class="col-md-8">
        <?php if($agenceSelectionnee): ?>
            <!-- Statistiques de l'agence -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Adhérents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_adherents']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Affectés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['adherents_affectes']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Non affectés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['adherents_non_affectes']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Agents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['agents_actifs']); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agents de l'agence -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-user-tie me-2"></i>Agents de <?php echo e($agenceSelectionnee->nom); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <?php if($agents->count() > 0): ?>
                        <div class="row">
                            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-user me-1"></i><?php echo e($agent->name); ?>

                                                    </h6>
                                                    <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $agent->role))); ?></span>
                                                </div>
                                                <div class="text-end">
                                                    <h4 class="text-success mb-0"><?php echo e($agent->adherents_geres_count); ?></h4>
                                                    <small class="text-muted">adhérents</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <a href="<?php echo e(route('admin.affectations.par-agent', $agent->id)); ?>" 
                                                   class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i>Voir les adhérents
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user-times fa-3x mb-3"></i>
                            <p>Aucun agent actif dans cette agence</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Adhérents de l'agence -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users me-2"></i>Adhérents de <?php echo e($agenceSelectionnee->nom); ?> (<?php echo e($adherents->total()); ?>)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Membre ID</th>
                                    <th>Nom complet</th>
                                    <th>Agents gestionnaires</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><strong><?php echo e($adherent->membre_id); ?></strong></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>">
                                            <?php echo e($adherent->nom_complet); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <?php if($adherent->agents_count > 0): ?>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <?php $__currentLoopData = $adherent->agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge <?php echo e($agent->pivot->is_principal ? 'bg-success' : 'bg-secondary'); ?>" 
                                                          title="<?php echo e($agent->pivot->is_principal ? 'Principal' : 'Secondaire'); ?>">
                                                        <?php echo e($agent->name); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Aucun agent</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($adherent->statut_compte == 'actif'): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.adherents.affectation', $adherent)); ?>" 
                                           class="btn btn-sm btn-primary" title="Gérer affectation">
                                            <i class="fas fa-user-tie"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Aucun adhérent dans cette agence</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($adherents->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo e($adherents->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">Sélectionnez une agence</h5>
                    <p class="text-muted">Choisissez une agence dans la liste de gauche pour voir ses adhérents et agents</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/affectations/par-agence.blade.php ENDPATH**/ ?>