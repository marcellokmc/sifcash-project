

<?php $__env->startSection('title', 'Tableau de bord Agent'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord Agent'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Statistiques Agent -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Adhérents de l'agence</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['total_adherents']); ?></div>
                        <div class="mt-1">
                            <?php if($stats['adherents_en_attente'] > 0): ?>
                                <small class="text-warning">
                                    <i class="fas fa-clock me-1"></i><?php echo e($stats['adherents_en_attente']); ?> en attente
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Documents à valider</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['documents_en_attente']); ?></div>
                        <div class="mt-1">
                            <?php if($stats['documents_en_attente'] > 0): ?>
                                <a href="<?php echo e(route('admin.validation.documents')); ?>" class="text-warning small text-decoration-none">
                                    <i class="fas fa-eye me-1"></i>Vérifier
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Ayants droit à valider</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['ayants_droit_en_attente']); ?></div>
                        <div class="mt-1">
                            <?php if($stats['ayants_droit_en_attente'] > 0): ?>
                                <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="text-info small text-decoration-none">
                                    <i class="fas fa-eye me-1"></i>Vérifier
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-friends fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Mon Agence</div>
                        <div class="h6 mb-0 font-weight-bold text-dark"><?php echo e($agence->nom); ?></div>
                        <div class="mt-1">
                            <small class="text-muted"><?php echo e($stats['adherents_actifs']); ?> adhérents actifs</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Actions Rapides -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-outline-primary text-start">
                        <i class="fas fa-users me-2"></i>Gérer les adhérents
                        <?php if($stats['adherents_en_attente'] > 0): ?>
                            <span class="badge bg-warning ms-2"><?php echo e($stats['adherents_en_attente']); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?php echo e(route('admin.validation.documents')); ?>" class="btn btn-outline-warning text-start">
                        <i class="fas fa-file-alt me-2"></i>Valider les documents
                        <?php if($stats['documents_en_attente'] > 0): ?>
                            <span class="badge bg-warning ms-2"><?php echo e($stats['documents_en_attente']); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="btn btn-outline-info text-start">
                        <i class="fas fa-user-friends me-2"></i>Valider les ayants droit
                        <?php if($stats['ayants_droit_en_attente'] > 0): ?>
                            <span class="badge bg-warning ms-2"><?php echo e($stats['ayants_droit_en_attente']); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <button class="btn btn-outline-success text-start">
                        <i class="fas fa-credit-card me-2"></i>Évaluer les crédits
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations Agence -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informations de l'Agence</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">Code:</th>
                        <td><?php echo e($agence->code); ?></td>
                    </tr>
                    <tr>
                        <th>Province:</th>
                        <td><?php echo e($agence->province); ?></td>
                    </tr>
                    <tr>
                        <th>Département:</th>
                        <td><?php echo e($agence->departement ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Adresse:</th>
                        <td><?php echo e($agence->adresse); ?></td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td><?php echo e($agence->contact); ?></td>
                    </tr>
                    <tr>
                        <th>Statut:</th>
                        <td>
                            <?php if($agence->active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Mes Connexions Récentes -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>Mes Connexions
                </h6>
            </div>
            <div class="card-body">
                <?php if(isset($recentActivity) && $recentActivity->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentActivity->where('user_id', auth()->id())->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-<?php echo e($activity->action === 'login' ? 'sign-in-alt text-success' : 'sign-out-alt text-warning'); ?>"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <small class="d-block fw-bold text-dark">
                                        <?php echo e($activity->action === 'login' ? 'Connexion' : 'Déconnexion'); ?>

                                    </small>
                                    <small class="text-muted">
                                        <?php echo e($activity->created_at->diffForHumans()); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune activité récente
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Alertes de validation -->
<?php if($stats['documents_en_attente'] > 0 || $stats['ayants_droit_en_attente'] > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Validations en attente</h6>
            <div class="row mt-2">
                <?php if($stats['documents_en_attente'] > 0): ?>
                <div class="col-md-6">
                    <i class="fas fa-file-alt me-2"></i>
                    <strong><?php echo e($stats['documents_en_attente']); ?></strong> document(s) en attente de validation
                    <a href="<?php echo e(route('admin.validation.documents')); ?>" class="btn btn-sm btn-warning ms-2">
                        <i class="fas fa-check-circle me-1"></i>Valider
                    </a>
                </div>
                <?php endif; ?>
                <?php if($stats['ayants_droit_en_attente'] > 0): ?>
                <div class="col-md-6">
                    <i class="fas fa-users me-2"></i>
                    <strong><?php echo e($stats['ayants_droit_en_attente']); ?></strong> ayant(s) droit en attente de validation
                    <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="btn btn-sm btn-info ms-2">
                        <i class="fas fa-check-circle me-1"></i>Valider
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Statistiques détaillées -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-2"></i>Mes Statistiques d'Agent
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h6>Mes Adhérents</h6>
                            <h4 class="text-primary"><?php echo e($stats['total_adherents']); ?></h4>
                            <small class="text-muted">Qui me sont affectés</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-user-check fa-2x text-success mb-2"></i>
                            <h6>Actifs</h6>
                            <h4 class="text-success"><?php echo e($stats['adherents_actifs']); ?></h4>
                            <small class="text-muted">Comptes activés</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h6>En Attente</h6>
                            <h4 class="text-warning"><?php echo e($stats['adherents_en_attente']); ?></h4>
                            <small class="text-muted">A vérifier</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-sign-in-alt fa-2x text-info mb-2"></i>
                            <h6>Mes Connexions</h6>
                            <h4 class="text-info"><?php echo e($stats['recent_activity']); ?></h4>
                            <small class="text-muted">Aujourd'hui</small>
                        </div>
                    </div>
                </div>
                
                <?php if(isset($stats['credits_en_attente']) || isset($stats['credits_approuves'])): ?>
                <hr class="my-4">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-file-invoice-dollar fa-2x text-warning mb-2"></i>
                            <h6>Crédits en Attente</h6>
                            <h4 class="text-warning"><?php echo e($stats['credits_en_attente'] ?? 0); ?></h4>
                            <small class="text-muted">De mes adhérents</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h6>Crédits Approuvés</h6>
                            <h4 class="text-success"><?php echo e($stats['credits_approuves'] ?? 0); ?></h4>
                            <small class="text-muted">De mes adhérents</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="p-3 border rounded bg-light">
                            <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
                            <h6>Documents à Traiter</h6>
                            <h4 class="text-info"><?php echo e($stats['documents_en_attente'] ?? 0); ?></h4>
                            <small class="text-muted">De mes adhérents</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/dashboard/agent.blade.php ENDPATH**/ ?>