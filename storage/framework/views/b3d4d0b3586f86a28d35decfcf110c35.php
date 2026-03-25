

<?php $__env->startSection('title', 'Mes Ayants Droit'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Ayants Droit</h5>
                    <?php ($nonRejectedCount = $ayantsDroit->where('statut_validation', '!=', 'rejeté')->count()); ?>
                    <?php if($nonRejectedCount < 2): ?>
                    <a href="<?php echo e(route('adherent.ayants-droit.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Ajouter un Ayant Droit
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>

                    <?php if($ayantsDroit->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun ayant droit enregistré</p>
                            <a href="<?php echo e(route('adherent.ayants-droit.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter un Ayant Droit
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php $__currentLoopData = $ayantsDroit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ayant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?php echo e($ayant->nom_complet); ?></h6>
                                        <div>
                                            <?php if($ayant->isValide()): ?>
                                                <span class="badge bg-success">Validé</span>
                                            <?php elseif($ayant->isEnAttente()): ?>
                                                <span class="badge bg-warning">En attente</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejeté</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Lien de parenté:</th>
                                                <td><?php echo e($ayant->lien_parente); ?></td>
                                            </tr>
                                            <?php if($ayant->date_naissance): ?>
                                            <tr>
                                                <th>Date de naissance:</th>
                                                <td><?php echo e($ayant->date_naissance->format('d/m/Y')); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($ayant->contact): ?>
                                            <tr>
                                                <th>Contact:</th>
                                                <td><?php echo e($ayant->contact); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th>Type bénéficiaire:</th>
                                                <td>
                                                    <?php if($ayant->type_beneficiaire == 'vie'): ?>
                                                        <span class="badge bg-info">Vie</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Décès</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php if($ayant->isRejete() && !empty($ayant->motif_rejet)): ?>
                                            <div class="alert alert-danger mt-2 mb-0 py-2">
                                                <small>
                                                    <strong>Motif de rejet :</strong>
                                                    <?php echo e($ayant->motif_rejet); ?>

                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <div class="btn-group btn-group-sm">
                                            <?php if($ayant->isEnAttente()): ?>
                                            <a href="<?php echo e(route('adherent.ayants-droit.edit', $ayant)); ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('adherent.ayants-droit.destroy', $ayant)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($ayant->statut_validation === 'rejeté' && $nonRejectedCount < 2): ?>
                                            <a href="<?php echo e(route('adherent.ayants-droit.create')); ?>" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-plus"></i> Ajouter un autre
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="alert alert-info mt-3">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Information :</strong> Vous pouvez avoir maximum 2 ayants droit (hors ayants droit rejetés).
                                Si un ayant droit est rejeté, vous pouvez en proposer un autre.
                                Toute modification doit être validée par un agent.
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/adherent/ayants-droit/index.blade.php ENDPATH**/ ?>