<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Informations Personnelles</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Numéro Membre:</th>
                <td><strong><?php echo e($adherent->membre_id); ?></strong></td>
            </tr>
            <tr>
                <th>Nom Complet:</th>
                <td><?php echo e($adherent->nom_complet); ?></td>
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
            <tr>
                <th>Profession:</th>
                <td><?php echo e($adherent->profession); ?></td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Coordonnées</h6>
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
        </table>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Contact d'Urgence Principal</h6>
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
        <h6 class="text-primary mb-3">Contact d'Urgence Secondaire</h6>
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
        <h6 class="text-primary mb-3">
            <i class="fas fa-building"></i> Affectation
            <?php if(auth()->user()->isAdmin() || auth()->user()->isChefService()): ?>
            <a href="<?php echo e(route('admin.adherents.affectation', $adherent)); ?>" class="btn btn-sm btn-info float-end">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <?php endif; ?>
        </h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Agence:</th>
                <td>
                    <?php if($adherent->agence): ?>
                        <i class="fas fa-building text-primary"></i> 
                        <strong><?php echo e($adherent->agence->nom); ?></strong>
                        <br>
                        <small class="text-muted"><?php echo e($adherent->agence->ville); ?></small>
                    <?php else: ?>
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle"></i> Non affecté
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Agent Gestionnaire:</th>
                <td>
                    <?php if($adherent->agentGestionnaire): ?>
                        <i class="fas fa-user-tie text-success"></i> 
                        <strong><?php echo e($adherent->agentGestionnaire->name); ?></strong>
                        <br>
                        <small class="text-muted">
                            <?php echo e(ucfirst(str_replace('_', ' ', $adherent->agentGestionnaire->role))); ?>

                            <?php if($adherent->agentGestionnaire->agence): ?>
                                - <?php echo e($adherent->agentGestionnaire->agence->nom); ?>

                            <?php endif; ?>
                        </small>
                    <?php else: ?>
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle"></i> Non affecté
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Commercial Référent:</th>
                <td>
                    <?php if($adherent->commercial): ?>
                        <i class="fas fa-user-tie text-info"></i> 
                        <strong><?php echo e($adherent->commercial->nom); ?> <?php echo e($adherent->commercial->prenoms); ?></strong>
                        <br>
                        <small class="text-muted">
                            Code: <?php echo e($adherent->commercial->code_commercial); ?>

                            <?php if($adherent->commercial->telephone): ?>
                            - <?php echo e($adherent->commercial->telephone); ?>

                            <?php endif; ?>
                        </small>
                    <?php else: ?>
                        <span class="badge bg-warning">
                            <i class="fas fa-exclamation-triangle"></i> Non assigné
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Date d'Adhésion</h6>
        <table class="table table-borderless">
            <tr>
                <th width="40%">Date d'Inscription:</th>
                <td><strong><?php echo e($adherent->created_at->format('d/m/Y H:i')); ?></strong></td>
            </tr>
            <?php if($adherent->adhesions->isNotEmpty()): ?>
            <tr>
                <th>Première Adhésion:</th>
                <td><?php echo e($adherent->adhesions->first()->created_at->format('d/m/Y')); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div class="row mt-4">
    
    <div class="col-md-6">
        <h6 class="text-primary mb-3">Statut du Compte</h6>
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
            <tr>
                <th>Date d'Inscription:</th>
                <td><?php echo e($adherent->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <th>Dernière Modification:</th>
                <td><?php echo e($adherent->updated_at->format('d/m/Y H:i')); ?></td>
            </tr>
        </table>
    </div>
</div><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/adherents/partials/profile.blade.php ENDPATH**/ ?>