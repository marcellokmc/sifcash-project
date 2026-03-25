

<?php $__env->startSection('title', 'Soumettre un Paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Mobile-First -->
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">💸 Nouveau Paiement</h4>
                    <small class="text-muted">
                        <a href="<?php echo e(route('adherent.dashboard')); ?>" class="text-decoration-none">Tableau de bord</a> / 
                        <a href="<?php echo e(route('adherent.paiements.index')); ?>" class="text-decoration-none">Paiements</a> / 
                        Nouveau
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <form action="<?php echo e(route('adherent.paiements.store')); ?>" method="POST" enctype="multipart/form-data" id="paiement-form" class="form-mobile">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="adhesion_id" class="form-label">Adhésion <span class="text-danger">*</span></label>
                                    <?php if($adhesions->count() > 0): ?>
                                        <select class="form-control <?php $__errorArgs = ['adhesion_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="adhesion_id" name="adhesion_id" required>
                                            <option value="">Sélectionner une adhésion</option>
                                            <?php $__currentLoopData = $adhesions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adhesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($adhesion->id); ?>"
                                                    data-plan="<?php echo e($adhesion->plan->nom); ?>"
                                                    data-montant="<?php echo e($adhesion->montant_souscrit); ?>"
                                                    <?php echo e(old('adhesion_id') == $adhesion->id ? 'selected' : ''); ?>>
                                                <?php echo e($adhesion->numero_adhesion); ?> - <?php echo e($adhesion->plan->nom); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            Aucune adhésion active trouvée. 
                                            <a href="<?php echo e(route('adherent.adhesions.index')); ?>" class="alert-link">Créer une adhésion d'abord</a>.
                                        </div>
                                        <select class="form-control" disabled>
                                            <option>Aucune adhésion disponible</option>
                                        </select>
                                    <?php endif; ?>
                                    <?php $__errorArgs = ['adhesion_id'];
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

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="categorie" name="categorie" required>
                                        <option value="">Sélectionner une catégorie</option>
                                        <option value="ouverture" <?php echo e(old('categorie') == 'ouverture' ? 'selected' : ''); ?>>Frais d'ouverture</option>
                                        <option value="cotisation" <?php echo e(old('categorie') == 'cotisation' ? 'selected' : ''); ?>>Cotisation</option>
                                        <option value="credit" <?php echo e(old('categorie') == 'credit' ? 'selected' : ''); ?>>Paiement crédit</option>
                                        <option value="autre" <?php echo e(old('categorie') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                    </select>
                                    <?php $__errorArgs = ['categorie'];
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

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="montant" class="form-label">Montant <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['montant'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="montant" name="montant" step="0.01" min="0.01"
                                               value="<?php echo e(old('montant')); ?>" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <?php $__errorArgs = ['montant'];
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

                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="mode_paiement" class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['mode_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="mode_paiement" name="mode_paiement" required>
                                        <option value="">Sélectionner un mode</option>
                                        <option value="mobile_money" <?php echo e(old('mode_paiement') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                        <option value="virement" <?php echo e(old('mode_paiement') == 'virement' ? 'selected' : ''); ?>>Virement Bancaire</option>
                                        <option value="cheque" <?php echo e(old('mode_paiement') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                                        <option value="especes" <?php echo e(old('mode_paiement') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                                    </select>
                                    <?php $__errorArgs = ['mode_paiement'];
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

                        <!-- Champs conditionnels selon le mode de paiement -->
                        <div id="mobile-money-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="reference_paiement" class="form-label">Référence Mobile Money</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['reference_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="reference_paiement" name="reference_paiement"
                                           value="<?php echo e(old('reference_paiement')); ?>" placeholder="Ex: 1234567890">
                                    <?php $__errorArgs = ['reference_paiement'];
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

                        <div id="virement-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="numero_compte_beneficiaire" class="form-label">Numéro de Compte Bénéficiaire</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['numero_compte_beneficiaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="numero_compte_beneficiaire" name="numero_compte_beneficiaire"
                                           value="<?php echo e(old('numero_compte_beneficiaire')); ?>">
                                    <?php $__errorArgs = ['numero_compte_beneficiaire'];
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
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="banque_emetteur" class="form-label">Banque Émettrice</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['banque_emetteur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="banque_emetteur" name="banque_emetteur"
                                           value="<?php echo e(old('banque_emetteur')); ?>">
                                    <?php $__errorArgs = ['banque_emetteur'];
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

                        <div id="cheque-fields" class="row g-3" style="display: none;">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="reference_cheque" class="form-label">Référence du Chèque</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['reference_cheque'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="reference_cheque" name="reference_cheque"
                                           value="<?php echo e(old('reference_cheque')); ?>">
                                    <?php $__errorArgs = ['reference_cheque'];
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

                        <div class="mb-3">
                            <label for="preuve" class="form-label">Preuve de Paiement <span class="text-danger">*</span></label>
                            <input type="file" class="form-control <?php $__errorArgs = ['preuve'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="preuve" name="preuve" accept=".pdf,.jpg,.jpeg,.png" required>
                            <div class="form-text">Formats acceptés: PDF, JPG, JPEG, PNG (Max: 5MB)</div>
                            <?php $__errorArgs = ['preuve'];
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
                            <label for="notes" class="form-label">Notes (Optionnel)</label>
                            <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="notes" name="notes" rows="3"
                                      placeholder="Informations complémentaires..."><?php echo e(old('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
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

                        <div class="btn-group-mobile mt-4">
                            <button type="submit" class="btn btn-primary btn-mobile" <?php echo e($adhesions->count() == 0 ? 'disabled' : ''); ?>>
                                <i class="fas fa-paper-plane me-1"></i> Soumettre le Paiement
                            </button>
                            <a href="<?php echo e(route('adherent.paiements.index')); ?>" class="btn btn-outline-secondary btn-mobile">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Informations Importantes -->
            <div class="card sif-card-mobile mb-mobile-3">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle text-info me-2"></i>Informations Importantes
                    </h6>
                    <div class="alert alert-info mb-0">
                        <h6 class="fw-bold mb-2">
                            <i class="fas fa-clipboard-list me-1"></i> Instructions
                        </h6>
                        <ul class="mb-0 small">
                            <li class="mb-2">Assurez-vous que le montant correspond exactement à votre paiement</li>
                            <li class="mb-2">Joignez une preuve claire et lisible de votre paiement</li>
                            <li class="mb-2">Votre paiement sera validé dans les 24-48h</li>
                            <li>Vous recevrez une notification une fois validé</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Résumé Adhésion -->
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-receipt text-primary me-2"></i>Résumé de l'Adhésion
                    </h6>
                    <div id="adhesion-summary" class="text-muted text-center">
                        <i class="fas fa-hand-pointer fa-2x mb-2 d-block"></i>
                        <small>Sélectionnez une adhésion pour voir les détails</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modePaiementSelect = document.getElementById('mode_paiement');
    const adhesionSelect = document.getElementById('adhesion_id');
    const adhesionSummary = document.getElementById('adhesion-summary');

    // Gestion des champs conditionnels
    modePaiementSelect.addEventListener('change', function() {
        const selectedMode = this.value;

        // Masquer tous les champs conditionnels
        document.getElementById('mobile-money-fields').style.display = 'none';
        document.getElementById('virement-fields').style.display = 'none';
        document.getElementById('cheque-fields').style.display = 'none';

        // Afficher les champs appropriés
        switch(selectedMode) {
            case 'mobile_money':
                document.getElementById('mobile-money-fields').style.display = 'block';
                break;
            case 'virement':
                document.getElementById('virement-fields').style.display = 'block';
                break;
            case 'cheque':
                document.getElementById('cheque-fields').style.display = 'block';
                break;
        }
    });

    // Gestion du résumé d'adhésion
    if (adhesionSelect) {
        adhesionSelect.addEventListener('change', function() {
            console.log('Sélection d\'adhésion changée:', this.value);
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const plan = selectedOption.dataset.plan;
                const montant = selectedOption.dataset.montant;
                
                console.log('Données adhésion:', { plan, montant });

                if (plan && montant) {
                    adhesionSummary.innerHTML = `
                        <div class="border rounded p-3 bg-light text-start">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-file-contract text-primary me-2 fa-lg"></i>
                                <h6 class="text-primary mb-0 fw-bold">${plan}</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Montant souscrit</small>
                                    <p class="mb-0 fw-bold text-success">${parseFloat(montant).toLocaleString('fr-FR')} FCFA</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1">Numéro</small>
                                    <p class="mb-0 fw-bold">${selectedOption.textContent.split(' - ')[0]}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    adhesionSummary.innerHTML = `
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i> Données d'adhésion incomplètes
                        </div>
                    `;
                }
            } else {
                adhesionSummary.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-hand-pointer fa-2x mb-2 d-block"></i>
                        <small>Sélectionnez une adhésion pour voir les détails</small>
                    </div>
                `;
            }
        });
    } else {
        console.log('Aucun select d\'adhésion trouvé (probablement aucune adhésion disponible)');
    }

    // Validation du formulaire
    document.getElementById('paiement-form').addEventListener('submit', function(e) {
        const preuve = document.getElementById('preuve').files[0];
        if (preuve && preuve.size > 5 * 1024 * 1024) {
            e.preventDefault();
            alert('Le fichier de preuve ne doit pas dépasser 5MB');
            return false;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/paiements/create.blade.php ENDPATH**/ ?>