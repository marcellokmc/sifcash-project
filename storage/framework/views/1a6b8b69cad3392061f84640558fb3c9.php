

<?php $__env->startSection('title', 'Gestion des Adhésions'); ?>
<?php $__env->startSection('page-title', 'Gestion des Adhésions'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('components.backoffice.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestion des Adhésions</h1>
        <p class="text-muted mb-0">Liste de toutes les adhésions aux plans d'épargne</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <a href="<?php echo e(route('admin.adhesions.index')); ?>" 
               class="btn <?php echo e(!request('statut') ? 'btn-primary' : 'btn-outline-primary'); ?>">
                <i class="fas fa-list me-1"></i>Toutes
            </a>
            <a href="<?php echo e(route('admin.adhesions.index', ['statut' => 'actif'])); ?>" 
               class="btn <?php echo e(request('statut') === 'actif' ? 'btn-success' : 'btn-outline-success'); ?>">
                <i class="fas fa-check-circle me-1"></i>Actives
                <?php if(isset($stats['actives']) && $stats['actives'] > 0): ?>
                    <span class="badge bg-light text-dark ms-1"><?php echo e($stats['actives']); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo e(route('admin.adhesions.index', ['statut' => 'en_attente_activation'])); ?>" 
               class="btn <?php echo e(request('statut') === 'en_attente_activation' ? 'btn-warning' : 'btn-outline-warning'); ?>">
                <i class="fas fa-clock me-1"></i>En attente
                <?php if(isset($stats['en_attente']) && $stats['en_attente'] > 0): ?>
                    <span class="badge bg-light text-dark ms-1"><?php echo e($stats['en_attente']); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo e(route('admin.adhesions.index', ['statut' => 'suspendue'])); ?>" 
               class="btn <?php echo e(request('statut') === 'suspendue' ? 'btn-warning' : 'btn-outline-warning'); ?>">
                <i class="fas fa-pause-circle me-1"></i>Suspendues
            </a>
            <a href="<?php echo e(route('admin.adhesions.index', ['statut' => 'terminee'])); ?>" 
               class="btn <?php echo e(request('statut') === 'terminee' ? 'btn-secondary' : 'btn-outline-secondary'); ?>">
                <i class="fas fa-stop-circle me-1"></i>Terminées
            </a>
        </div>
    </div>
</div>


<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.adhesions.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label"><i class="fas fa-search me-1"></i>Recherche</label>
                <input type="text" 
                       name="search" 
                       class="form-control" 
                       placeholder="ID, nom, téléphone, email, plan..." 
                       value="<?php echo e(request('search')); ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-list me-1"></i>Plan</label>
                <select name="plan_id" class="form-select">
                    <option value="">Tous les plans</option>
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($plan->id); ?>" <?php echo e(request('plan_id') == $plan->id ? 'selected' : ''); ?>>
                            <?php echo e($plan->nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-calendar me-1"></i>Date début</label>
                <input type="date" 
                       name="date_debut" 
                       class="form-control" 
                       value="<?php echo e(request('date_debut')); ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-calendar me-1"></i>Date fin</label>
                <input type="date" 
                       name="date_fin" 
                       class="form-control" 
                       value="<?php echo e(request('date_fin')); ?>">
            </div>
            
            <div class="col-md-2">
                <label class="form-label"><i class="fas fa-sync me-1"></i>Renouvelable</label>
                <select name="renouvelable" class="form-select">
                    <option value="">Tous</option>
                    <option value="1" <?php echo e(request('renouvelable') === '1' ? 'selected' : ''); ?>>Oui</option>
                    <option value="0" <?php echo e(request('renouvelable') === '0' ? 'selected' : ''); ?>>Non</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-money-bill me-1"></i>Montant min (FCFA)</label>
                <input type="number" 
                       name="montant_min" 
                       class="form-control" 
                       placeholder="Min" 
                       value="<?php echo e(request('montant_min')); ?>">
            </div>
            
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-money-bill me-1"></i>Montant max (FCFA)</label>
                <input type="number" 
                       name="montant_max" 
                       class="form-control" 
                       placeholder="Max" 
                       value="<?php echo e(request('montant_max')); ?>">
            </div>
            
            <div class="col-md-6 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Rechercher
                </button>
                <a href="<?php echo e(route('admin.adhesions.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-redo me-1"></i>Réinitialiser
                </a>
                <button type="button" class="btn btn-outline-secondary" id="toggleFilters">
                    <i class="fas fa-filter me-1"></i>Plus de filtres
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques rapides -->
<?php if(isset($stats)): ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total</h6>
                        <h3 class="mb-0"><?php echo e($adhesions->total()); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-handshake fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Actives</h6>
                        <h3 class="mb-0"><?php echo e($stats['actives'] ?? 0); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">En attente</h6>
                        <h3 class="mb-0"><?php echo e($stats['en_attente'] ?? 0); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Closes</h6>
                        <h3 class="mb-0"><?php echo e($stats['closes'] ?? 0); ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-stop-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Liste des adhésions -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-handshake me-2 text-primary"></i>
            Liste des Adhésions
            <?php if(request('statut')): ?>
                - <?php echo e(ucfirst(str_replace('_', ' ', request('statut')))); ?>

            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Adhérent</th>
                        <th>Plan</th>
                        <th>Montant souscrit</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th>Renouvelable</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $adhesions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adhesion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong>#<?php echo e($adhesion->id); ?></strong></td>
                        <td>
                            <div>
                                <strong><?php echo e($adhesion->adherent->nom ?? 'N/A'); ?> <?php echo e($adhesion->adherent->prenom ?? ''); ?></strong>
                                <?php if($adhesion->adherent->telephone): ?>
                                    <br><small class="text-muted"><?php echo e($adhesion->adherent->telephone); ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo e($adhesion->plan->nom ?? 'Plan supprimé'); ?></strong>
                                <?php if($adhesion->plan): ?>
                                    <br><small class="text-muted"><?php echo e($adhesion->plan->taux_interet); ?>% - <?php echo e(ucfirst($adhesion->plan->periodicite)); ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <strong class="text-primary"><?php echo e(number_format($adhesion->montant_souscrit, 0, ',', ' ')); ?> FCFA</strong>
                        </td>
                        <td>
                            <?php echo e($adhesion->date_debut ? \Carbon\Carbon::parse($adhesion->date_debut)->format('d/m/Y') : 'N/A'); ?>

                        </td>
                        <td>
                            <?php echo e($adhesion->date_fin ? \Carbon\Carbon::parse($adhesion->date_fin)->format('d/m/Y') : 'N/A'); ?>

                        </td>
                        <td>
                            <?php
                                $statutClass = match($adhesion->statut) {
                                    'actif' => 'bg-success',
                                    'en_attente_activation' => 'bg-warning text-dark',
                                    'suspendue' => 'bg-warning text-dark', 
                                    'terminee' => 'bg-secondary',
                                    'annulee' => 'bg-danger',
                                    default => 'bg-info'
                                };
                            ?>
                            <span class="badge <?php echo e($statutClass); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $adhesion->statut))); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($adhesion->renouvelable): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times me-1"></i>Non
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.adhesions.show', $adhesion)); ?>" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $adhesion)): ?>
                                    <?php if($adhesion->statut === 'en_attente_activation'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.adhesions.activate', $adhesion)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success"
                                                    title="Activer"
                                                    onclick="return confirm('Activer cette adhésion ?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    <?php elseif($adhesion->statut === 'actif'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.adhesions.suspend', $adhesion)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-warning"
                                                    title="Suspendre"
                                                    onclick="return confirm('Suspendre cette adhésion ?')">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>
                                    <?php elseif($adhesion->statut === 'suspendue'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.adhesions.resume', $adhesion)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-info"
                                                    title="Reprendre"
                                                    onclick="return confirm('Reprendre cette adhésion ?')">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-center">
                                <i class="fas fa-handshake text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Aucune adhésion trouvée</h5>
                                <p class="text-muted">
                                    <?php if(request('statut')): ?>
                                        Aucune adhésion avec le statut "<?php echo e(request('statut')); ?>" n'a été trouvée.
                                    <?php else: ?>
                                        Les adhésions aux plans d'épargne apparaîtront ici.
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if($adhesions->hasPages()): ?>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Affichage de <?php echo e($adhesions->firstItem() ?? 0); ?> à <?php echo e($adhesions->lastItem() ?? 0); ?> 
                sur <?php echo e($adhesions->total()); ?> adhésions
            </div>
            <div>
                <?php echo e($adhesions->links()); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh des statistiques toutes les 30 secondes
    setTimeout(function() {
        location.reload();
    }, 30000);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/adhesions/index.blade.php ENDPATH**/ ?>