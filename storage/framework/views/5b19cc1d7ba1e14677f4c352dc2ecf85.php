<?php $__env->startSection('title', 'Gestion des comptes épargne'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-piggy-bank text-primary"></i> Gestion des comptes épargne
        </h1>
        <div>
            <a href="<?php echo e(route('admin.epargnes.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nouveau compte
            </a>
            <a href="<?php echo e(route('admin.epargnes.export')); ?>" class="btn btn-success">
                <i class="fas fa-file-export me-1"></i> Exporter
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.epargnes.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="adherent_id" class="form-label">Adhérent</label>
                    <select name="adherent_id" id="adherent_id" class="form-select">
                        <option value="">Tous les adhérents</option>
                        <?php $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($adherent->id); ?>" <?php echo e(request('adherent_id') == $adherent->id ? 'selected' : ''); ?>>
                                <?php echo e($adherent->nom_complet); ?> (<?php echo e($adherent->membre_id); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type_epargne" class="form-label">Type d'épargne</label>
                    <select name="type_epargne" id="type_epargne" class="form-select">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = \App\Models\Epargne::TYPES_EPARGNE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('type_epargne') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <?php $__currentLoopData = \App\Models\Epargne::STATUTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('statut') == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des épargnes -->
    <div class="card shadow">
        <div class="card-body">
            <?php if($epargnes->isEmpty()): ?>
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i> Aucun compte épargne trouvé.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Compte</th>
                                <th>Adhérent</th>
                                <th>Type</th>
                                <th>Solde</th>
                                <th>Intérêts</th>
                                <th>Date ouverture</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $epargnes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $epargne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($epargne->numero_compte); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.adherents.show', $epargne->adherent)); ?>">
                                            <?php echo e($epargne->adherent->nom_complet); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($epargne->type_epargne_formatted); ?></td>
                                    <td class="text-end"><?php echo e(number_format($epargne->solde_actuel, 0, ',', ' ')); ?> FCFA</td>
                                    <td class="text-end"><?php echo e(number_format($epargne->interet_cumule, 0, ',', ' ')); ?> FCFA</td>
                                    <td><?php echo e($epargne->date_ouverture->format('d/m/Y')); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($epargne->statut === 'actif' ? 'success' : ($epargne->statut === 'inactif' ? 'secondary' : 'warning')); ?>">
                                            <?php echo e($epargne->statut_formatted); ?>

                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('admin.epargnes.show', $epargne)); ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Voir le détail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.epargnes.edit', $epargne)); ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#depotModal"
                                                    data-epargne-id="<?php echo e($epargne->id); ?>"
                                                    title="Effectuer un dépôt">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#retraitModal"
                                                    data-epargne-id="<?php echo e($epargne->id); ?>"
                                                    title="Effectuer un retrait"
                                                    <?php echo e($epargne->solde_actuel <= 0 ? 'disabled' : ''); ?>>
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <?php echo e($epargnes->withQueryString()->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de dépôt -->
<?php echo $__env->make('backoffice.epargnes._depot', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Modal de retrait -->
<?php echo $__env->make('backoffice.epargnes._retrait', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Gestion des modales de dépôt/retrait
        var depotModal = document.getElementById('depotModal');
        var retraitModal = document.getElementById('retraitModal');
        
        if (depotModal) {
            depotModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var epargneId = button.getAttribute('data-epargne-id');
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/depot';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
        
        if (retraitModal) {
            retraitModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var epargneId = button.getAttribute('data-epargne-id');
                var modal = this;
                modal.querySelector('form').action = '/admin/epargnes/' + epargneId + '/retrait';
                modal.querySelector('input[name="date_operation"]').valueAsDate = new Date();
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/epargnes/index.blade.php ENDPATH**/ ?>