<?php $__env->startSection('title', 'Détail du Paiement'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.paiements.index')); ?>">Paiements</a></li>
                        <li class="breadcrumb-item active">Détail du Paiement</li>
                    </ol>
                </div>
                <h4 class="page-title">Détail du Paiement</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Informations du Paiement</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php switch($paiement->statut):
                                case ('validé'): ?>
                                    <span class="badge bg-success fs-6">Validé</span>
                                    <?php break; ?>
                                <?php case ('soumis'): ?>
                                    <span class="badge bg-warning fs-6">En attente de validation</span>
                                    <?php break; ?>
                                <?php case ('rejeté'): ?>
                                    <span class="badge bg-danger fs-6">Rejeté</span>
                                    <?php break; ?>
                                <?php case ('brouillon'): ?>
                                    <span class="badge bg-secondary fs-6">Brouillon</span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="badge bg-light text-dark fs-6"><?php echo e(ucfirst($paiement->statut)); ?></span>
                            <?php endswitch; ?>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Montant</label>
                                <p class="text-success fs-5"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catégorie</label>
                                <p><span class="badge bg-info"><?php echo e(ucfirst($paiement->categorie)); ?></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mode de Paiement</label>
                                <p><?php echo e(ucfirst(str_replace('_', ' ', $paiement->mode_paiement))); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date de Soumission</label>
                                <p><?php echo e($paiement->date_soumission->format('d/m/Y à H:i')); ?></p>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <h6><i class="mdi mdi-alert-circle"></i> Motif du Rejet</h6>
                                <p class="mb-0"><?php echo e($paiement->motif_rejet); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($paiement->statut === 'validé' && $paiement->date_validation): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Date de Validation</label>
                                <p><?php echo e($paiement->date_validation->format('d/m/Y à H:i')); ?></p>
                            </div>
                        </div>
                        <?php if($paiement->validatedByAgent): ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Validé par</label>
                                <p><?php echo e($paiement->validatedByAgent->nom); ?> <?php echo e($paiement->validatedByAgent->prenom); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($paiement->preuve): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Preuve de Paiement</h5>
                    <div class="text-center">
                        <?php if(pathinfo($paiement->preuve, PATHINFO_EXTENSION) === 'pdf'): ?>
                            <div class="border rounded p-4">
                                <i class="mdi mdi-file-pdf-box fs-1 text-danger"></i>
                                <p class="mt-2">Document PDF</p>
                                <a href="<?php echo e(route('admin.paiements.download', $paiement)); ?>" class="btn btn-primary">
                                    <i class="mdi mdi-download"></i> Télécharger
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="border rounded p-4">
                                <img src="<?php echo e(route('admin.paiements.download', $paiement)); ?>"
                                     alt="Preuve de paiement"
                                     class="img-fluid rounded"
                                     style="max-height: 400px;">
                                <div class="mt-3">
                                    <a href="<?php echo e(route('admin.paiements.download', $paiement)); ?>" class="btn btn-primary">
                                        <i class="mdi mdi-download"></i> Télécharger
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations Adhérent</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg bg-primary rounded-circle me-3">
                            <span class="avatar-title text-white fs-3"><?php echo e(substr($paiement->adherent->prenom, 0, 1)); ?></span>
                        </div>
                        <div>
                            <h6 class="mb-0"><?php echo e($paiement->adherent->nom_complet); ?></h6>
                            <small class="text-muted"><?php echo e($paiement->adherent->email); ?></small>
                        </div>
                    </div>
                    <div class="border rounded p-3">
                        <p class="mb-1"><strong>Membre ID:</strong> <?php echo e($paiement->adherent->membre_id); ?></p>
                        <p class="mb-1"><strong>Téléphone:</strong> <?php echo e($paiement->adherent->telephone); ?></p>
                        <p class="mb-0"><strong>Statut:</strong>
                            <span class="badge bg-<?php echo e($paiement->adherent->statut_compte === 'actif' ? 'success' : 'warning'); ?>">
                                <?php echo e(ucfirst($paiement->adherent->statut_compte)); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Adhésion Associée</h5>
                    <?php if($paiement->adhesion): ?>
                    <div class="border rounded p-3">
                        <h6 class="text-primary"><?php echo e($paiement->adhesion->plan->nom); ?></h6>
                        <p class="mb-1"><strong>Numéro:</strong> <?php echo e($paiement->adhesion->numero_adhesion); ?></p>
                        <p class="mb-1"><strong>Montant souscrit:</strong> <?php echo e(number_format($paiement->adhesion->montant_souscrit, 0, ',', ' ')); ?> FCFA</p>
                        <p class="mb-0"><strong>Statut:</strong>
                            <span class="badge bg-<?php echo e($paiement->adhesion->statut === 'actif' ? 'success' : 'warning'); ?>">
                                <?php echo e(ucfirst($paiement->adhesion->statut)); ?>

                            </span>
                        </p>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">Aucune adhésion associée</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('admin.paiements.index')); ?>" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Retour à la liste
                        </a>

                        <?php if($paiement->statut === 'soumis'): ?>
                        <button type="button" class="btn btn-success" onclick="validatePayment(<?php echo e($paiement->id); ?>)">
                            <i class="mdi mdi-check"></i> Valider
                        </button>
                        <button type="button" class="btn btn-danger" onclick="rejectPayment(<?php echo e($paiement->id); ?>)">
                            <i class="mdi mdi-close"></i> Rejeter
                        </button>
                        <?php endif; ?>

                        <a href="<?php echo e(route('admin.adherents.show', $paiement->adherent)); ?>" class="btn btn-outline-primary">
                            <i class="mdi mdi-account"></i> Voir l'adhérent
                        </a>

                        <?php if($paiement->adhesion): ?>
                        <a href="<?php echo e(route('admin.adhesions.show', $paiement->adhesion)); ?>" class="btn btn-outline-info">
                            <i class="mdi mdi-file-document"></i> Voir l'adhésion
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if($paiement->statut === 'soumis'): ?>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="mdi mdi-clock"></i> En attente de validation</h6>
                        <p class="mb-0">Ce paiement nécessite une validation manuelle. Vérifiez la preuve jointe avant de valider ou rejeter.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de validation -->
<div class="modal fade" id="validateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valider le Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="validate-form" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('POST'); ?>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Êtes-vous sûr de vouloir valider ce paiement ?
                    </div>
                    <div class="mb-3">
                        <label for="validation-notes" class="form-label">Notes (Optionnel)</label>
                        <textarea class="form-control" id="validation-notes" name="notes" rows="3"
                                  placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Valider le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeter le Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reject-form" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('POST'); ?>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle"></i> Êtes-vous sûr de vouloir rejeter ce paiement ?
                    </div>
                    <div class="mb-3">
                        <label for="reject-motif" class="form-label">Motif du rejet <span class="text-danger">*</span></label>
                        <textarea class="form-control <?php $__errorArgs = ['motif_rejet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="reject-motif" name="motif_rejet" rows="3"
                                  placeholder="Expliquez pourquoi ce paiement est rejeté..." required></textarea>
                        <?php $__errorArgs = ['motif_rejet'];
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Rejeter le Paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function validatePayment(paymentId) {
    const form = document.getElementById('validate-form');
    form.action = `/admin/paiements/${paymentId}/validate`;

    const modal = new bootstrap.Modal(document.getElementById('validateModal'));
    modal.show();
}

function rejectPayment(paymentId) {
    const form = document.getElementById('reject-form');
    form.action = `/admin/paiements/${paymentId}/reject`;

    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/paiements/show.blade.php ENDPATH**/ ?>