

<?php $__env->startSection('title', 'Créer un Plan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark mb-1">Créer un Nouveau Plan</h2>
            <p class="text-muted mb-0">Configuration d'un plan d'épargne ou de crédit</p>
        </div>
        <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <?php echo $__env->make('components.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <form action="<?php echo e(route('admin.plans.store')); ?>" method="POST" class="needs-validation" novalidate>
        <?php echo csrf_field(); ?>
        
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations générales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Informations Générales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom du Plan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="nom" name="nom" value="<?php echo e(old('nom')); ?>" required>
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
                                    <div class="form-text">Un nom descriptif et unique pour ce plan</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type_plan" class="form-label">Type de Plan <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['type_plan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="type_plan" name="type_plan" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="epargne" <?php echo e(old('type_plan') == 'epargne' ? 'selected' : ''); ?>>
                                            Plan d'Épargne
                                        </option>
                                        <option value="credit" <?php echo e(old('type_plan') == 'credit' ? 'selected' : ''); ?>>
                                            Plan de Crédit
                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['type_plan'];
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
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="description" name="description" rows="3" required><?php echo e(old('description')); ?></textarea>
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
                            <div class="form-text">Décrivez les avantages et caractéristiques de ce plan</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="periodicite" class="form-label">Périodicité <span class="text-danger">*</span></label>
                                    <select class="form-select <?php $__errorArgs = ['periodicite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="periodicite" name="periodicite" required>
                                        <option value="">Sélectionner</option>
                                        <option value="hebdomadaire" <?php echo e(old('periodicite') == 'hebdomadaire' ? 'selected' : ''); ?>>Hebdomadaire</option>
                                        <option value="mensuel" <?php echo e(old('periodicite') == 'mensuel' ? 'selected' : ''); ?>>Mensuel</option>
                                        <option value="trimestriel" <?php echo e(old('periodicite') == 'trimestriel' ? 'selected' : ''); ?>>Trimestriel</option>
                                        <option value="semestriel" <?php echo e(old('periodicite') == 'semestriel' ? 'selected' : ''); ?>>Semestriel</option>
                                        <option value="annuel" <?php echo e(old('periodicite') == 'annuel' ? 'selected' : ''); ?>>Annuel</option>
                                    </select>
                                    <?php $__errorArgs = ['periodicite'];
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="duree_min_jours" class="form-label">Durée minimum (jours)</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['duree_min_jours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="duree_min_jours" name="duree_min_jours" 
                                           value="<?php echo e(old('duree_min_jours', 30)); ?>" min="1">
                                    <?php $__errorArgs = ['duree_min_jours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">Durée minimum d'engagement</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ordre_affichage" class="form-label">Ordre d'affichage</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['ordre_affichage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="ordre_affichage" name="ordre_affichage" 
                                           value="<?php echo e(old('ordre_affichage', 1)); ?>" min="1">
                                    <?php $__errorArgs = ['ordre_affichage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="form-text">Position dans la liste (1 = premier)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration financière -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calculator me-2 text-success"></i>Configuration Financière
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="montant_min" class="form-label">Montant Minimum (FCFA) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['montant_min'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="montant_min" name="montant_min" value="<?php echo e(old('montant_min')); ?>" 
                                               min="1" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <?php $__errorArgs = ['montant_min'];
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
                                    <label for="montant_max" class="form-label">Montant Maximum (FCFA) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['montant_max'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="montant_max" name="montant_max" value="<?php echo e(old('montant_max')); ?>" 
                                               min="1" required>
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <?php $__errorArgs = ['montant_max'];
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="taux_interet" class="form-label">Taux d'Intérêt (%) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['taux_interet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="taux_interet" name="taux_interet" value="<?php echo e(old('taux_interet')); ?>" 
                                               step="0.01" min="0" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <?php $__errorArgs = ['taux_interet'];
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="frais_adhesion" class="form-label">Frais d'Adhésion (FCFA)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['frais_adhesion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="frais_adhesion" name="frais_adhesion" 
                                               value="<?php echo e(old('frais_adhesion', 0)); ?>" min="0">
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <?php $__errorArgs = ['frais_adhesion'];
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
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="frais_retrait" class="form-label">Frais de Retrait (FCFA)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['frais_retrait'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="frais_retrait" name="frais_retrait" 
                                               value="<?php echo e(old('frais_retrait', 0)); ?>" min="0">
                                        <span class="input-group-text">FCFA</span>
                                    </div>
                                    <?php $__errorArgs = ['frais_retrait'];
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

                <!-- Conditions et règles -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-gavel me-2 text-warning"></i>Conditions et Règles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="conditions" class="form-label">Conditions du Plan</label>
                            <textarea class="form-control <?php $__errorArgs = ['conditions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="conditions" name="conditions" rows="4"><?php echo e(old('conditions')); ?></textarea>
                            <?php $__errorArgs = ['conditions'];
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
                                Décrivez les conditions spécifiques : pénalités, restrictions, avantages, etc.
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="penalite_retrait_anticipe" class="form-label">Pénalité Retrait Anticipé (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control <?php $__errorArgs = ['penalite_retrait_anticipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="penalite_retrait_anticipe" name="penalite_retrait_anticipe" 
                                               value="<?php echo e(old('penalite_retrait_anticipe', 0)); ?>" 
                                               step="0.01" min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <?php $__errorArgs = ['penalite_retrait_anticipe'];
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
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="retrait_partiel_autorise" name="retrait_partiel_autorise" 
                                           value="1" <?php echo e(old('retrait_partiel_autorise') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="retrait_partiel_autorise">
                                        Autoriser les retraits partiels
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar droite -->
            <div class="col-lg-4">
                <!-- Aperçu du plan -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-eye me-2 text-info"></i>Aperçu du Plan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="plan-preview" id="planPreview">
                            <div class="text-center text-muted">
                                <i class="fas fa-clipboard-list" style="font-size: 2rem;"></i>
                                <p class="mt-2">L'aperçu s'affichera en saisissant les informations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cogs me-2 text-secondary"></i>Options de Publication
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="actif" name="actif" value="1" <?php echo e(old('actif', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="actif">
                                    Plan actif (visible aux adhérents)
                                </label>
                            </div>
                            <div class="form-text">
                                Seuls les plans actifs sont proposés aux adhérents
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Créer le Plan
                            </button>
                            <a href="<?php echo e(route('admin.plans.index')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu en temps réel
    const inputs = ['nom', 'type_plan', 'description', 'montant_min', 'montant_max', 'taux_interet', 'periodicite'];
    const preview = document.getElementById('planPreview');
    
    function updatePreview() {
        const values = {};
        inputs.forEach(input => {
            const element = document.getElementById(input);
            values[input] = element ? element.value : '';
        });
        
        if (values.nom) {
            const typeIcon = values.type_plan === 'epargne' ? 'fa-piggy-bank text-success' : 'fa-credit-card text-primary';
            
            preview.innerHTML = `
                <div class="text-center">
                    <i class="fas ${typeIcon}" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1">${values.nom}</h6>
                    <small class="text-muted">${values.type_plan ? 'Plan ' + (values.type_plan === 'epargne' ? "d'épargne" : 'de crédit') : ''}</small>
                </div>
                ${values.description ? `<p class="text-muted mt-2 small">${values.description}</p>` : ''}
                ${values.montant_min || values.montant_max ? `
                <div class="row text-center mt-3">
                    ${values.montant_min ? `<div class="col-6"><small class="text-muted d-block">Min</small><strong>${parseInt(values.montant_min).toLocaleString()} FCFA</strong></div>` : ''}
                    ${values.montant_max ? `<div class="col-6"><small class="text-muted d-block">Max</small><strong>${parseInt(values.montant_max).toLocaleString()} FCFA</strong></div>` : ''}
                </div>` : ''}
                ${values.taux_interet ? `<div class="text-center mt-2"><span class="badge bg-success">${values.taux_interet}% d'intérêt</span></div>` : ''}
                ${values.periodicite ? `<div class="text-center mt-1"><span class="badge bg-info">${values.periodicite}</span></div>` : ''}
            `;
        }
    }
    
    inputs.forEach(input => {
        const element = document.getElementById(input);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });
    
    // Validation des montants
    document.getElementById('montant_max').addEventListener('input', function() {
        const min = parseFloat(document.getElementById('montant_min').value) || 0;
        const max = parseFloat(this.value) || 0;
        
        if (max > 0 && min > 0 && max < min) {
            this.setCustomValidity('Le montant maximum doit être supérieur au minimum');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/plans/create.blade.php ENDPATH**/ ?>