<?php if($adherent->ayantsDroit->isEmpty()): ?>
    <div class="text-center py-4">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <p class="text-muted">Aucun ayant droit enregistré</p>
    </div>
<?php else: ?>
    <div class="row">
        <?php $__currentLoopData = $adherent->ayantsDroit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ayant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                        <?php if($ayant->validateur): ?>
                        <tr>
                            <th>Validé par:</th>
                            <td><?php echo e($ayant->validateur->name); ?> le <?php echo e($ayant->updated_at->format('d/m/Y')); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="card-footer">
                    <?php if($ayant->isEnAttente()): ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/adherents/partials/ayants-droit.blade.php ENDPATH**/ ?>