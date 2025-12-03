

<?php $__env->startSection('title', 'Détail Adhérent - ' . $adherent->membre_id); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail de l'Adhérent : <?php echo e($adherent->membre_id); ?></h5>
                    <div>
                        <?php if($adherent->isEnAttente()): ?>
                        <form action="<?php echo e(route('admin.adherents.activate', $adherent)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Activer le compte
                            </button>
                        </form>
                        <?php elseif($adherent->isActif()): ?>
                        <form action="<?php echo e(route('admin.adherents.deactivate', $adherent)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-pause"></i> Désactiver
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $adherent)): ?>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="openResetPasswordModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', true)"
                                    title="Réinitialiser le mot de passe">
                                <i class="fas fa-key"></i>
                            </button>
                            
                            <?php if($adherent->isSuspended()): ?>
                                <button type="button" class="btn btn-success btn-sm" 
                                        onclick="openActivateModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', '<?php echo e($adherent->suspended_at?->format('d/m/Y H:i')); ?>', '<?php echo e($adherent->suspension_reason); ?>', '<?php echo e($adherent->suspendedByUser?->name); ?>', true)"
                                        title="Réactiver le compte">
                                    <i class="fas fa-check-circle"></i> Réactiver
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="openSuspendModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', true)"
                                        title="Suspendre le compte">
                                    <i class="fas fa-ban"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(auth()->user()->isAdmin() || auth()->user()->isChefService()): ?>
                        <a href="<?php echo e(route('admin.adherents.affectation', $adherent)); ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-user-tie"></i> Affecter Agence/Agent
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('admin.adherents.edit', $adherent)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        
                        <a href="<?php echo e(route('admin.adherents.contrat.download', $adherent)); ?>" class="btn btn-success btn-sm">
                            <i class="fas fa-file-pdf"></i> Télécharger contrat
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Navigation par onglets -->
                    <ul class="nav nav-tabs" id="adherentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" 
                                    data-bs-target="#profile" type="button" role="tab">
                                Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ayants-droit-tab" data-bs-toggle="tab" 
                                    data-bs-target="#ayants-droit" type="button" role="tab">
                                Ayants Droit (<?php echo e($adherent->ayantsDroit->count()); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" 
                                    data-bs-target="#documents" type="button" role="tab">
                                Documents (<?php echo e($adherent->documents->count()); ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="adhesions-tab" data-bs-toggle="tab" 
                                    data-bs-target="#adhesions" type="button" role="tab">
                                Adhésions (<?php echo e($adherent->adhesions->count()); ?>)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="adherentTabsContent">
                        <!-- Onglet Profil -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <?php echo $__env->make('backoffice.adherents.partials.profile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <!-- Onglet Ayants Droit -->
                        <div class="tab-pane fade" id="ayants-droit" role="tabpanel">
                            <?php echo $__env->make('backoffice.adherents.partials.ayants-droit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <!-- Onglet Documents -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <?php echo $__env->make('backoffice.adherents.partials.documents', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>

                        <!-- Onglet Adhésions -->
                        <div class="tab-pane fade" id="adhesions" role="tabpanel">
                            <?php echo $__env->make('backoffice.adherents.partials.adhesions', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('backoffice.partials.account-management-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/adherents/show.blade.php ENDPATH**/ ?>