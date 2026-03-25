

<?php $__env->startSection('title', 'Affectations par agent'); ?>
<?php $__env->startSection('page-title', 'Affectations par Agent'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Liste des agents -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-tie me-2"></i>Agents
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('admin.affectations.par-agent', $agent->id)); ?>" 
                           class="list-group-item list-group-item-action <?php echo e($agentSelectionne && $agentSelectionne->id == $agent->id ? 'active' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user me-2"></i>
                                    <strong><?php echo e($agent->name); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $agent->role))); ?>

                                        <?php if($agent->agence): ?>
                                            - <?php echo e($agent->agence->nom); ?>

                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill"><?php echo e($agent->adherents_geres_count); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de l'agent sélectionné -->
    <div class="col-md-8">
        <?php if($agentSelectionne): ?>
            <!-- Info agent -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">
                                <i class="fas fa-user-circle text-success me-2"></i><?php echo e($agentSelectionne->name); ?>

                            </h4>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $agentSelectionne->role))); ?></span>
                                <?php if($agentSelectionne->agence): ?>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-building me-1"></i><?php echo e($agentSelectionne->agence->nom); ?>

                                    </span>
                                <?php endif; ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-envelope me-1"></i><?php echo e($agentSelectionne->email); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2 class="text-success mb-0"><?php echo e($stats['total_adherents']); ?></h2>
                            <small class="text-muted">adhérents gérés</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Principal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['adherents_principaux']); ?></div>
                            <small class="text-muted">Responsable principal</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Secondaire</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['adherents_secondaires']); ?></div>
                            <small class="text-muted">Agent secondaire</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($stats['total_adherents']); ?></div>
                            <small class="text-muted">Tous les adhérents</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adhérents gérés -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-users me-2"></i>Adhérents gérés (<?php echo e($adherents->total()); ?>)
                        </h6>
                        <form method="GET" action="<?php echo e(route('admin.affectations.par-agent', $agentSelectionne->id)); ?>" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Rechercher..." value="<?php echo e(request('search')); ?>" style="width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Membre ID</th>
                                    <th>Nom complet</th>
                                    <th>Agence</th>
                                    <th>Rôle</th>
                                    <th>Autres agents</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><strong><?php echo e($adherent->membre_id); ?></strong></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>">
                                            <?php echo e($adherent->nom_complet); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <?php if($adherent->agence): ?>
                                            <span class="badge bg-info"><?php echo e($adherent->agence->nom); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Non affecté</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $pivotAgent = $adherent->agents->where('id', $agentSelectionne->id)->first();
                                            $isPrincipal = $pivotAgent ? $pivotAgent->pivot->is_principal : false;
                                        ?>
                                        <?php if($isPrincipal): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i>Principal
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Secondaire</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $autresAgents = $adherent->agents->where('id', '!=', $agentSelectionne->id);
                                        ?>
                                        <?php if($autresAgents->count() > 0): ?>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <?php $__currentLoopData = $autresAgents->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-secondary" title="<?php echo e($agent->name); ?>">
                                                        <?php echo e($agent->name); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($autresAgents->count() > 2): ?>
                                                    <span class="badge bg-light text-dark">+<?php echo e($autresAgents->count() - 2); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">Seul</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($adherent->statut_compte == 'actif'): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">En attente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" 
                                               class="btn btn-outline-primary" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.adherents.affectation', $adherent)); ?>" 
                                               class="btn btn-outline-success" title="Gérer affectation">
                                                <i class="fas fa-user-tie"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="retirerAgent(<?php echo e($adherent->id); ?>, <?php echo e($agentSelectionne->id); ?>)"
                                                    title="Retirer cet agent">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <p>Aucun adhérent géré par cet agent</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if($adherents->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo e($adherents->appends(['search' => request('search')])->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-tie fa-4x text-muted mb-4"></i>
                    <h5 class="text-muted">Sélectionnez un agent</h5>
                    <p class="text-muted">Choisissez un agent dans la liste de gauche pour voir ses adhérents</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Formulaire caché pour retirer un agent -->
<form id="formRetirerAgent" method="POST" action="<?php echo e(route('admin.affectations.retirer-agent')); ?>" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="adherent_id" id="adherent_id_retirer">
    <input type="hidden" name="agent_id" id="agent_id_retirer">
</form>

<?php $__env->startPush('scripts'); ?>
<script>
function retirerAgent(adherentId, agentId) {
    if (confirm('Êtes-vous sûr de vouloir retirer cet agent de cet adhérent ?')) {
        document.getElementById('adherent_id_retirer').value = adherentId;
        document.getElementById('agent_id_retirer').value = agentId;
        document.getElementById('formRetirerAgent').submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/affectations/par-agent.blade.php ENDPATH**/ ?>