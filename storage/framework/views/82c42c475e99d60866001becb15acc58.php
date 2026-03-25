

<?php $__env->startSection('title', 'Détail du Paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Mobile-First -->
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">📄 Détail Paiement</h4>
                    <small class="text-muted">
                        <a href="<?php echo e(route('adherent.dashboard')); ?>" class="text-decoration-none">Tableau de bord</a> / 
                        <a href="<?php echo e(route('adherent.paiements.index')); ?>" class="text-decoration-none">Paiements</a> / 
                        Détail
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Mobile -->
    <div class="row mb-mobile-3 visible-mobile">
        <div class="col-12">
            <div class="btn-group-mobile">
                <a href="<?php echo e(route('adherent.paiements.index')); ?>" class="btn btn-outline-secondary btn-mobile">
                    <i class="fas fa-arrow-left me-1"></i>Retour Liste
                </a>
                <?php if($paiement->statut === 'brouillon'): ?>
                <a href="<?php echo e(route('adherent.paiements.edit', $paiement)); ?>" class="btn btn-warning btn-mobile">
                    <i class="fas fa-edit me-1"></i>Modifier
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                        <h5 class="mb-2 mb-md-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>Informations
                        </h5>
                        <div>
                            <?php switch($paiement->statut):
                                case ('validé'): ?>
                                    <span class="badge bg-success">Validé</span>
                                    <?php break; ?>
                                <?php case ('soumis'): ?>
                                    <span class="badge bg-warning">En attente</span>
                                    <?php break; ?>
                                <?php case ('rejeté'): ?>
                                    <span class="badge bg-danger">Rejeté</span>
                                    <?php break; ?>
                                <?php case ('brouillon'): ?>
                                    <span class="badge bg-secondary">Brouillon</span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="badge bg-light text-dark"><?php echo e(ucfirst($paiement->statut)); ?></span>
                            <?php endswitch; ?>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3">
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Montant</small>
                                <p class="text-success fw-bold fs-5 mb-0"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> <small>FCFA</small></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Catégorie</small>
                                <span class="badge bg-info"><?php echo e(ucfirst($paiement->categorie)); ?></span>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Mode</small>
                                <p class="mb-0 fw-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $paiement->mode_paiement))); ?></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">Soumis le</small>
                                <p class="mb-0 fw-semibold"><?php echo e($paiement->date_soumission->format('d/m/Y à H:i')); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if($paiement->reference_paiement): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Référence de Paiement</label>
                                <p class="font-monospace"><?php echo e($paiement->reference_paiement); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($paiement->numero_compte_beneficiaire): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Numéro de Compte</label>
                                <p class="font-monospace"><?php echo e($paiement->numero_compte_beneficiaire); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Banque Émettrice</label>
                                <p><?php echo e($paiement->banque_emetteur); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($paiement->reference_cheque): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Référence du Chèque</label>
                                <p class="font-monospace"><?php echo e($paiement->reference_cheque); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($paiement->statut === 'rejeté' && $paiement->motif_rejet): ?>
                    <div class="alert alert-danger mt-3">
                        <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-circle me-1"></i> Motif du Rejet</h6>
                        <p class="mb-0 small"><?php echo e($paiement->motif_rejet); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if($paiement->statut === 'validé' && $paiement->date_validation): ?>
                    <div class="alert alert-success mt-3">
                        <h6 class="fw-bold mb-2"><i class="fas fa-check-circle me-1"></i> Paiement Validé</h6>
                        <div class="row g-2 small">
                            <div class="col-12 col-md-6">
                                <strong>Date:</strong> <?php echo e($paiement->date_validation->format('d/m/Y à H:i')); ?>

                            </div>
                            <?php if($paiement->validatedByAgent): ?>
                            <div class="col-12 col-md-6">
                                <strong>Par:</strong> <?php echo e($paiement->validatedByAgent->nom); ?> <?php echo e($paiement->validatedByAgent->prenom); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($paiement->preuve): ?>
            <div class="card sif-card-mobile mt-mobile-3">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-file-image text-info me-2"></i>Preuve de Paiement
                    </h6>
                    <div class="text-center">
                        <?php if(pathinfo($paiement->preuve, PATHINFO_EXTENSION) === 'pdf'): ?>
                            <div class="border rounded p-4 bg-light">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <p class="mb-3">Document PDF</p>
                                <a href="<?php echo e(route('adherent.paiements.download', $paiement)); ?>" class="btn btn-primary btn-mobile">
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="border rounded p-3 bg-light">
                                <img src="<?php echo e(route('adherent.paiements.download', $paiement)); ?>"
                                     alt="Preuve de paiement"
                                     class="img-fluid rounded mb-3"
                                     style="max-height: 400px; max-width: 100%;">
                                <div>
                                    <a href="<?php echo e(route('adherent.paiements.download', $paiement)); ?>" class="btn btn-primary btn-mobile" download>
                                        <i class="fas fa-download me-1"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-lg-4">
            <!-- Adhésion -->
            <div class="card sif-card-mobile mb-mobile-3">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-handshake text-primary me-2"></i>Adhésion Associée
                    </h6>
                    <?php if($paiement->adhesion): ?>
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-primary fw-bold mb-2"><?php echo e($paiement->adhesion->plan->nom); ?></h6>
                        <div class="small">
                            <p class="mb-1"><strong>Numéro:</strong> <?php echo e($paiement->adhesion->numero_adhesion); ?></p>
                            <p class="mb-1"><strong>Montant:</strong> <?php echo e(number_format($paiement->adhesion->montant_souscrit, 0, ',', ' ')); ?> FCFA</p>
                            <p class="mb-0"><strong>Statut:</strong>
                                <span class="badge bg-<?php echo e($paiement->adhesion->statut === 'actif' ? 'success' : 'warning'); ?>">
                                    <?php echo e(ucfirst($paiement->adhesion->statut)); ?>

                                </span>
                            </p>
                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0"><small>Aucune adhésion associée</small></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions Desktop -->
            <div class="card sif-card-mobile mb-mobile-3 visible-desktop">
                <div class="card-body p-mobile-3">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-cogs text-secondary me-2"></i>Actions
                    </h6>
                    <div class="btn-group-mobile">
                        <a href="<?php echo e(route('adherent.paiements.index')); ?>" class="btn btn-outline-secondary btn-mobile">
                            <i class="fas fa-arrow-left me-1"></i> Retour Liste
                        </a>

                        <?php if($paiement->statut === 'brouillon'): ?>
                        <a href="<?php echo e(route('adherent.paiements.edit', $paiement)); ?>" class="btn btn-warning btn-mobile">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <?php endif; ?>

                        <?php if($paiement->statut === 'rejeté'): ?>
                        <a href="<?php echo e(route('adherent.paiements.create')); ?>" class="btn btn-primary btn-mobile">
                            <i class="fas fa-plus me-1"></i> Nouveau Paiement
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Info Validation -->
            <?php if($paiement->statut === 'soumis'): ?>
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-3">
                    <div class="alert alert-info mb-0">
                        <h6 class="fw-bold mb-2"><i class="fas fa-clock me-1"></i> En attente</h6>
                        <p class="mb-0 small">Votre paiement est en cours de validation par nos équipes. Vous recevrez une notification une fois traité.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/paiements/show.blade.php ENDPATH**/ ?>