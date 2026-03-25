

<?php $__env->startSection('title', 'Mes Documents'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes Documents</h5>
                    <a href="<?php echo e(route('adherent.documents.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Uploader un Document
                    </a>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <?php if($documents->isEmpty()): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun document uploadé</p>
                            <a href="<?php echo e(route('adherent.documents.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Uploader mon premier document
                            </a>
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
                                    <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                <a href="<?php echo e(route('adherent.documents.download', ['document' => $document, 'type' => 'recto'])); ?>" 
                                                   class="btn btn-outline-primary" title="Télécharger recto">
                                                    <i class="fas fa-download"></i> Recto
                                                </a>
                                                <?php if($document->fichier_verso): ?>
                                                <a href="<?php echo e(route('adherent.documents.download', ['document' => $document, 'type' => 'verso'])); ?>" 
                                                   class="btn btn-outline-secondary" title="Télécharger verso">
                                                    <i class="fas fa-download"></i> Verso
                                                </a>
                                                <?php endif; ?>
                                                <?php if($document->isSoumis()): ?>
                                                <form action="<?php echo e(route('adherent.documents.destroy', $document)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
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
                    <?php endif; ?>

                    <?php if($typesDocuments->isNotEmpty()): ?>
                    <div class="mt-4">
                        <h6>Types de documents requis</h6>
                        <div class="row">
                            <?php $__currentLoopData = $typesDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeDoc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4 mb-2">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><?php echo e($typeDoc->nom); ?></span>
                                            <div>
                                                <?php if($typeDoc->obligatoire): ?>
                                                    <span class="badge bg-danger me-2">Obligatoire</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary me-2">Facultatif</span>
                                                <?php endif; ?>
                                                <?php
                                                    $userDoc = $documents->where('type_document_id', $typeDoc->id)->first();
                                                ?>
                                                <?php if($userDoc && $userDoc->isValide()): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                <?php elseif($userDoc && $userDoc->isSoumis()): ?>
                                                    <span class="badge bg-warning"><i class="fas fa-clock"></i></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/documents/index.blade.php ENDPATH**/ ?>