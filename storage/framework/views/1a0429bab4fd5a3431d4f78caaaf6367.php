<?php if($adherent->documents->isEmpty()): ?>
    <div class="text-center py-4">
        <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
        <p class="text-muted">Aucun document uploadé</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Type de Document</th>
                    <th>Version</th>
                    <th>Statut</th>
                    <th>Date Upload</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $adherent->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($document->typeDocument->nom); ?></strong>
                        <?php if($document->typeDocument->description): ?>
                            <br><small class="text-muted"><?php echo e($document->typeDocument->description); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>v<?php echo e($document->version); ?></td>
                    <td>
                        <?php if($document->isValide()): ?>
                            <span class="badge bg-success">Validé</span>
                        <?php elseif($document->isRejete()): ?>
                            <span class="badge bg-danger">Rejeté</span>
                        <?php else: ?>
                            <span class="badge bg-warning">En attente</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($document->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="<?php echo e(route('admin.documents.download', ['document' => $document, 'type' => 'recto'])); ?>" 
                               class="btn btn-outline-primary" title="Voir recto" target="_blank">
                                <i class="fas fa-eye"></i> Recto
                            </a>
                            <?php if($document->fichier_verso): ?>
                            <a href="<?php echo e(route('admin.documents.download', ['document' => $document, 'type' => 'verso'])); ?>" 
                               class="btn btn-outline-secondary" title="Voir verso" target="_blank">
                                <i class="fas fa-eye"></i> Verso
                            </a>
                            <?php endif; ?>
                            
                            <?php if($document->isSoumis()): ?>
                            <form action="<?php echo e(route('admin.documents.validate', $document)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-success" title="Valider">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" 
                                    data-bs-target="#rejectDocumentModal<?php echo e($document->id); ?>" title="Rejeter">
                                <i class="fas fa-times"></i>
                            </button>
                            <?php endif; ?>
                        </div>

                        <!-- Modal de rejet document -->
                        <div class="modal fade" id="rejectDocumentModal<?php echo e($document->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Rejet du document</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="<?php echo e(route('admin.documents.reject', $document)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="commentaire" class="form-label">Motif du rejet *</label>
                                                <textarea class="form-control" id="commentaire" name="commentaire" 
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
                    </td>
                </tr>
                <?php if($document->isRejete() && $document->commentaire): ?>
                <tr>
                    <td colspan="5" class="bg-light">
                        <small class="text-danger">
                            <strong>Motif du rejet :</strong> <?php echo e($document->commentaire); ?>

                        </small>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/adherents/partials/documents.blade.php ENDPATH**/ ?>