

<?php $__env->startSection('title', 'Uploader un Document'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Uploader un Document</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('adherent.documents.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="type_document_id" class="form-label">Type de Document *</label>
                            <select class="form-select <?php $__errorArgs = ['type_document_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="type_document_id" name="type_document_id" required>
                                <option value="">Choisir un type de document...</option>
                                <?php $__currentLoopData = $typesDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeDoc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($typeDoc->id); ?>" 
                                            <?php echo e(old('type_document_id') == $typeDoc->id ? 'selected' : ''); ?>

                                            data-recto="<?php echo e($typeDoc->recto_requis); ?>"
                                            data-verso="<?php echo e($typeDoc->verso_requis); ?>">
                                        <?php echo e($typeDoc->nom); ?>

                                        <?php if($typeDoc->description): ?>
                                            - <?php echo e($typeDoc->description); ?>

                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['type_document_id'];
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
                            <label for="fichier_recto" class="form-label">Fichier Recto *</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['fichier_recto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="fichier_recto" name="fichier_recto" accept=".jpg,.jpeg,.png,.pdf" required>
                            <div class="form-text">
                                Formats acceptés : JPG, JPEG, PNG, PDF (Max: 5MB)
                            </div>
                            <?php $__errorArgs = ['fichier_recto'];
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

                        <div class="mb-3" id="verso-field" style="display: none;">
                            <label for="fichier_verso" class="form-label">Fichier Verso</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['fichier_verso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="fichier_verso" name="fichier_verso" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">
                                Formats acceptés : JPG, JPEG, PNG, PDF (Max: 5MB)
                            </div>
                            <?php $__errorArgs = ['fichier_verso'];
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

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <strong>Conseils :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Assurez-vous que le document est lisible et en couleur</li>
                                    <li>La photo doit être nette et sans reflet</li>
                                    <li>Toutes les informations doivent être visibles</li>
                                    <li>Le document doit être en cours de validité</li>
                                </ul>
                            </small>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Uploader le Document
                            </button>
                            <a href="<?php echo e(route('adherent.documents.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_document_id');
    const versoField = document.getElementById('verso-field');
    const versoInput = document.getElementById('fichier_verso');

    function toggleVersoField() {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const versoRequis = selectedOption.getAttribute('data-verso') === '1';
        
        if (versoRequis) {
            versoField.style.display = 'block';
            versoInput.setAttribute('required', 'required');
        } else {
            versoField.style.display = 'none';
            versoInput.removeAttribute('required');
        }
    }

    typeSelect.addEventListener('change', toggleVersoField);
    toggleVersoField(); // Initial call
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/documents/create.blade.php ENDPATH**/ ?>