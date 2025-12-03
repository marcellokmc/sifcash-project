

<?php $__env->startSection('title', 'Inscription - Étape 2'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 2/3</h4>
                    <p class="text-muted mb-0">Gestion des ayants droit</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 66%;" 
                             aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-success"><i class="fas fa-check"></i> Profil</small>
                        <small class="text-primary">Ayants Droit</small>
                        <small class="text-muted">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('adherent.inscription.ayants-droit')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Vous pouvez ajouter jusqu'à 2 ayants droit (bénéficiaires vie et/ou décès).
                            Ces informations devront être validées par un agent.
                        </div>

                        <div id="ayants-droit-container">
                            <?php
                                $oldAyants = old('ayants_droit', []);
                                $ayantCount = count($oldAyants) > 0 ? count($oldAyants) : 1;
                            ?>

                            <?php for($i = 0; $i < $ayantCount; $i++): ?>
                            <div class="card mb-4 ayant-droit-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Ayant Droit #<?php echo e($i + 1); ?></h6>
                                    <?php if($i > 0): ?>
                                    <button type="button" class="btn btn-sm btn-danger remove-ayant" data-index="<?php echo e($i); ?>">
                                        <i class="fas fa-times"></i> Supprimer
                                    </button>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom *</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['ayants_droit.'.$i.'.nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       name="ayants_droit[<?php echo e($i); ?>][nom]" 
                                                       value="<?php echo e($oldAyants[$i]['nom'] ?? ''); ?>" required>
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.nom'];
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prénom *</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['ayants_droit.'.$i.'.prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       name="ayants_droit[<?php echo e($i); ?>][prenom]" 
                                                       value="<?php echo e($oldAyants[$i]['prenom'] ?? ''); ?>" required>
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.prenom'];
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
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date de Naissance</label>
                                                <input type="date" class="form-control <?php $__errorArgs = ['ayants_droit.'.$i.'.date_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       name="ayants_droit[<?php echo e($i); ?>][date_naissance]" 
                                                       value="<?php echo e($oldAyants[$i]['date_naissance'] ?? ''); ?>">
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.date_naissance'];
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Lien de Parenté *</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['ayants_droit.'.$i.'.lien_parente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       name="ayants_droit[<?php echo e($i); ?>][lien_parente]" 
                                                       value="<?php echo e($oldAyants[$i]['lien_parente'] ?? ''); ?>" 
                                                       placeholder="Ex: Conjoint, Enfant, Parent..." required>
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.lien_parente'];
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
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="text" class="form-control <?php $__errorArgs = ['ayants_droit.'.$i.'.contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       name="ayants_droit[<?php echo e($i); ?>][contact]" 
                                                       value="<?php echo e($oldAyants[$i]['contact'] ?? ''); ?>">
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.contact'];
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type de Bénéficiaire *</label>
                                                <select class="form-select <?php $__errorArgs = ['ayants_droit.'.$i.'.type_beneficiaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                        name="ayants_droit[<?php echo e($i); ?>][type_beneficiaire]" required>
                                                    <option value="">Choisir...</option>
                                                    <option value="vie" <?php echo e(($oldAyants[$i]['type_beneficiaire'] ?? '') == 'vie' ? 'selected' : ''); ?>>Bénéficiaire Vie</option>
                                                    <option value="deces" <?php echo e(($oldAyants[$i]['type_beneficiaire'] ?? '') == 'deces' ? 'selected' : ''); ?>>Bénéficiaire Décès</option>
                                                </select>
                                                <?php $__errorArgs = ['ayants_droit.'.$i.'.type_beneficiaire'];
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>

                        <div class="mb-4">
                            <button type="button" id="add-ayant" class="btn btn-outline-primary btn-sm" 
                                    <?php echo e($ayantCount >= 2 ? 'disabled' : ''); ?>>
                                <i class="fas fa-plus"></i> Ajouter un autre ayant droit dans votre espace une fois l'inscription terminée
                            </button>
                            <small class="text-muted ms-2">Maximum 2 ayants droit</small>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('adherent.inscription', ['step' => 1])); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Retour
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    Continuer vers les documents <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let ayantCount = <?php echo e($ayantCount); ?>;
    const maxAyants = 2;
    const container = document.getElementById('ayants-droit-container');
    const addButton = document.getElementById('add-ayant');

    // Ajouter un ayant droit
    addButton.addEventListener('click', function() {
        if (ayantCount >= maxAyants) return;

        const newIndex = ayantCount;
        const newCard = document.createElement('div');
        newCard.className = 'card mb-4 ayant-droit-card';
        newCard.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Ayant Droit #${newIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-ayant" data-index="${newIndex}">
                    <i class="fas fa-times"></i> Supprimer
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][nom]" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][prenom]" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" name="ayants_droit[${newIndex}][date_naissance]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Lien de Parenté *</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][lien_parente]" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="ayants_droit[${newIndex}][contact]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type de Bénéficiaire *</label>
                            <select class="form-select" name="ayants_droit[${newIndex}][type_beneficiaire]" required>
                                <option value="">Choisir...</option>
                                <option value="vie">Bénéficiaire Vie</option>
                                <option value="deces">Bénéficiaire Décès</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(newCard);
        ayantCount++;

        if (ayantCount >= maxAyants) {
            addButton.disabled = true;
        }

        // Ajouter l'événement de suppression
        newCard.querySelector('.remove-ayant').addEventListener('click', function() {
            newCard.remove();
            ayantCount--;
            addButton.disabled = false;
            renumberAyants();
        });
    });

    // Supprimer un ayant droit
    document.querySelectorAll('.remove-ayant').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.ayant-droit-card');
            card.remove();
            ayantCount--;
            addButton.disabled = false;
            renumberAyants();
        });
    });

    // Renuméroter les ayants droit
    function renumberAyants() {
        document.querySelectorAll('.ayant-droit-card').forEach((card, index) => {
            card.querySelector('.card-header h6').textContent = `Ayant Droit #${index + 1}`;
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/adherent/inscription/steps/step2.blade.php ENDPATH**/ ?>