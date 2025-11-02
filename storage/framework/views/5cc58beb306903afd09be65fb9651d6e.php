

<?php $__env->startSection('title', 'Inscription - Étape 1'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Inscription Adhérent - Étape 1/3</h4>
                    <p class="text-muted mb-0">Informations personnelles</p>
                    
                    <!-- Barre de progression -->
                    <div class="progress mt-3" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: 33%;" 
                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-primary">Profil</small>
                        <small class="text-muted">Ayants Droit</small>
                        <small class="text-muted">Documents</small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('adherent.inscription.profile')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Informations Personnelles</h6>
                                
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="nom" name="nom" value="<?php echo e(old('nom', $adherent->nom ?? '')); ?>" required>
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
                                    <label for="prenom" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['prenom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="prenom" name="prenom" value="<?php echo e(old('prenom', $adherent->prenom ?? '')); ?>" required>
                                    <?php $__errorArgs = ['prenom'];
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
                                    <label for="date_naissance" class="form-label">Date de Naissance *</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['date_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="date_naissance" name="date_naissance" 
                                           value="<?php echo e(old('date_naissance', $adherent->date_naissance ?? '')); ?>" required>
                                    <?php $__errorArgs = ['date_naissance'];
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
                                    <label for="lieu_naissance" class="form-label">Lieu de Naissance *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['lieu_naissance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="lieu_naissance" name="lieu_naissance" 
                                           value="<?php echo e(old('lieu_naissance', $adherent->lieu_naissance ?? '')); ?>" required>
                                    <?php $__errorArgs = ['lieu_naissance'];
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
                                    <label for="situation_famille" class="form-label">Situation Familiale *</label>
                                    <select class="form-select <?php $__errorArgs = ['situation_famille'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="situation_famille" name="situation_famille" required>
                                        <option value="">Choisir...</option>
                                        <option value="marié" <?php echo e(old('situation_famille', $adherent->situation_famille ?? '') == 'marié' ? 'selected' : ''); ?>>Marié(e)</option>
                                        <option value="celibataire" <?php echo e(old('situation_famille', $adherent->situation_famille ?? '') == 'celibataire' ? 'selected' : ''); ?>>Célibataire</option>
                                        <option value="veuf/veuve" <?php echo e(old('situation_famille', $adherent->situation_famille ?? '') == 'veuf/veuve' ? 'selected' : ''); ?>>Veuf/Veuve</option>
                                        <option value="divorcé" <?php echo e(old('situation_famille', $adherent->situation_famille ?? '') == 'divorcé' ? 'selected' : ''); ?>>Divorcé(e)</option>
                                    </select>
                                    <?php $__errorArgs = ['situation_famille'];
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
                                <h6 class="text-primary mb-3">Coordonnées</h6>

                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="telephone" name="telephone" value="<?php echo e(old('telephone', $adherent->telephone ?? '')); ?>" required>
                                    <?php $__errorArgs = ['telephone'];
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
                                    <label for="telephone_secondaire" class="form-label">Téléphone Secondaire</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['telephone_secondaire'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="telephone_secondaire" name="telephone_secondaire" 
                                           value="<?php echo e(old('telephone_secondaire', $adherent->telephone_secondaire ?? '')); ?>">
                                    <?php $__errorArgs = ['telephone_secondaire'];
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
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="email" name="email" value="<?php echo e(old('email', $adherent->email ?? '')); ?>" required>
                                    <?php $__errorArgs = ['email'];
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
                                    <label for="adresse" class="form-label">Adresse *</label>
                                    <textarea class="form-control <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="adresse" name="adresse" rows="3" required><?php echo e(old('adresse', $adherent->adresse ?? '')); ?></textarea>
                                    <?php $__errorArgs = ['adresse'];
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
                                    <label for="residence" class="form-label">Résidence *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['residence'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="residence" name="residence" value="<?php echo e(old('residence', $adherent->residence ?? '')); ?>" required>
                                    <?php $__errorArgs = ['residence'];
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
                                    <label for="secteur_numero" class="form-label">Numéro de Secteur</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['secteur_numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="secteur_numero" name="secteur_numero" value="<?php echo e(old('secteur_numero', $adherent->secteur_numero ?? '')); ?>" min="1">
                                    <?php $__errorArgs = ['secteur_numero'];
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
                                    <label for="profession" class="form-label">Profession *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['profession'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="profession" name="profession" value="<?php echo e(old('profession', $adherent->profession ?? '')); ?>" required>
                                    <?php $__errorArgs = ['profession'];
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
                                    <label for="profession_exercee" class="form-label">Profession Exercée</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['profession_exercee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="profession_exercee" name="profession_exercee" value="<?php echo e(old('profession_exercee', $adherent->profession_exercee ?? '')); ?>">
                                    <?php $__errorArgs = ['profession_exercee'];
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

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Contact d'Urgence (Principal)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="contact_urgence_nom" name="contact_urgence_nom"
                                           value="<?php echo e(old('contact_urgence_nom', $adherent->contact_urgence_nom ?? '')); ?>" 
                                           placeholder="Nom et prénoms du contact" required>
                                    <?php $__errorArgs = ['contact_urgence_nom'];
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
                                    <label for="contact_urgence_lien" class="form-label">Lien avec vous *</label>
                                    <select class="form-select <?php $__errorArgs = ['contact_urgence_lien'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="contact_urgence_lien" name="contact_urgence_lien" required>
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'conjoint' ? 'selected' : ''); ?>>Conjoint(e)</option>
                                        <option value="parent" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'parent' ? 'selected' : ''); ?>>Parent</option>
                                        <option value="enfant" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'enfant' ? 'selected' : ''); ?>>Enfant</option>
                                        <option value="frere" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'frere' ? 'selected' : ''); ?>>Frère</option>
                                        <option value="soeur" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'soeur' ? 'selected' : ''); ?>>Sœur</option>
                                        <option value="ami" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'ami' ? 'selected' : ''); ?>>Ami(e)</option>
                                        <option value="collegue" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'collegue' ? 'selected' : ''); ?>>Collègue</option>
                                        <option value="autre" <?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien ?? '') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                    </select>
                                    <?php $__errorArgs = ['contact_urgence_lien'];
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
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone d'Urgence *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="<?php echo e(old('contact_urgence_telephone', $adherent->contact_urgence_telephone ?? '')); ?>" required>
                                    <?php $__errorArgs = ['contact_urgence_telephone'];
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
                                <h6 class="text-primary mb-3">Contact d'Urgence (Secondaire)</h6>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_nom" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="contact_urgence_2_nom" name="contact_urgence_2_nom"
                                           value="<?php echo e(old('contact_urgence_2_nom', $adherent->contact_urgence_2_nom ?? '')); ?>" 
                                           placeholder="Nom et prénoms du contact">
                                    <?php $__errorArgs = ['contact_urgence_2_nom'];
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
                                    <label for="contact_urgence_2_lien" class="form-label">Lien avec vous</label>
                                    <select class="form-select <?php $__errorArgs = ['contact_urgence_2_lien'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="contact_urgence_2_lien" name="contact_urgence_2_lien">
                                        <option value="">Choisir...</option>
                                        <option value="conjoint" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'conjoint' ? 'selected' : ''); ?>>Conjoint(e)</option>
                                        <option value="parent" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'parent' ? 'selected' : ''); ?>>Parent</option>
                                        <option value="enfant" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'enfant' ? 'selected' : ''); ?>>Enfant</option>
                                        <option value="frere" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'frere' ? 'selected' : ''); ?>>Frère</option>
                                        <option value="soeur" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'soeur' ? 'selected' : ''); ?>>Sœur</option>
                                        <option value="ami" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'ami' ? 'selected' : ''); ?>>Ami(e)</option>
                                        <option value="collegue" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'collegue' ? 'selected' : ''); ?>>Collègue</option>
                                        <option value="autre" <?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_2_lien ?? '') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                    </select>
                                    <?php $__errorArgs = ['contact_urgence_2_lien'];
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
                                    <label for="contact_urgence_2_telephone" class="form-label">Téléphone d'Urgence Secondaire</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                           value="<?php echo e(old('contact_urgence_2_telephone', $adherent->contact_urgence_2_telephone ?? '')); ?>">
                                    <?php $__errorArgs = ['contact_urgence_2_telephone'];
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

                        <div class="row mt-4">
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    Continuer vers les ayants droit <i class="fas fa-arrow-right ms-2"></i>
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

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/inscription/steps/step1.blade.php ENDPATH**/ ?>