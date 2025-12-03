

<?php $__env->startSection('title', 'Inscription - Étape 3'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 3/3</h4>
                    <p class="text-muted mb-0">Upload des documents</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 100%;" 
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-success"><i class="fas fa-check"></i> Profil</small>
                        <small class="text-success"><i class="fas fa-check"></i> Ayants Droit</small>
                        <small class="text-primary">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                        // Initialize all variables at the top
                        $missingTypes = collect(session('missing_types', []));
                        $missingIds = $missingTypes->pluck('id')->toArray();
                        $typesDocuments = $typesDocuments ?? collect();
                    ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                            <?php if($missingTypes->isNotEmpty()): ?>
                                <ul class="mt-2 mb-0">
                                    <?php $__currentLoopData = $missingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="#doc-<?php echo e($mt['id']); ?>" class="text-danger text-decoration-underline">
                                                <?php echo e($mt['nom']); ?> (aller au formulaire)
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('adherent.inscription.documents')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Veuillez uploader les documents requis pour finaliser votre inscription.
                            Les documents doivent être en couleur, lisibles et en cours de validité.
                            <br>
                            <strong>Important :</strong> Sélectionnez <u>un seul</u> document d'identité (CNI, Passeport, Permis, etc.). Les fichiers recto/verso peuvent être requis selon le type.
                        </div>

                        <div class="row">
                            <?php $__currentLoopData = $typesDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeDoc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $name = mb_strtolower($typeDoc->nom ?? '');
                                    $isIdentity = $name && (str_contains($name, 'identit') || str_contains($name, 'cni') || str_contains($name, 'passeport') || str_contains($name, 'permis'));
                                    $isMissing = isset($typeDoc->id) && in_array($typeDoc->id, $missingIds);
                                ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 <?php echo e($isMissing ? 'border-danger' : ''); ?>" id="doc-<?php echo e($typeDoc->id); ?>"
                                     data-identity="<?php echo e($isIdentity ? '1' : '0'); ?>"
                                     data-doc-id="<?php echo e($typeDoc->id); ?>"
                                     data-recto-req="<?php echo e($typeDoc->recto_requis ? '1' : '0'); ?>"
                                     data-verso-req="<?php echo e($typeDoc->verso_requis ? '1' : '0'); ?>">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?php echo e($typeDoc->nom); ?>

                                            <?php if($isMissing): ?>
                                                <span class="badge bg-danger ms-2">Manquant</span>
                                            <?php endif; ?>
                                        </h6>
                                        <?php if($isIdentity): ?>
                                            <div class="form-check m-0">
                                                <input class="form-check-input identity-radio" type="radio" name="identity_choice" value="<?php echo e($typeDoc->id); ?>" <?php if(old('identity_choice') == $typeDoc->id): echo 'checked'; endif; ?> aria-label="Choisir ce document d'identité">
                                            </div>
                                        <?php endif; ?>
                                        <?php if($typeDoc->description): ?>
                                            <small class="text-muted"><?php echo e($typeDoc->description); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fichier Recto *
                                                <?php if($typeDoc->recto_requis): ?>
                                                    <span class="text-danger">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <input type="file" class="form-control identity-file <?php $__errorArgs = ['documents.'.$typeDoc->id.'.recto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   name="documents[<?php echo e($typeDoc->id); ?>][recto]" 
                                                   accept=".jpg,.jpeg,.png,.pdf" <?php echo e((!$isIdentity && $typeDoc->recto_requis) ? 'required' : ''); ?>>
                                            <?php $__errorArgs = ['documents.'.$typeDoc->id.'.recto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text">
                                                Formats: JPG, JPEG, PNG, PDF (Max: 5MB)
                                            </div>
                                        </div>

                                        <?php if($typeDoc->verso_requis): ?>
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Fichier Verso *
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" class="form-control identity-file <?php $__errorArgs = ['documents.'.$typeDoc->id.'.verso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   name="documents[<?php echo e($typeDoc->id); ?>][verso]" 
                                                   accept=".jpg,.jpeg,.png,.pdf" <?php echo e(!$isIdentity ? 'required' : ''); ?>>
                                            <?php $__errorArgs = ['documents.'.$typeDoc->id.'.verso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <div class="form-text">
                                                Formats: JPG, JPEG, PNG, PDF (Max: 5MB)
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="alert alert-warning py-2">
                                            <small>
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Conseils :</strong>
                                                <ul class="mb-0 mt-1">
                                                    <li>Document en couleur et lisible</li>
                                                    <li>Photo nette sans reflet</li>
                                                    <li>Toutes informations visibles</li>
                                                    <li>Document en cours de validité</li>
                                                </ul>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Presque terminé !</strong> Après l'upload de vos documents, votre dossier sera soumis à validation.
                            Vous recevrez une notification une fois votre compte activé.
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('adherent.inscription', ['step' => 2])); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i> Finaliser l'inscription
                                </button>
                            </div>
                        </div>
                    </form>
                    <script>
                        (function(){
                            function applyIdentitySelection() {
                                const selected = document.querySelector('input.identity-radio:checked');
                                const identityCards = document.querySelectorAll('.card[data-identity="1"]');
                                identityCards.forEach(card => {
                                    const recto = card.querySelector('input.identity-file[name^="documents"][name$="[recto]"]');
                                    const verso = card.querySelector('input.identity-file[name^="documents"][name$="[verso]"]');
                                    const rectoReq = card.getAttribute('data-recto-req') === '1';
                                    const versoReq = card.getAttribute('data-verso-req') === '1';
                                    const isSelected = selected && selected.value === card.getAttribute('data-doc-id');
                                    if (recto) {
                                        recto.disabled = !isSelected;
                                        recto.required = isSelected && rectoReq;
                                    }
                                    if (verso) {
                                        verso.disabled = !isSelected;
                                        verso.required = isSelected && versoReq;
                                    }
                                });
                            }

                            function validateFile(file, maxSize = 5 * 1024 * 1024) {
                                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                                const allowedExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];

                                // Check file size
                                if (file.size > maxSize) {
                                    return {
                                        valid: false,
                                        error: `Le fichier "${file.name}" est trop volumineux (max 5MB).`
                                    };
                                }

                                // Check file type
                                if (!allowedTypes.includes(file.type)) {
                                    return {
                                        valid: false,
                                        error: `Le fichier "${file.name}" n'est pas dans un format valide (JPG, PNG, PDF uniquement).`
                                    };
                                }

                                // Check file extension
                                const extension = '.' + file.name.split('.').pop().toLowerCase();
                                if (!allowedExtensions.includes(extension)) {
                                    return {
                                        valid: false,
                                        error: `Le fichier "${file.name}" n'a pas une extension valide (.jpg, .jpeg, .png, .pdf uniquement).`
                                    };
                                }

                                return { valid: true };
                            }

                            function validateForm() {
                                const form = document.querySelector('form');
                                const fileInputs = form.querySelectorAll('input[type="file"]:not(:disabled)');
                                const errors = [];
                                let hasFiles = false;

                                fileInputs.forEach(input => {
                                    if (input.files && input.files.length > 0) {
                                        hasFiles = true;
                                        const file = input.files[0];
                                        const validation = validateFile(file);

                                        if (!validation.valid) {
                                            errors.push(validation.error);

                                            // Highlight the invalid input
                                            input.classList.add('is-invalid');

                                            // Add or update error message
                                            let errorDiv = input.parentNode.querySelector('.invalid-feedback');
                                            if (!errorDiv) {
                                                errorDiv = document.createElement('div');
                                                errorDiv.className = 'invalid-feedback';
                                                input.parentNode.appendChild(errorDiv);
                                            }
                                            errorDiv.textContent = validation.error;
                                        } else {
                                            input.classList.remove('is-invalid');
                                            const errorDiv = input.parentNode.querySelector('.invalid-feedback');
                                            if (errorDiv) {
                                                errorDiv.remove();
                                            }
                                        }
                                    }
                                });

                                if (!hasFiles) {
                                    errors.push('Veuillez sélectionner au moins un fichier à uploader.');
                                }

                                // Check if at least one identity document is selected
                                const identityCards = document.querySelectorAll('.card[data-identity="1"]');
                                const selectedIdentity = document.querySelector('input.identity-radio:checked');
                                let hasIdentityFile = false;

                                if (identityCards.length > 0) {
                                    if (!selectedIdentity) {
                                        errors.push('Veuillez sélectionner un type de document d\'identité.');
                                    } else {
                                        const selectedCard = document.querySelector(`.card[data-doc-id="${selectedIdentity.value}"]`);
                                        const rectoFile = selectedCard.querySelector('input[name$="[recto]"]')?.files?.[0];
                                        const versoFile = selectedCard.querySelector('input[name$="[verso]"]')?.files?.[0];
                                        const versoReq = selectedCard.getAttribute('data-verso-req') === '1';

                                        if (!rectoFile) {
                                            errors.push('Veuillez uploader le fichier recto du document d\'identité sélectionné.');
                                        } else {
                                            hasIdentityFile = true;
                                        }

                                        if (versoReq && !versoFile) {
                                            errors.push('Le fichier verso est requis pour ce type de document d\'identité.');
                                        }
                                    }
                                }

                                if (errors.length > 0) {
                                    // Show error messages
                                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                                    errors.forEach(error => {
                                        errorHtml += `<li>${error}</li>`;
                                    });
                                    errorHtml += '</ul></div>';

                                    // Remove existing error alert
                                    const existingAlert = form.querySelector('.alert-danger');
                                    if (existingAlert) {
                                        existingAlert.remove();
                                    }

                                    // Insert new error alert at the top of the form
                                    form.insertAdjacentHTML('afterbegin', errorHtml);

                                    // Scroll to top of form to see errors
                                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });

                                    return false;
                                }

                                return true;
                            }

                            // Add file validation on file input change
                            document.addEventListener('change', function(e){
                                if (e.target && e.target.classList.contains('identity-file')) {
                                    if (e.target.files && e.target.files.length > 0) {
                                        const file = e.target.files[0];
                                        const validation = validateFile(file);

                                        if (!validation.valid) {
                                            e.target.classList.add('is-invalid');

                                            // Add or update error message
                                            let errorDiv = e.target.parentNode.querySelector('.invalid-feedback');
                                            if (!errorDiv) {
                                                errorDiv = document.createElement('div');
                                                errorDiv.className = 'invalid-feedback';
                                                e.target.parentNode.appendChild(errorDiv);
                                            }
                                            errorDiv.textContent = validation.error;
                                        } else {
                                            e.target.classList.remove('is-invalid');
                                            const errorDiv = e.target.parentNode.querySelector('.invalid-feedback');
                                            if (errorDiv) {
                                                errorDiv.remove();
                                            }
                                        }
                                    }
                                }

                                if (e.target && e.target.classList.contains('identity-radio')) {
                                    applyIdentitySelection();
                                }
                            });

                            // Add form validation on submit
                            document.querySelector('form').addEventListener('submit', function(e) {
                                if (!validateForm()) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            });

                            // Au chargement initial
                            applyIdentitySelection();
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/adherent/inscription/steps/step3.blade.php ENDPATH**/ ?>