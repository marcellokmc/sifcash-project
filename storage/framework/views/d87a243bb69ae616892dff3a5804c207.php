

<?php $__env->startSection('title', 'Tableau de bord Chef de service'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord - Mon Agence'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Adhérents (Agence)</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['total_adherents']); ?></div>
                        <small class="text-muted">Actifs: <?php echo e($stats['adherents_actifs']); ?> | En attente: <?php echo e($stats['adherents_en_attente']); ?></small>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Documents à valider</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['documents_en_attente']); ?></div>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ayants droit en attente</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['ayants_droit_en_attente']); ?></div>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Crédits (Agence)</div>
                        <div class="h6 mb-0 font-weight-bold text-dark">En attente: <?php echo e($stats['credits_en_attente']); ?> | Approuvés: <?php echo e($stats['credits_approuves']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Agents actifs</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['agents_actifs'] ?? 0); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Épargnes actives</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['epargnes_actives'] ?? 0); ?></div>
                        <small class="text-muted">Solde: <?php echo e(number_format($stats['epargnes_solde_total'] ?? 0, 0, ',', ' ')); ?> FCFA</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-piggy-bank fa-2x text-secondary"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Adhésions actives</div>
                        <div class="h5 mb-0 font-weight-bold text-dark"><?php echo e($stats['adhesions_actives'] ?? 0); ?></div>
                        <small class="text-muted">En attente: <?php echo e($stats['adhesions_attente'] ?? 0); ?></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-handshake fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Crédits</div>
                        <div class="h6 mb-0 font-weight-bold text-dark">Approuvés/En cours: <?php echo e($stats['credits_approuves'] ?? 0); ?></div>
                        <small class="text-muted">En attente: <?php echo e($stats['credits_en_attente'] ?? 0); ?></small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Activité récente de l'agence (<?php echo e($agence->nom ?? 'N/A'); ?>)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>IP</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($log->user->name ?? 'N/A'); ?> (<?php echo e($log->user->role ?? '-'); ?>)</td>
                                    <td><?php echo e($log->action ?? '-'); ?></td>
                                    <td><?php echo e($log->ip_address ?? '-'); ?></td>
                                    <td><?php echo e($log->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Aucune activité récente</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/dashboard/chef_service.blade.php ENDPATH**/ ?>