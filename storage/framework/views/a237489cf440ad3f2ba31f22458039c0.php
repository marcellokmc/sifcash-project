<?php if($adherent->adhesions->isEmpty()): ?>
    <div class="text-center py-4">
        <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
        <p class="text-muted">Aucune adhésion enregistrée</p>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Plan</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date Paiement</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $adherent->adhesions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adhesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($adhesion->plan->nom ?? 'N/A'); ?></strong>
                        <?php if($adhesion->plan->description ?? false): ?>
                            <br><small class="text-muted"><?php echo e($adhesion->plan->description); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($adhesion->date_debut ? $adhesion->date_debut->format('d/m/Y') : 'N/A'); ?></td>
                    <td><?php echo e($adhesion->date_fin ? $adhesion->date_fin->format('d/m/Y') : 'N/A'); ?></td>
                    <td><?php echo e($adhesion->montant ? number_format($adhesion->montant, 0, ',', ' ') . ' FCFA' : 'N/A'); ?></td>
                    <td>
                        <?php if($adhesion->statut == 'actif'): ?>
                            <span class="badge bg-success">Actif</span>
                        <?php elseif($adhesion->statut == 'en_attente'): ?>
                            <span class="badge bg-warning">En attente</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Expiré</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($adhesion->date_paiement ? $adhesion->date_paiement->format('d/m/Y') : 'En attente'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php endif; ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/adherents/partials/adhesions.blade.php ENDPATH**/ ?>