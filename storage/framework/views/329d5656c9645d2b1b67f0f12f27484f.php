

<?php $__env->startSection('title', 'Validation des Documents'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Documents en Attente de Validation</h5>
                    <div>
                        <span class="badge bg-warning"><?php echo e($documents->count()); ?> document(s) en attente</span>
                        <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-outline-primary btn-sm ms-2">
                            <i class="fas fa-arrow-left"></i> Retour aux adhérents
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if($documents->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">Aucun document en attente de validation</p>
                            <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-primary">
                                <i class="fas fa-users"></i> Voir tous les adhérents
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Adhérent</th>
                                        <th>Type Document</th>
                                        <th>Version</th>
                                        <th>Date Upload</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong>
                                                <a href="<?php echo e(route('admin.adherents.show', $document->adherent)); ?>">
                                                    <?php echo e($document->adherent->membre_id); ?>

                                                </a>
                                            </strong>
                                            <br>
                                            <small><?php echo e($document->adherent->nom_complet); ?></small>
                                            <br>
                                            <small class="text-muted"><?php echo e($document->adherent->email); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo e($document->typeDocument->nom); ?></strong>
                                            <?php if($document->typeDocument->description): ?>
                                                <br><small class="text-muted"><?php echo e($document->typeDocument->description); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">v<?php echo e($document->version); ?></span>
                                            <?php if($document->version > 1): ?>
                                                <br><small class="text-warning">Nouvelle version</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($document->created_at->format('d/m/Y')); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e($document->created_at->format('H:i')); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.documents.download', ['document' => $document, 'type' => 'recto'])); ?>" 
                                                   class="btn btn-outline-primary" target="_blank" title="Voir recto">
                                                    <i class="fas fa-eye"></i> Recto
                                                </a>
                                                <?php if($document->fichier_verso): ?>
                                                <a href="<?php echo e(route('admin.documents.download', ['document' => $document, 'type' => 'verso'])); ?>" 
                                                   class="btn btn-outline-secondary" target="_blank" title="Voir verso">
                                                    <i class="fas fa-eye"></i> Verso
                                                </a>
                                                <?php endif; ?>
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
                                            </div>

                                            <!-- Modal de rejet -->
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
                                                                              rows="4" required 
                                                                              placeholder="Expliquez précisément le motif du rejet...
Ex: 
- Photo floue
- Document expiré
- Informations illisibles
- Mauvais format
- ..."></textarea>
                                                                    <div class="form-text">
                                                                        Ce commentaire sera visible par l'adhérent.
                                                                    </div>
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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Affichage de <?php echo e($documents->count()); ?> document(s) en attente de validation
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/validation/documents.blade.php ENDPATH**/ ?>