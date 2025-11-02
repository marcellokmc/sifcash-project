

<?php $__env->startSection('title', 'Mes Paiements'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Mobile-First -->
    <div class="row mb-mobile-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">💵 Mes Paiements</h4>
                    <small class="text-muted">
                        <a href="<?php echo e(route('adherent.dashboard')); ?>" class="text-decoration-none">Tableau de bord</a> / Paiements
                    </small>
                </div>
                <a href="<?php echo e(route('adherent.paiements.create')); ?>" class="btn btn-primary btn-mobile visible-desktop">
                    <i class="fas fa-plus me-1"></i>Nouveau
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Action Card Mobile -->
    <div class="row mb-mobile-3 visible-mobile">
        <div class="col-12">
            <div class="card sif-card-mobile border-0 shadow-sm">
                <div class="card-body text-center p-mobile-3">
                    <i class="fas fa-plus-circle text-primary" style="font-size: 2rem;"></i>
                    <h6 class="mt-2 mb-1 fw-bold">Soumettre un Paiement</h6>
                    <p class="text-muted small mb-3">Soumettez vos preuves de paiement</p>
                    <a href="<?php echo e(route('adherent.paiements.create')); ?>" class="btn btn-primary btn-mobile">
                        <i class="fas fa-plus me-1"></i> Nouveau Paiement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card sif-card-mobile">
                <div class="card-body p-mobile-2">
                    <!-- Filtres -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>Historique
                        </h5>
                        <div class="btn-group-mobile w-100 w-md-auto" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="filterPayments('all')">Tous</button>
                            <button type="button" class="btn btn-outline-success btn-sm" onclick="filterPayments('validé')">Validés</button>
                            <button type="button" class="btn btn-outline-warning btn-sm" onclick="filterPayments('soumis')">Attente</button>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="filterPayments('rejeté')">Rejetés</button>
                        </div>
                    </div>

                    <div class="table-responsive-mobile">
                        <table class="table table-mobile table-striped" id="paiements-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
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
                                            <a href="<?php echo e(route('adherent.paiements.show', $paiement)); ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if($paiement->statut === 'brouillon'): ?>
                                            <a href="<?php echo e(route('adherent.paiements.edit', $paiement)); ?>" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($paiements->hasPages()): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                                <small class="text-muted">
                                    Affichage de <?php echo e($paiements->firstItem()); ?> à <?php echo e($paiements->lastItem()); ?> sur <?php echo e($paiements->total()); ?> résultats
                                </small>
                                <div>
                                    <?php echo e($paiements->links()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filterPayments(status) {
    // Implementation for filtering payments by status
    console.log('Filtering by status:', status);
    // Add AJAX call to filter payments
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/paiements/index.blade.php ENDPATH**/ ?>