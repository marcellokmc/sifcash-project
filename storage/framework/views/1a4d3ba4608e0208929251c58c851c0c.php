

<?php $__env->startSection('title', 'Gestion des Commerciaux'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Gestion des Commerciaux
                        </h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="exportCommercials()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                            <a href="<?php echo e(route('admin.commercials.create')); ?>" class="btn btn-light">
                                <i class="fas fa-plus me-2"></i>Nouveau Commercial
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0"><?php echo e($commercials->total()); ?></h4>
                                    <small>Total Commerciaux</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0"><?php echo e($commercials->where('actif', 1)->count()); ?></h4>
                                    <small>Commerciaux Actifs</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0"><?php echo e($commercials->sum('adherents_count')); ?></h4>
                                    <small>Total Adhérents</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4 class="mb-0"><?php echo e($commercials->where('actif', 1)->sum('adherents_count') > 0 ? number_format($commercials->where('actif', 1)->sum('adherents_count') / $commercials->where('actif', 1)->count(), 1) : 0); ?></h4>
                                    <small>Moyenne/Commercial Actif</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <form method="GET" action="<?php echo e(route('admin.commercials.index')); ?>" class="mb-4" id="commercialFilterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Rechercher..." value="<?php echo e(request('search')); ?>"
                                           onchange="submitCommercialFilters()" onkeyup="handleCommercialSearchKeyup(event)">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="actif" class="form-select" onchange="submitCommercialFilters()">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?php echo e(request('actif') == '1' ? 'selected' : ''); ?>>Actifs</option>
                                    <option value="0" <?php echo e(request('actif') == '0' ? 'selected' : ''); ?>>Inactifs</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <a href="<?php echo e(route('admin.commercials.index')); ?>" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times me-2"></i>Réinitialiser
                                    </a>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input" type="checkbox" id="autoCommercialFilter" checked>
                                        <label class="form-check-label" for="autoCommercialFilter">
                                            Auto
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Contact</th>
                                    <th>Statut</th>
                                    <th>Adhérents</th>
                                    <th>Performance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $commercials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commercial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($commercial->code_commercial); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <?php echo e(strtoupper(substr($commercial->nom, 0, 1))); ?>

                                                </div>
                                                <div>
                                                    <strong><?php echo e($commercial->nom_complet); ?></strong>
                                                    <?php if($commercial->notes): ?>
                                                        <br><small class="text-muted"><?php echo e(Str::limit($commercial->notes, 30)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div><i class="fas fa-phone me-1"></i><?php echo e($commercial->telephone); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($commercial->actif): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2"><?php echo e($commercial->adherents_count); ?></span>
                                                <?php if($commercial->adherents_count > 0): ?>
                                                    <small class="text-muted">
                                                        <a href="<?php echo e(route('admin.commercials.show', $commercial)); ?>" class="text-decoration-none">
                                                            <i class="fas fa-eye me-1"></i>Voir
                                                        </a>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($commercial->adherents_count > 0): ?>
                                                <div class="progress" style="height: 8px;">
                                                    <?php
                                                        $maxAdherents = $commercials->max('adherents_count');
                                                        $percentage = $maxAdherents > 0 ? ($commercial->adherents_count / $maxAdherents) * 100 : 0;
                                                    ?>
                                                    <div class="progress-bar <?php echo e($percentage >= 70 ? 'bg-success' : ($percentage >= 40 ? 'bg-warning' : 'bg-danger')); ?>" 
                                                         style="width: <?php echo e($percentage); ?>%">
                                                    </div>
                                                </div>
                                                <small class="text-muted"><?php echo e(number_format($percentage, 0)); ?>%</small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo e(route('admin.commercials.show', $commercial)); ?>" 
                                                   class="btn btn-outline-primary" title="Voir les adhérents">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin.commercials.edit', $commercial)); ?>" 
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($commercial->actif): ?>
                                                    <form method="POST" action="<?php echo e(route('admin.commercials.toggle', $commercial)); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-outline-secondary" title="Désactiver">
                                                            <i class="fas fa-pause"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?php echo e(route('admin.commercials.toggle', $commercial)); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-outline-success" title="Activer">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('admin.commercials.destroy', $commercial)); ?>" 
                                                      method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline-danger" 
                                                            title="Supprimer" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commercial ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Aucun commercial trouvé</p>
                                            <a href="<?php echo e(route('admin.commercials.create')); ?>" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer un commercial
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <span class="text-muted">
                                Affichage de <?php echo e($commercials->firstItem()); ?> à <?php echo e($commercials->lastItem()); ?> 
                                sur <?php echo e($commercials->total()); ?> commerciaux
                            </span>
                            <?php if(request()->hasAny(['search', 'actif'])): ?>
                                <br><small class="text-info">
                                    <i class="fas fa-filter me-1"></i>
                                    Filtres appliqués: 
                                    <?php if(request('search')): ?><span class="badge bg-secondary me-1">Recherche: <?php echo e(request('search')); ?></span><?php endif; ?>
                                    <?php if(request('actif') !== null): ?><span class="badge bg-secondary me-1">Statut: <?php echo e(request('actif') == '1' ? 'Actif' : 'Inactif'); ?></span><?php endif; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <?php echo e($commercials->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportCommercials() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = "<?php echo e(route('admin.commercials.index')); ?>?" + params.toString();
}

function submitCommercialFilters() {
    // Vérifier si le filtrage automatique est activé
    const autoFilter = document.getElementById('autoCommercialFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Ajouter un petit délai pour éviter les requêtes trop fréquentes
    clearTimeout(window.commercialFilterTimeout);
    window.commercialFilterTimeout = setTimeout(() => {
        document.getElementById('commercialFilterForm').submit();
    }, 300);
}

function handleCommercialSearchKeyup(event) {
    const autoFilter = document.getElementById('autoCommercialFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Soumettre après 500ms d'inactivité pour la recherche
    clearTimeout(window.commercialSearchTimeout);
    window.commercialSearchTimeout = setTimeout(() => {
        document.getElementById('commercialFilterForm').submit();
    }, 500);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/commercials/index.blade.php ENDPATH**/ ?>