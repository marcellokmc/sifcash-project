

<?php $__env->startSection('title', 'Modifier Adhérent'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .section-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .section-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .section-title {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.adherents.index')); ?>">Adhérents</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>"><?php echo e($adherent->membre_id); ?></a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Modifier l'Adhérent : <?php echo e($adherent->membre_id); ?>

                        </h5>
                        <span class="badge bg-light text-dark"><?php echo e($adherent->nom_complet); ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.adherents.update', $adherent)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-user"></i>
                                    <span>Informations Personnelles</span>
                                </div>
                                
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
                                           id="nom" name="nom" value="<?php echo e(old('nom', $adherent->nom)); ?>" required>
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
                                           id="prenom" name="prenom" value="<?php echo e(old('prenom', $adherent->prenom)); ?>" required>
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
                                           value="<?php echo e(old('date_naissance', $adherent->date_naissance->format('Y-m-d'))); ?>" required>
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
                                           value="<?php echo e(old('lieu_naissance', $adherent->lieu_naissance)); ?>" required>
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
                                        <option value="marié" <?php echo e(old('situation_famille', $adherent->situation_famille) == 'marié' ? 'selected' : ''); ?>>Marié(e)</option>
                                        <option value="celibataire" <?php echo e(old('situation_famille', $adherent->situation_famille) == 'celibataire' ? 'selected' : ''); ?>>Célibataire</option>
                                        <option value="veuf/veuve" <?php echo e(old('situation_famille', $adherent->situation_famille) == 'veuf/veuve' ? 'selected' : ''); ?>>Veuf/Veuve</option>
                                        <option value="divorcé" <?php echo e(old('situation_famille', $adherent->situation_famille) == 'divorcé' ? 'selected' : ''); ?>>Divorcé(e)</option>
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
                                           id="profession" name="profession" value="<?php echo e(old('profession', $adherent->profession)); ?>" required>
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
                            </div>

                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-address-book"></i>
                                    <span>Coordonnées</span>
                                </div>

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
                                           id="telephone" name="telephone" value="<?php echo e(old('telephone', $adherent->telephone)); ?>" required>
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
                                           value="<?php echo e(old('telephone_secondaire', $adherent->telephone_secondaire)); ?>">
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
                                    <label for="email" class="form-label">Email (optionnel)</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="email" name="email" value="<?php echo e(old('email', $adherent->email)); ?>" 
                                           placeholder="email@exemple.com">
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
                                    <div class="form-text">L'email est optionnel</div>
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
                                              id="adresse" name="adresse" rows="3" required><?php echo e(old('adresse', $adherent->adresse)); ?></textarea>
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
                                           id="residence" name="residence" value="<?php echo e(old('residence', $adherent->residence)); ?>" required>
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
                                    <label for="secteur_numero" class="form-label">Secteur N°</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['secteur_numero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="secteur_numero" name="secteur_numero" 
                                           value="<?php echo e(old('secteur_numero', $adherent->secteur_numero)); ?>" 
                                           placeholder="Exemple: Secteur 15, Zone 3, etc.">
                                    <div class="form-text">Indiquez le secteur de résidence (facultatif)</div>
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
                                    <label for="profession_exercee" class="form-label">Profession exercée</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['profession_exercee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="profession_exercee" name="profession_exercee" 
                                           value="<?php echo e(old('profession_exercee', $adherent->profession_exercee)); ?>" 
                                           placeholder="Exemple: Enseignant, Commerçant, Agriculteur, etc.">
                                    <div class="form-text">Spécifiez la profession actuellement exercée (facultatif)</div>
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
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-phone-alt"></i>
                                    <span>Contact d'Urgence Principal</span>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_nom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_nom" name="contact_urgence_nom" 
                                           value="<?php echo e(old('contact_urgence_nom', $adherent->contact_urgence_nom)); ?>" required>
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
                                    <label for="contact_urgence_prenoms" class="form-label">Prénoms</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_prenoms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_prenoms" name="contact_urgence_prenoms" 
                                           value="<?php echo e(old('contact_urgence_prenoms', $adherent->contact_urgence_prenoms)); ?>">
                                    <?php $__errorArgs = ['contact_urgence_prenoms'];
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
                                    <label for="contact_urgence_lien" class="form-label">Lien de parenté *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_lien'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_lien" name="contact_urgence_lien" 
                                           value="<?php echo e(old('contact_urgence_lien', $adherent->contact_urgence_lien_parente)); ?>" 
                                           placeholder="Ex: Père, Mère, Frère, Sœur, Ami, etc." required>
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
                                    <label for="contact_urgence_telephone" class="form-label">Téléphone *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_telephone" name="contact_urgence_telephone" 
                                           value="<?php echo e(old('contact_urgence_telephone', $adherent->contact_urgence_telephone)); ?>" required>
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
                                <div class="section-title">
                                    <i class="fas fa-phone-square-alt"></i>
                                    <span>Contact d'Urgence Secondaire</span>
                                </div>

                                <div class="mb-3">
                                    <label for="contact_urgence_2_nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_2_nom" name="contact_urgence_2_nom" 
                                           value="<?php echo e(old('contact_urgence_2_nom', $adherent->contact_urgence_secondaire_nom)); ?>">
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
                                    <label for="contact_urgence_2_prenoms" class="form-label">Prénoms</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_prenoms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_2_prenoms" name="contact_urgence_2_prenoms" 
                                           value="<?php echo e(old('contact_urgence_2_prenoms', $adherent->contact_urgence_secondaire_prenoms)); ?>">
                                    <?php $__errorArgs = ['contact_urgence_2_prenoms'];
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
                                    <label for="contact_urgence_2_lien" class="form-label">Lien de parenté</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_lien'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_2_lien" name="contact_urgence_2_lien" 
                                           value="<?php echo e(old('contact_urgence_2_lien', $adherent->contact_urgence_secondaire_lien_parente)); ?>" 
                                           placeholder="Ex: Père, Mère, Frère, Sœur, Ami, etc.">
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
                                    <label for="contact_urgence_2_telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['contact_urgence_2_telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="contact_urgence_2_telephone" name="contact_urgence_2_telephone" 
                                           value="<?php echo e(old('contact_urgence_2_telephone', $adherent->contact_urgence_secondaire_telephone)); ?>">
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
                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-user-shield"></i>
                                    <span>Statut du Compte</span>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="statut_compte" class="form-label">Statut *</label>
                                    <select class="form-select <?php $__errorArgs = ['statut_compte'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="statut_compte" name="statut_compte" required>
                                        <option value="actif" <?php echo e(old('statut_compte', $adherent->statut_compte) == 'actif' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="inactif" <?php echo e(old('statut_compte', $adherent->statut_compte) == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                                        <option value="en_attente_de_verification" <?php echo e(old('statut_compte', $adherent->statut_compte) == 'en_attente_de_verification' ? 'selected' : ''); ?>>En attente</option>
                                    </select>
                                    <?php $__errorArgs = ['statut_compte'];
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

                                <?php if($adherent->isActif() && $adherent->date_activation): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Date d'activation</label>
                                        <input type="text" class="form-control" value="<?php echo e($adherent->date_activation->format('d/m/Y H:i')); ?>" readonly>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <div class="section-title">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Commercial Assigné</span>
                                </div>

                                <div class="mb-3">
                                    <label for="commercial_id" class="form-label">Commercial</label>
                                    <select class="form-select <?php $__errorArgs = ['commercial_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="commercial_id" name="commercial_id">
                                        <option value="">Sélectionner un commercial</option>
                                        <?php $__currentLoopData = $commercials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commercial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($commercial->id); ?>" 
                                                <?php echo e(old('commercial_id', $adherent->commercial_id) == $commercial->id ? 'selected' : ''); ?>>
                                                <?php echo e($commercial->nom_complet); ?> (<?php echo e($commercial->code_commercial); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['commercial_id'];
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
                                        <i class="fas fa-info-circle me-1"></i>
                                        Assignez un commercial pour suivre cet adhérent
                                    </div>
                                </div>

                                <?php if($adherent->commercial): ?>
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <?php echo e(strtoupper(substr($adherent->commercial->nom, 0, 1))); ?>

                                            </div>
                                            <div>
                                                <strong>Commercial actuel:</strong><br>
                                                <?php echo e($adherent->commercial->nom_complet); ?><br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i><?php echo e($adherent->commercial->telephone); ?> | 
                                                    <i class="fas fa-id-badge me-1"></i><?php echo e($adherent->commercial->code_commercial); ?>

                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="date_affectation_commercial" class="form-label">Date d'affectation</label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['date_affectation_commercial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="date_affectation_commercial" name="date_affectation_commercial" 
                                           value="<?php echo e(old('date_affectation_commercial', $adherent->date_affectation_commercial ? $adherent->date_affectation_commercial->format('Y-m-d') : '')); ?>">
                                    <?php $__errorArgs = ['date_affectation_commercial'];
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
                                        Date à laquelle ce commercial a été assigné à l'adhérent
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                    </button>
                                    <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" class="btn btn-secondary btn-lg ms-2">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Les champs marqués d'un * sont obligatoires
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/adherents/edit.blade.php ENDPATH**/ ?>