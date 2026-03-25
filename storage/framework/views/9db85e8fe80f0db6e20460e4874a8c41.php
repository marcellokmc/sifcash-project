

<?php $__env->startSection('title', 'Gestion des Agences'); ?>
<?php $__env->startSection('page-title', 'Liste des Agences'); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Agences</h6>
        <a href="<?php echo e(route('admin.agences.create')); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nouvelle Agence
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Province</th>
                        <th>Département</th>
                        <th>Contact</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $agences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($agence->id); ?></td>
                        <td><strong><?php echo e($agence->code); ?></strong></td>
                        <td><?php echo e($agence->nom); ?></td>
                        <td><?php echo e($agence->province); ?></td>
                        <td><?php echo e($agence->departement ?? 'N/A'); ?></td>
                        <td><?php echo e($agence->contact); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($agence->active ? 'success' : 'secondary'); ?>">
                                <?php echo e($agence->active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.agences.show', $agence)); ?>" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.agences.edit', $agence)); ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.agences.toggle-status', $agence)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-<?php echo e($agence->active ? 'warning' : 'success'); ?>">
                                        <i class="fas fa-<?php echo e($agence->active ? 'pause' : 'play'); ?>"></i>
                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.agences.destroy', $agence)); ?>" method="POST" 
                                      onsubmit="return confirm('Supprimer cette agence?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/agences/index.blade.php ENDPATH**/ ?>