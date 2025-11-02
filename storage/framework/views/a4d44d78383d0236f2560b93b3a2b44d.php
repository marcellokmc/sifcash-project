

<?php $__env->startSection('title', 'Demander un Retrait'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('adherent.dashboard')); ?>">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('adherent.retraits.index')); ?>">Mes Retraits</a></li>
                        <li class="breadcrumb-item active">Nouvelle Demande</li>
                    </ol>
                </div>
                <h4 class="page-title">Demander un Retrait</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('adherent.retraits.store')); ?>" method="POST" id="retrait-form">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adhesion_id" class="form-label">Adhésion <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['adhesion_id'];
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
                                                data-solde="<?php echo e($adhesion->solde_disponible); ?>"
                                                data-montant="<?php echo e($adhesion->montant_souscrit); ?>"
                                                <?php echo e(old('adhesion_id') == $adhesion->id ? 'selected' : ''); ?>>
                                            <?php echo e($adhesion->numero_adhesion); ?> - <?php echo e($adhesion->plan->nom); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
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

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type_retrait" class="form-label">Type de Retrait <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['type_retrait'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type_retrait" name="type_retrait" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="a_terme" <?php echo e(old('type_retrait') == 'a_terme' ? 'selected' : ''); ?>>Retrait à terme</option>
                                        <option value="anticipe" <?php echo e(old('type_retrait') == 'anticipe' ? 'selected' : ''); ?>>Retrait anticipé</option>
                                    </select>
                                    <?php $__errorArgs = ['type_retrait'];
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
                                    <label for="montant_demande" class="form-label">Montant Demandé <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['montant_demande'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="montant_demande" name="montant_demande" step="0.01" min="0.01"
                                               value="<?php echo e(old('montant_demande')); ?>" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <div class="form-text">Solde disponible: <span id="solde-disponible" class="fw-semibold">0 FCFA</span></div>
                                    <?php $__errorArgs = ['montant_demande'];
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
                                    <label for="mode_retrait" class="form-label">Mode de Retrait <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['mode_retrait'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="mode_retrait" name="mode_retrait" required>
                                        <option value="">Sélectionner un mode</option>
                                        <option value="mobile_money" <?php echo e(old('mode_retrait') == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                                        <option value="virement" <?php echo e(old('mode_retrait') == 'virement' ? 'selected' : ''); ?>>Virement Bancaire</option>
                                        <option value="cheque" <?php echo e(old('mode_retrait') == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                                        <option value="especes" <?php echo e(old('mode_retrait') == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                                    </select>
                                    <?php $__errorArgs = ['mode_retrait'];
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

                        <!-- Champs conditionnels selon le mode de retrait -->
                        <div id="mobile-money-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_mobile" class="form-label">Numéro Mobile Money</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['informations_retrait.numero_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="numero_mobile" name="informations_retrait[numero_mobile]"
                                           value="<?php echo e(old('informations_retrait.numero_mobile')); ?>" placeholder="Ex: 1234567890">
                                    <?php $__errorArgs = ['informations_retrait.numero_mobile'];
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
                                    <label for="operateur_mobile" class="form-label">Opérateur</label>
                                    <select class="form-select <?php $__errorArgs = ['informations_retrait.operateur_mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="operateur_mobile" name="informations_retrait[operateur_mobile]">
                                        <option value="">Sélectionner un opérateur</option>
                                        <option value="orange" <?php echo e(old('informations_retrait.operateur_mobile') == 'orange' ? 'selected' : ''); ?>>Orange Money</option>
                                        <option value="mtn" <?php echo e(old('informations_retrait.operateur_mobile') == 'mtn' ? 'selected' : ''); ?>>Telecel Money</option>
                                        <option value="moov" <?php echo e(old('informations_retrait.operateur_mobile') == 'moov' ? 'selected' : ''); ?>>Moov Money</option>
                                    </select>
                                    <?php $__errorArgs = ['informations_retrait.operateur_mobile'];
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

                        <div id="virement-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_compte" class="form-label">Numéro de Compte</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['informations_retrait.numero_compte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="numero_compte" name="informations_retrait[numero_compte]"
                                           value="<?php echo e(old('informations_retrait.numero_compte')); ?>">
                                    <?php $__errorArgs = ['informations_retrait.numero_compte'];
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
                                    <label for="nom_banque" class="form-label">Nom de la Banque</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['informations_retrait.nom_banque'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="nom_banque" name="informations_retrait[nom_banque]"
                                           value="<?php echo e(old('informations_retrait.nom_banque')); ?>">
                                    <?php $__errorArgs = ['informations_retrait.nom_banque'];
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

                        <div id="cheque-fields" class="row" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nom_beneficiaire" class="form-label">Nom du Bénéficiaire</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['informations_retrait.nom_beneficiaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="nom_beneficiaire" name="informations_retrait[nom_beneficiaire]"
                                           value="<?php echo e(old('informations_retrait.nom_beneficiaire')); ?>">
                                    <?php $__errorArgs = ['informations_retrait.nom_beneficiaire'];
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
                            <label for="motif" class="form-label">Motif du Retrait <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['motif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      id="motif" name="motif" rows="3"
                                      placeholder="Expliquez la raison de votre demande de retrait..." required><?php echo e(old('motif')); ?></textarea>
                            <?php $__errorArgs = ['motif'];
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

                        <div class="text-end">
                            <a href="<?php echo e(route('adherent.retraits.index')); ?>" class="btn btn-light me-2">
                                <i class="mdi mdi-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-send"></i> Soumettre la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations Importantes</h5>
                    <div class="alert alert-info">
                        <h6><i class="mdi mdi-information"></i> Conditions de Retrait</h6>
                        <ul class="mb-0">
                            <li>Le montant doit être inférieur ou égal au solde disponible</li>
                            <li>Les retraits sont traités dans les 24-48h</li>
                            <li>Des frais peuvent s'appliquer selon le type de retrait</li>
                            <li>Vous recevrez une notification une fois traité</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Résumé de l'Adhésion</h5>
                    <div id="adhesion-summary" class="text-muted">
                        <p>Sélectionnez une adhésion pour voir les détails</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Calcul des Frais</h5>
                    <div id="frais-calculation" class="text-muted">
                        <p>Sélectionnez un montant pour voir les frais</p>
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
    const modeRetraitSelect = document.getElementById('mode_retrait');
    const adhesionSelect = document.getElementById('adhesion_id');
    const montantInput = document.getElementById('montant_demande');
    const adhesionSummary = document.getElementById('adhesion-summary');
    const fraisCalculation = document.getElementById('frais-calculation');

    // Gestion des champs conditionnels
    modeRetraitSelect.addEventListener('change', function() {
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
    adhesionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const plan = selectedOption.dataset.plan;
            const solde = parseFloat(selectedOption.dataset.solde);
            const montant = parseFloat(selectedOption.dataset.montant);

            adhesionSummary.innerHTML = `
                <div class="border rounded p-3">
                    <h6 class="text-primary">${plan}</h6>
                    <p class="mb-1"><strong>Montant souscrit:</strong> ${montant.toLocaleString()} FCFA</p>
                    <p class="mb-1"><strong>Solde disponible:</strong> <span class="text-success">${solde.toLocaleString()} FCFA</span></p>
                    <p class="mb-0"><strong>Numéro:</strong> ${selectedOption.textContent.split(' - ')[0]}</p>
                </div>
            `;

            // Mettre à jour le solde disponible dans le formulaire
            document.getElementById('solde-disponible').textContent = solde.toLocaleString() + ' FCFA';

            // Définir le montant maximum
            montantInput.max = solde;
        } else {
            adhesionSummary.innerHTML = '<p class="text-muted">Sélectionnez une adhésion pour voir les détails</p>';
            document.getElementById('solde-disponible').textContent = '0 FCFA';
        }
    });

    // Calcul des frais
    montantInput.addEventListener('input', function() {
        const montant = parseFloat(this.value);
        const adhesionOption = adhesionSelect.options[adhesionSelect.selectedIndex];

        if (montant > 0 && adhesionOption.value) {
            // Simulation du calcul des frais (à adapter selon vos règles métier)
            const fraisRetrait = montant * 0.01; // 1% de frais
            const montantNet = montant - fraisRetrait;

            fraisCalculation.innerHTML = `
                <div class="border rounded p-3">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Montant demandé</small>
                            <p class="mb-0 fw-semibold">${montant.toLocaleString()} FCFA</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Frais de retrait</small>
                            <p class="mb-0 fw-semibold text-danger">${fraisRetrait.toLocaleString()} FCFA</p>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Montant net reçu</small>
                        <p class="mb-0 fs-5 fw-bold text-success">${montantNet.toLocaleString()} FCFA</p>
                    </div>
                </div>
            `;
        } else {
            fraisCalculation.innerHTML = '<p class="text-muted">Sélectionnez un montant pour voir les frais</p>';
        }
    });

    // Validation du formulaire
    document.getElementById('retrait-form').addEventListener('submit', function(e) {
        const montant = parseFloat(montantInput.value);
        const adhesionOption = adhesionSelect.options[adhesionSelect.selectedIndex];
        const soldeDisponible = parseFloat(adhesionOption.dataset.solde);

        if (montant > soldeDisponible) {
            e.preventDefault();
            alert('Le montant demandé ne peut pas dépasser le solde disponible (' + soldeDisponible.toLocaleString() + ' FCFA)');
            return false;
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/retraits/create.blade.php ENDPATH**/ ?>