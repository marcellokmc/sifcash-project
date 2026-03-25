<?php $__env->startSection('title', 'Gestion des Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Tableau de bord</a></li>
                        <li class="breadcrumb-item active">Paiements</li>
                    </ol>
                </div>
                <h4 class="page-title">Gestion des Paiements</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="header-title">Liste des Paiements</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterPayments('all')">Tous</button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterPayments('soumis')">En attente</button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="filterPayments('validé')">Validés</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterPayments('rejeté')">Rejetés</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search-input" placeholder="Nom, email, référence, téléphone..." value="<?php echo e(request('search')); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie-filter">
                                    <option value="">Toutes</option>
                                    <option value="ouverture" <?php echo e(request('categorie') == 'ouverture' ? 'selected' : ''); ?>>Frais d'ouverture</option>
                                    <option value="cotisation" <?php echo e(request('categorie') == 'cotisation' ? 'selected' : ''); ?>>Cotisation</option>
                                    <option value="credit" <?php echo e(request('categorie') == 'credit' ? 'selected' : ''); ?>>Paiement crédit</option>
                                    <option value="autre" <?php echo e(request('categorie') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Mode</label>
                                <select class="form-select" id="mode-filter">
                                    <option value="">Tous</option>
                                    <option value="espece" <?php echo e(request('mode') == 'espece' ? 'selected' : ''); ?>>Espèces</option>
                                    <option value="orange_money" <?php echo e(request('mode') == 'orange_money' ? 'selected' : ''); ?>>Orange Money</option>
                                    <option value="moov_money" <?php echo e(request('mode') == 'moov_money' ? 'selected' : ''); ?>>Moov Money</option>
                                    <option value="virement" <?php echo e(request('mode') == 'virement' ? 'selected' : ''); ?>>Virement</option>
                                    <option value="autre" <?php echo e(request('mode') == 'autre' ? 'selected' : ''); ?>>Autre</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" id="date-debut" value="<?php echo e(request('date_debut')); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">Date fin</label>
                                <input type="date" class="form-control" id="date-fin" value="<?php echo e(request('date_fin')); ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-primary d-block" onclick="applyFilters()">
                                    <i class="mdi mdi-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100" id="paiements-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Adhérent</th>
                                    <th>Montant</th>
                                    <th>Catégorie</th>
                                    <th>Mode</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold"><?php echo e($paiement->date_soumission->format('d/m/Y')); ?></span>
                                        <br>
                                        <small class="text-muted"><?php echo e($paiement->date_soumission->format('H:i')); ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle me-2">
                                                <span class="avatar-title text-white"><?php echo e(substr($paiement->adherent->prenom, 0, 1)); ?></span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo e($paiement->adherent->nom_complet); ?></h6>
                                                <small class="text-muted d-block"><?php echo e($paiement->adherent->email); ?></small>
                                                <small class="text-primary fw-bold"><i class="fas fa-phone-alt me-1"></i><?php echo e($paiement->adherent->telephone); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e(ucfirst($paiement->categorie)); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?php echo e(ucfirst(str_replace('_', ' ', $paiement->mode_paiement))); ?></span>
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.paiements.show', $paiement)); ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <?php if($paiement->statut === 'soumis'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="validatePayment(<?php echo e($paiement->id); ?>)" title="Valider">
                                                <i class="mdi mdi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="rejectPayment(<?php echo e($paiement->id); ?>)" title="Rejeter">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if($paiement->preuve): ?>
                                            <a href="<?php echo e(route('admin.paiements.quittance', $paiement)); ?>" class="btn btn-sm btn-outline-info" title="Quittance PDF">
                                                <i class="mdi mdi-file-pdf"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-information-outline fs-1"></i>
                                            <p>Aucun paiement trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($paiements->hasPages()): ?>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info">
                                Affichage de <?php echo e($paiements->firstItem()); ?> à <?php echo e($paiements->lastItem()); ?> sur <?php echo e($paiements->total()); ?> résultats
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                <?php echo e($paiements->links()); ?>

                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
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
function filterPayments(status) {
    const url = new URL(window.location.href);
    url.searchParams.set('statut', status);
    window.location.href = url.toString();
}

function applyFilters() {
    const search = document.getElementById('search-input').value;
    const categorie = document.getElementById('categorie-filter').value;
    const mode = document.getElementById('mode-filter').value;
    const dateDebut = document.getElementById('date-debut').value;
    const dateFin = document.getElementById('date-fin').value;

    const url = new URL(window.location.href);
    
    if (search) url.searchParams.set('search', search);
    else url.searchParams.delete('search');
    
    if (categorie) url.searchParams.set('categorie', categorie);
    else url.searchParams.delete('categorie');
    
    if (mode) url.searchParams.set('mode', mode);
    else url.searchParams.delete('mode');
    
    if (dateDebut) url.searchParams.set('date_debut', dateDebut);
    else url.searchParams.delete('date_debut');
    
    if (dateFin) url.searchParams.set('date_fin', dateFin);
    else url.searchParams.delete('date_fin');

    window.location.href = url.toString();
}

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

// Auto-refresh every 30 seconds for pending payments
setInterval(function() {
    if (document.querySelector('.badge.bg-warning')) {
        location.reload();
    }
}, 30000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/paiements/index.blade.php ENDPATH**/ ?>