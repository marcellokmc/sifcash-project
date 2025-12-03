

<?php $__env->startSection('title', 'Validation des Ayants Droit'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ayants Droit en Attente de Validation</h5>
                    <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left"></i> Retour aux adhérents
                    </a>
                </div>
                <div class="card-body">
                    <?php if($ayantsDroit->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Aucun ayant droit en attente de validation</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php $__currentLoopData = $ayantsDroit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ayant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?php echo e($ayant->nom_complet); ?></h6>
                                        <small class="text-muted">Adhérent: <?php echo e($ayant->adherent->membre_id); ?></small>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="40%">Adhérent:</th>
                                                <td><?php echo e($ayant->adherent->nom_complet); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Lien de parenté:</th>
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
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group btn-group-sm">
                                            <form action="<?php echo e(route('admin.ayants-droit.validate', $ayant)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Valider
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#rejectAyantModal<?php echo e($ayant->id); ?>">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </div>

                                        <!-- Modal de rejet -->
                                        <div class="modal fade" id="rejectAyantModal<?php echo e($ayant->id); ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rejet de l'ayant droit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="<?php echo e(route('admin.ayants-droit.reject', $ayant)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="motif_rejet" class="form-label">Motif du rejet *</label>
                                                                <textarea class="form-control" id="motif_rejet" name="motif_rejet" 
                                                                          rows="3" required placeholder="Expliquez le motif du rejet..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/validation/ayants-droit.blade.php ENDPATH**/ ?>