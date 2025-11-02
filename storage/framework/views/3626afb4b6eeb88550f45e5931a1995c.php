

<?php $__env->startSection('title', 'Gestion des Utilisateurs'); ?>
<?php $__env->startSection('page-title', 'Liste des Utilisateurs'); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Utilisateurs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($users->total()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Actifs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($users->where('active', true)->where('suspended_at', null)->count()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Suspendus</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($users->whereNotNull('suspended_at')->count()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-ban fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactifs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($users->where('active', false)->whereNull('suspended_at')->count()); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users me-2"></i>Liste des Utilisateurs
        </h6>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nouvel Utilisateur
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="50">#</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Agence</th>
                        <th>Statut</th>
                        <th width="200" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="align-middle <?php echo e($user->isSuspended() ? 'table-danger' : ''); ?>">
                        <td><?php echo e($user->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <strong><?php echo e($user->name); ?></strong>
                                    <?php if($user->isSuspended()): ?>
                                        <br>
                                        <small class="text-danger">
                                            <i class="fas fa-ban me-1"></i>Suspendu
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-envelope text-muted me-1"></i><?php echo e($user->email); ?>

                        </td>
                        <td>
                            <?php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'agent' => 'primary',
                                    'chef_service' => 'info',
                                    'superviseur' => 'success',
                                ];
                                $roleLabels = [
                                    'admin' => 'Administrateur',
                                    'agent' => 'Agent',
                                    'chef_service' => 'Chef de Service',
                                    'superviseur' => 'Superviseur',
                                ];
                            ?>
                            <span class="badge bg-<?php echo e($roleColors[$user->role] ?? 'secondary'); ?>">
                                <i class="fas fa-user-shield me-1"></i><?php echo e($roleLabels[$user->role] ?? $user->role); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($user->agence): ?>
                                <i class="fas fa-building text-muted me-1"></i><?php echo e($user->agence->nom); ?>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($user->isSuspended()): ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-ban me-1"></i>Suspendu
                                </span>
                            <?php elseif($user->active): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-pause-circle me-1"></i>Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="<?php echo e(route('admin.users.show', $user)); ?>" 
                                   class="btn btn-info" 
                                   title="Voir le profil"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                   class="btn btn-warning"
                                   title="Modifier"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $user)): ?>
                                    <button type="button" 
                                            class="btn btn-primary" 
                                            onclick="openResetPasswordModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', false)"
                                            title="Réinitialiser mot de passe"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    
                                    <?php if($user->isSuspended()): ?>
                                        <button type="button" 
                                                class="btn btn-success" 
                                                onclick="openActivateModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', '<?php echo e($user->suspended_at?->format('d/m/Y H:i')); ?>', '<?php echo e($user->suspension_reason); ?>', '<?php echo e($user->suspendedByUser?->name); ?>', false)"
                                                title="Réactiver"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    <?php elseif(!$user->isAdmin()): ?>
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                onclick="openSuspendModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>', false)"
                                                title="Suspendre"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if($users->hasPages()): ?>
            <div class="p-3 border-top bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de <?php echo e($users->firstItem()); ?> à <?php echo e($users->lastItem()); ?> sur <?php echo e($users->total()); ?> résultats
                    </div>
                    <div>
                        <?php echo e($users->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('backoffice.partials.account-management-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopPush(); ?>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-danger {
    border-left: 4px solid #e74a3b;
}

.border-left-warning {
    border-left: 4px solid #f6c23e;
}

.table tbody tr.table-danger {
    background-color: #f8d7da !important;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/users/index.blade.php ENDPATH**/ ?>