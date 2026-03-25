

<?php $__env->startSection('title', 'Nouveau Type de Document'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Créer un nouveau type de document</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.types-documents.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du document *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="nom" name="nom" value="<?php echo e(old('nom')); ?>" 
                                   placeholder="Ex: CNIB, Passeport, Permis de conduire..." required>
                            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Description détaillée du document..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="recto_requis" name="recto_requis" value="1" 
                                               <?php echo e(old('recto_requis', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="recto_requis">
                                            Recto requis
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Le recto de ce document est-il obligatoire ?
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="verso_requis" name="verso_requis" value="1" 
                                               <?php echo e(old('verso_requis') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="verso_requis">
                                            Verso requis
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Le verso de ce document est-il obligatoire ?
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="actif" name="actif" value="1" 
                                       <?php echo e(old('actif', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="actif">
                                    Type actif
                                </label>
                            </div>
                            <div class="form-text">
                                Les documents de ce type pourront-ils être uploadés ?
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Conseil :</strong> Pensez à bien configurer les champs requis (recto/verso) 
                                selon la nature du document. Par exemple, une CNIB nécessite généralement recto ET verso.
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer le type de document
                            </button>
                            <a href="<?php echo e(route('admin.types-documents.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/types-documents/create.blade.php ENDPATH**/ ?>