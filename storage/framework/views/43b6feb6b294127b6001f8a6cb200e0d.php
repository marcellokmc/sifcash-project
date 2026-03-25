

<?php $__env->startSection('title', 'Adhérents non affectés'); ?>
<?php $__env->startSection('page-title', 'Adhérents Non Affectés'); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistiques en-tête -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Non Affectés</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($adherents->total()); ?></div>
                        <div class="text-xs text-muted mt-1">Nécessitent une affectation</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Agences Actives</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($agences->count()); ?></div>
                        <div class="text-xs text-muted mt-1">Disponibles pour affectation</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Agents Actifs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($agents->count()); ?></div>
                        <div class="text-xs text-muted mt-1">Prêts à gérer des adhérents</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerte informative -->
<?php if($adherents->total() > 0): ?>
<div class="alert alert-warning d-flex align-items-center mb-4">
    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
    <div class="flex-grow-1">
        <strong>Action requise !</strong> Ces adhérents ne sont affectés à aucun agent et/ou agence.
        <br>
        <small>Il est recommandé d'affecter chaque adhérent à une agence et au moins un agent gestionnaire pour assurer un suivi optimal.</small>
    </div>
    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#guideModal">
        <i class="fas fa-question-circle me-1"></i>Guide
    </button>
</div>
<?php endif; ?>

<!-- Affectation rapide en masse -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-gradient-primary text-white">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-bolt me-2"></i>Affectation Rapide en Masse
        </h6>
    </div>
    <div class="card-body">
        <!-- Barre de recherche -->
        <form method="GET" action="<?php echo e(route('admin.affectations.non-affectes')); ?>" class="mb-4">
            <div class="row g-3">
                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher par nom, prénom ou membre ID..." 
                               value="<?php echo e(request('search')); ?>" autofocus>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                        <a href="<?php echo e(route('admin.affectations.non-affectes')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Formulaire d'affectation en masse -->
        <div class="border-top pt-4">
            <form method="POST" action="<?php echo e(route('admin.affectations.affecter-masse')); ?>" id="formAffectationRapide">
                <?php echo csrf_field(); ?>
                <div class="alert alert-info d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        <strong>Affectation en masse :</strong> Sélectionnez les adhérents ci-dessous, puis choisissez une agence et/ou des agents.
                        <br>
                        <small>Le premier agent sélectionné sera défini comme agent principal.</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">
                            <i class="fas fa-building text-primary me-1"></i>Agence de rattachement
                        </label>
                        <select name="agence_id" id="agence_rapide" class="form-select form-select-lg">
                            <option value="">-- Sélectionner une agence --</option>
                            <?php $__currentLoopData = $agences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agence->id); ?>" data-ville="<?php echo e($agence->ville ?? ''); ?>">
                                    <?php echo e($agence->nom); ?>

                                    <?php if($agence->ville): ?> - <?php echo e($agence->ville); ?><?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="form-text text-muted">
                            <i class="fas fa-lightbulb text-warning"></i> Les agents seront filtrés selon l'agence
                        </small>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user-tie text-success me-1"></i>Agents gestionnaires
                            <span id="selectedAgentsCountRapide" class="badge bg-primary ms-2">0 sélectionné(s)</span>
                        </label>
                        
                        <!-- Recherche d'agent -->
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchAgentRapide" class="form-control" 
                                   placeholder="Rechercher un agent..." autocomplete="off">
                        </div>
                        
                        <!-- Liste avec checkboxes -->
                        <div class="border rounded p-2 bg-light" style="max-height: 200px; overflow-y: auto;" id="agentsListRapide">
                            <?php if($agents->count() > 0): ?>
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check agent-item-rapide p-2 rounded hover-bg-white" 
                                         data-agence="<?php echo e($agent->agence_id ?? ''); ?>"
                                         data-search="<?php echo e(strtolower($agent->name . ' ' . ($agent->agence ? $agent->agence->nom : ''))); ?>">
                                        <input class="form-check-input agent-checkbox-rapide" 
                                               type="checkbox" 
                                               name="agent_ids[]" 
                                               value="<?php echo e($agent->id); ?>" 
                                               id="agent_rapide_<?php echo e($agent->id); ?>">
                                        <label class="form-check-label w-100" for="agent_rapide_<?php echo e($agent->id); ?>" style="cursor: pointer;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?php echo e($agent->name); ?></strong>
                                                    <?php if($agent->agence): ?>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-building me-1"></i><?php echo e($agent->agence->nom); ?>

                                                        </small>
                                                    <?php else: ?>
                                                        <br>
                                                        <small class="text-warning">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>Sans agence
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="badge bg-secondary" title="Adhérents gérés">
                                                    <?php echo e($agent->adherents_geres_count ?? 0); ?>

                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    <small>Aucun agent disponible</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted mt-1">
                            <i class="fas fa-info-circle"></i> Le 1er agent sera principal
                        </small>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-lg w-100" id="btnAffecterRapide" disabled>
                            <i class="fas fa-check-circle me-1"></i>
                            <div>Affecter</div>
                            <small class="d-block">(<span id="countRapide">0</span>)</small>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="adherent_ids" id="adherent_ids_rapide">
                <input type="hidden" name="is_principal" value="1">
            </form>
        </div>
    </div>
</div>

<!-- Liste des adhérents -->
<div class="card shadow">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-danger">
                <i class="fas fa-users me-2"></i>Liste des Adhérents Non Affectés 
                <span class="badge bg-danger"><?php echo e($adherents->total()); ?></span>
            </h6>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check form-switch">
                    <input type="checkbox" id="selectAll" class="form-check-input" role="switch">
                    <label for="selectAll" class="form-check-label fw-bold">Tout sélectionner</label>
                </div>
                <span class="badge bg-primary" id="selectionInfo">0 sélectionné(s)</span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50" class="text-center">
                            <i class="fas fa-check-square"></i>
                        </th>
                        <th>Membre ID</th>
                        <th>Nom Complet</th>
                        <th>Contact</th>
                        <th>Agence</th>
                        <th>Agent(s)</th>
                        <th>Statut</th>
                        <th class="text-center" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="align-middle">
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input adherent-check" 
                                   value="<?php echo e($adherent->id); ?>" style="width: 20px; height: 20px;">
                        </td>
                        <td>
                            <strong class="text-primary"><?php echo e($adherent->membre_id); ?></strong>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" 
                               class="text-decoration-none fw-bold text-dark">
                                <i class="fas fa-user me-1"></i><?php echo e($adherent->nom_complet); ?>

                            </a>
                        </td>
                        <td>
                            <div>
                                <i class="fas fa-phone text-muted me-1"></i>
                                <small><?php echo e($adherent->telephone ?? 'N/A'); ?></small>
                            </div>
                            <?php if($adherent->email): ?>
                            <div>
                                <i class="fas fa-envelope text-muted me-1"></i>
                                <small class="text-muted"><?php echo e(Str::limit($adherent->email, 20)); ?></small>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($adherent->agence): ?>
                                <span class="badge bg-info text-white">
                                    <i class="fas fa-building me-1"></i><?php echo e($adherent->agence->nom); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Non affecté
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($adherent->agentGestionnaire): ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-user-tie me-1"></i><?php echo e($adherent->agentGestionnaire->name); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle me-1"></i>Aucun agent
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($adherent->statut_compte == 'actif'): ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            <?php elseif($adherent->statut_compte == 'en_attente_de_verification'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>En attente
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class="fas fa-ban me-1"></i>Inactif
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.adherents.affectation', $adherent)); ?>" 
                                   class="btn btn-sm btn-primary" 
                                   title="Affecter à une agence/agent"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                                <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" 
                                   class="btn btn-sm btn-info" 
                                   title="Voir le profil"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h4 class="text-success mb-2">Excellent travail !</h4>
                                <p class="text-muted mb-0">Tous les adhérents sont correctement affectés à une agence et un agent gestionnaire.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($adherents->hasPages()): ?>
            <div class="p-3 border-top bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de <?php echo e($adherents->firstItem()); ?> à <?php echo e($adherents->lastItem()); ?> sur <?php echo e($adherents->total()); ?> résultats
                    </div>
                    <div>
                        <?php echo e($adherents->links()); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Guide -->
<div class="modal fade" id="guideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle me-2"></i>Guide d'Affectation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary mb-3">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-building me-2"></i>Affectation à une Agence
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Détermine le lieu de gestion du compte
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Les opérations s'effectuent dans cette agence
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Facilite les rapports par agence
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success mb-3">
                            <div class="card-header bg-success text-white">
                                <i class="fas fa-user-tie me-2"></i>Affectation à un Agent
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Responsable du suivi client
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Reçoit les notifications importantes
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Peut être principal ou secondaire
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Bonne pratique :</strong> Affectez l'adhérent à un agent de la même agence pour une meilleure coordination.
                </div>
                
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <i class="fas fa-bolt me-2"></i>Affectation Rapide en Masse
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li>Cochez les adhérents à affecter</li>
                            <li>Sélectionnez une agence (optionnel)</li>
                            <li>Sélectionnez un ou plusieurs agents</li>
                            <li>Cliquez sur "Affecter"</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments DOM
    const checkboxes = document.querySelectorAll('.adherent-check');
    const selectAllCheckbox = document.getElementById('selectAll');
    const btnAffecterRapide = document.getElementById('btnAffecterRapide');
    const countRapide = document.getElementById('countRapide');
    const selectionInfo = document.getElementById('selectionInfo');
    const agenceRapide = document.getElementById('agence_rapide');
    const searchAgentRapide = document.getElementById('searchAgentRapide');
    const agentItemsRapide = document.querySelectorAll('.agent-item-rapide');
    const agentCheckboxesRapide = document.querySelectorAll('.agent-checkbox-rapide');
    const selectedAgentsCountRapide = document.getElementById('selectedAgentsCountRapide');

    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Mise à jour du compteur d'adhérents sélectionnés
    function updateCount() {
        const count = document.querySelectorAll('.adherent-check:checked').length;
        countRapide.textContent = count;
        selectionInfo.textContent = count + ' sélectionné(s)';
        btnAffecterRapide.disabled = count === 0;
        
        // Changer la couleur du badge selon le nombre
        selectionInfo.className = 'badge ' + (count > 0 ? 'bg-success' : 'bg-primary');
    }

    // Mise à jour du compteur d'agents sélectionnés
    function updateAgentCount() {
        const count = document.querySelectorAll('.agent-checkbox-rapide:checked').length;
        selectedAgentsCountRapide.textContent = count + ' sélectionné(s)';
        selectedAgentsCountRapide.className = 'badge ms-2 ' + (count > 0 ? 'bg-success' : 'bg-primary');
    }

    // Sélection individuelle d'adhérents
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCount();
            // Mettre à jour l'état de "Tout sélectionner"
            const checkedCount = document.querySelectorAll('.adherent-check:checked').length;
            selectAllCheckbox.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });
    });

    // Tout sélectionner / désélectionner
    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateCount();
    });

    // Filtrer les agents par agence sélectionnée
    agenceRapide.addEventListener('change', function() {
        const selectedAgence = this.value;
        let visibleCount = 0;
        
        agentItemsRapide.forEach(item => {
            const agentAgence = item.dataset.agence;
            const checkbox = item.querySelector('.agent-checkbox-rapide');
            
            // Afficher si aucune agence sélectionnée OU si l'agent appartient à l'agence
            if (!selectedAgence || agentAgence === selectedAgence) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
                // Décocher si masqué
                if (checkbox) checkbox.checked = false;
            }
        });
        
        updateAgentCount();
        
        // Afficher un message si aucun agent disponible
        const agentsList = document.getElementById('agentsListRapide');
        const noAgentMsg = agentsList.querySelector('.no-agent-message');
        
        if (visibleCount === 0 && !noAgentMsg) {
            const msg = document.createElement('div');
            msg.className = 'text-center text-muted py-3 no-agent-message';
            msg.innerHTML = '<i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i><small>Aucun agent disponible pour cette agence</small>';
            agentsList.appendChild(msg);
        } else if (visibleCount > 0 && noAgentMsg) {
            noAgentMsg.remove();
        }
    });
    
    // Recherche d'agents
    searchAgentRapide.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const selectedAgence = agenceRapide.value;
        
        agentItemsRapide.forEach(item => {
            const searchData = item.dataset.search;
            const agentAgence = item.dataset.agence;
            
            const matchesSearch = searchTerm === '' || searchData.includes(searchTerm);
            const matchesAgence = !selectedAgence || agentAgence === selectedAgence;
            
            item.style.display = (matchesSearch && matchesAgence) ? '' : 'none';
        });
    });
    
    // Écouter les changements de sélection d'agents
    agentCheckboxesRapide.forEach(checkbox => {
        checkbox.addEventListener('change', updateAgentCount);
    });

    // Validation et soumission du formulaire
    document.getElementById('formAffectationRapide').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedIds = Array.from(document.querySelectorAll('.adherent-check:checked'))
            .map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('⚠️ Veuillez sélectionner au moins un adhérent à affecter.');
            return false;
        }
        
        const agence = agenceRapide.value;
        const agents = Array.from(document.querySelectorAll('.agent-checkbox-rapide:checked'));
        
        if (!agence && agents.length === 0) {
            alert('⚠️ Veuillez sélectionner au moins une agence ou un agent.');
            return false;
        }
        
        // Confirmation
        const confirmMsg = `Vous allez affecter ${selectedIds.length} adhérent(s) :\n` +
            (agence ? `- Agence sélectionnée\n` : '') +
            (agents.length > 0 ? `- ${agents.length} agent(s) sélectionné(s)\n` : '') +
            `\nConfirmer l'affectation ?`;
        
        if (confirm(confirmMsg)) {
            document.getElementById('adherent_ids_rapide').value = JSON.stringify(selectedIds);
            this.submit();
        }
    });
    
    // Initialiser les compteurs
    updateCount();
    updateAgentCount();
});
</script>

<style>
.hover-bg-white:hover {
    background-color: rgba(255, 255, 255, 0.9);
    transition: background-color 0.2s ease;
}

.agent-item-rapide {
    transition: all 0.2s ease;
    border-bottom: 1px solid #eee;
}

.agent-item-rapide:last-child {
    border-bottom: none;
}

.agent-item-rapide:hover {
    background-color: #f8f9fa;
    transform: translateX(2px);
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    cursor: pointer;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}

.border-left-danger {
    border-left: 4px solid #e74a3b;
}

.border-left-info {
    border-left: 4px solid #36b9cc;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/affectations/non-affectes.blade.php ENDPATH**/ ?>