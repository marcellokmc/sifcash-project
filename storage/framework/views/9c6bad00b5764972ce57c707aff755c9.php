

<?php $__env->startSection('title', 'Détails Utilisateur'); ?>
<?php $__env->startSection('page-title', 'Détails de l\'Utilisateur'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informations Personnelles</h6>
                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nom complet:</th>
                                <td><?php echo e($user->name); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo e($user->email); ?></td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td><?php echo e($user->phone ?? 'Non renseigné'); ?></td>
                            </tr>
                            <tr>
                                <th>Rôle:</th>
                                <td>
                                    <span class="badge bg-<?php echo e($user->role === 'admin' ? 'danger' : ($user->role === 'agent' ? 'primary' : 'success')); ?>">
                                        <?php echo e($user->role); ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Matricule:</th>
                                <td><?php echo e($user->matricule ?? 'Non renseigné'); ?></td>
                            </tr>
                            <tr>
                                <th>Date d'embauche:</th>
                                <td><?php echo e($user->date_embauche ? $user->date_embauche->format('d/m/Y') : 'Non renseignée'); ?></td>
                            </tr>
                            <tr>
                                <th>Agence:</th>
                                <td><?php echo e($user->agence->nom ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    <span class="badge bg-<?php echo e($user->active ? 'success' : 'secondary'); ?>">
                                        <?php echo e($user->active ? 'Actif' : 'Inactif'); ?>

                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-user fa-3x text-primary"></i>
                    </div>
                    <h5>Activité de l'utilisateur</h5>
                    <div class="mt-3">
                        <p class="mb-1">
                            <i class="fas fa-sign-in-alt text-success me-2"></i>
                            Connexions: <?php echo e($user->logsConnexions->where('action', 'login')->count()); ?>

                        </p>
                        <p class="mb-1">
                            <i class="fas fa-sign-out-alt text-warning me-2"></i>
                            Déconnexions: <?php echo e($user->logsConnexions->where('action', 'logout')->count()); ?>

                        </p>
                        <p class="mb-0">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            Échecs: <?php echo e($user->logsConnexions->where('action', 'failed_login')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions Rapides</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.users.logs', $user)); ?>" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-history me-1"></i>Voir les logs
                    </a>
                    <form action="<?php echo e(route('admin.users.toggle-status', $user)); ?>" method="POST" class="d-grid">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-<?php echo e($user->active ? 'warning' : 'success'); ?> btn-sm">
                            <i class="fas fa-<?php echo e($user->active ? 'pause' : 'play'); ?> me-1"></i>
                            <?php echo e($user->active ? 'Désactiver' : 'Activer'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
        <div class="card shadow mt-3">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-shield-alt me-2"></i>Gestion du Compte
                </h6>
            </div>
            <div class="card-body">
                <?php if($user->isSuspended()): ?>
                    <div class="alert alert-danger mb-3">
                        <i class="fas fa-ban me-2"></i>
                        <strong>Compte Suspendu</strong>
                        <hr>
                        <p class="mb-1"><strong>Depuis:</strong> <?php echo e($user->suspended_at->format('d/m/Y H:i')); ?></p>
                        <p class="mb-1"><strong>Raison:</strong> <?php echo e($user->suspension_reason); ?></p>
                        <?php if($user->suspendedByUser): ?>
                            <p class="mb-0"><strong>Par:</strong> <?php echo e($user->suspendedByUser->name); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning btn-sm" 
                            onclick="openResetPasswordModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', false)">
                        <i class="fas fa-key me-1"></i>Réinitialiser le mot de passe
                    </button>
                    
                    <?php if($user->isSuspended()): ?>
                        <button type="button" class="btn btn-success btn-sm" 
                                onclick="openActivateModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', '<?php echo e($user->suspended_at?->format('d/m/Y H:i')); ?>', '<?php echo e($user->suspension_reason); ?>', '<?php echo e($user->suspendedByUser?->name); ?>', false)">
                            <i class="fas fa-check-circle me-1"></i>Réactiver le compte
                        </button>
                    <?php elseif(!$user->isAdmin()): ?>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="openSuspendModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', false)">
                            <i class="fas fa-ban me-1"></i>Suspendre le compte
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Dernières Connexions</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Date/Heure</th>
                        <th>Action</th>
                        <th>Adresse IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $user->logsConnexions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($log->action === 'login' ? 'success' : ($log->action === 'logout' ? 'warning' : 'danger')); ?>">
                                <?php echo e($log->action); ?>

                            </span>
                        </td>
                        <td><?php echo e($log->ip_address); ?></td>
                        <td><small><?php echo e(Str::limit($log->user_agent, 50)); ?></small></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center">Aucun log de connexion</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($user->logsConnexions->count() > 5): ?>
        <div class="text-center mt-2">
            <a href="<?php echo e(route('admin.users.logs', $user)); ?>" class="btn btn-sm btn-outline-primary">
                Voir tous les logs
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('backoffice.partials.account-management-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/users/show.blade.php ENDPATH**/ ?>