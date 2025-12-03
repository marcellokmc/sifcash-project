

<?php $__env->startSection('title', 'Mon Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mon Profil Adhérent</h5>
                    <a href="<?php echo e(route('adherent.profile.edit')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <?php if($adherent->isActif()): ?>
                    <a href="<?php echo e(route('adherent.contrat.download')); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i> Télécharger mon contrat
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Informations Personnelles</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Numéro Membre:</th>
                                    <td><?php echo e($adherent->membre_id); ?></td>
                                </tr>
                                <tr>
                                    <th>Nom:</th>
                                    <td><?php echo e($adherent->nom); ?></td>
                                </tr>
                                <tr>
                                    <th>Prénom:</th>
                                    <td><?php echo e($adherent->prenom); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de Naissance:</th>
                                    <td><?php echo e($adherent->date_naissance->format('d/m/Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Lieu de Naissance:</th>
                                    <td><?php echo e($adherent->lieu_naissance); ?></td>
                                </tr>
                                <tr>
                                    <th>Situation Familiale:</th>
                                    <td><?php echo e(ucfirst($adherent->situation_famille)); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary">Coordonnées</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Téléphone:</th>
                                    <td><?php echo e($adherent->telephone); ?></td>
                                </tr>
                                <tr>
                                    <th>Téléphone Secondaire:</th>
                                    <td><?php echo e($adherent->telephone_secondaire ?? 'Non renseigné'); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo e($adherent->email); ?></td>
                                </tr>
                                <tr>
                                    <th>Adresse:</th>
                                    <td><?php echo e($adherent->adresse); ?></td>
                                </tr>
                                <tr>
                                    <th>Résidence:</th>
                                    <td><?php echo e($adherent->residence); ?></td>
                                </tr>
                                <?php if($adherent->secteur_numero): ?>
                                <tr>
                                    <th>Secteur N°:</th>
                                    <td><span class="badge bg-secondary"><?php echo e($adherent->secteur_numero); ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($adherent->profession_exercee): ?>
                                <tr>
                                    <th>Profession exercée:</th>
                                    <td><span class="badge bg-info"><?php echo e($adherent->profession_exercee); ?></span></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Profession:</th>
                                    <td><?php echo e($adherent->profession); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact d'Urgence Principal</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom Complet:</th>
                                    <td><?php echo e($adherent->contact_urgence_nom ?? 'Non renseigné'); ?></td>
                                </tr>
                                <tr>
                                    <th>Lien:</th>
                                    <td>
                                        <?php if($adherent->contact_urgence_lien): ?>
                                            <span class="badge bg-info"><?php echo e(ucfirst($adherent->contact_urgence_lien)); ?></span>
                                        <?php else: ?>
                                            Non renseigné
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td><?php echo e($adherent->contact_urgence_telephone ?? 'Non renseigné'); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary">Contact d'Urgence Secondaire</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom Complet:</th>
                                    <td><?php echo e($adherent->contact_urgence_2_nom ?? 'Non renseigné'); ?></td>
                                </tr>
                                <tr>
                                    <th>Lien:</th>
                                    <td>
                                        <?php if($adherent->contact_urgence_2_lien): ?>
                                            <span class="badge bg-info"><?php echo e(ucfirst($adherent->contact_urgence_2_lien)); ?></span>
                                        <?php else: ?>
                                            Non renseigné
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td><?php echo e($adherent->contact_urgence_2_telephone ?? 'Non renseigné'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Statut du Compte</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Statut:</th>
                                    <td>
                                        <?php if($adherent->isActif()): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php elseif($adherent->isEnAttente()): ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date d'Activation:</th>
                                    <td><?php echo e($adherent->date_activation ? $adherent->date_activation->format('d/m/Y H:i') : 'Non activé'); ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary">Commercial Référent</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Commercial:</th>
                                    <td>
                                        <?php if($adherent->commercial): ?>
                                            <span class="badge bg-primary"><?php echo e($adherent->commercial->nom); ?> <?php echo e($adherent->commercial->prenoms); ?></span>
                                            <small class="text-muted d-block">Code: <?php echo e($adherent->commercial->code_commercial); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Non assigné</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($adherent->commercial): ?>
                                <tr>
                                    <th>Téléphone:</th>
                                    <td><?php echo e($adherent->commercial->telephone ?? 'Non renseigné'); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/adherent/profile/show.blade.php ENDPATH**/ ?>