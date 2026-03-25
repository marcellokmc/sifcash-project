

<?php $__env->startSection('title', 'Affectation Adhérent'); ?>
<?php $__env->startSection('page-title', 'Affecter Agence et Agent'); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.adherents.index')); ?>">Adhérents</a></li>
        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>"><?php echo e($adherent->nom_complet); ?></a></li>
        <li class="breadcrumb-item active">Affectation</li>
    </ol>
</nav>

<div class="row">
    <!-- Informations adhérent -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user"></i> Adhérent
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                </div>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">ID Membre:</td>
                        <td><strong><?php echo e($adherent->membre_id); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nom complet:</td>
                        <td><strong><?php echo e($adherent->nom_complet); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Téléphone:</td>
                        <td><?php echo e($adherent->telephone); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email:</td>
                        <td><?php echo e($adherent->email); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut:</td>
                        <td>
                            <?php if($adherent->statut_compte == 'actif'): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php elseif($adherent->statut_compte == 'en_attente_de_verification'): ?>
                                <span class="badge bg-warning">En attente</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Affectation actuelle -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle"></i> Affectation Actuelle
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted d-block mb-1">Agence:</label>
                    <?php if($adherent->agence): ?>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-building text-primary me-2"></i>
                            <strong><?php echo e($adherent->agence->nom); ?></strong>
                        </div>
                        <small class="text-muted"><?php echo e($adherent->agence->ville); ?></small>
                    <?php else: ?>
                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Non affecté</span>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="text-muted d-block mb-1">Agent gestionnaire:</label>
                    <?php if($adherent->agentGestionnaire): ?>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie text-success me-2"></i>
                            <strong><?php echo e($adherent->agentGestionnaire->name); ?></strong>
                        </div>
                        <small class="text-muted"><?php echo e($adherent->agentGestionnaire->email); ?></small>
                    <?php else: ?>
                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Non affecté</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'affectation -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit"></i> Nouvelle Affectation
                </h6>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.adherents.affecter', $adherent)); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Vous pouvez affecter cet adhérent à une agence et/ou à un agent gestionnaire.
                        L'agent gestionnaire sera responsable du suivi de ce compte.
                    </div>

                    <!-- Sélection agence -->
                    <div class="mb-4">
                        <label for="agence_id" class="form-label">
                            <i class="fas fa-building text-primary"></i> Agence de rattachement
                        </label>
                        <select name="agence_id" id="agence_id" 
                                class="form-select <?php $__errorArgs = ['agence_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Sélectionner une agence --</option>
                            <?php $__currentLoopData = $agences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agence->id); ?>" 
                                        <?php echo e(old('agence_id', $adherent->agence_id) == $agence->id ? 'selected' : ''); ?>>
                                    <?php echo e($agence->nom); ?> - <?php echo e($agence->ville); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['agence_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted">
                            L'agence de rattachement détermine où l'adhérent effectuera ses opérations.
                        </small>
                    </div>

                    <!-- Sélection agent gestionnaire -->
                    <div class="mb-4">
                        <label for="agent_gestionnaire_id" class="form-label">
                            <i class="fas fa-user-tie text-success"></i> Agent gestionnaire
                            <span id="agentCount" class="badge bg-secondary ms-2"><?php echo e(count($agents)); ?> disponible(s)</span>
                        </label>
                        <select name="agent_gestionnaire_id" id="agent_gestionnaire_id" 
                                class="form-select <?php $__errorArgs = ['agent_gestionnaire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Sélectionner un agent (<?php echo e(count($agents)); ?> disponible(s)) --</option>
                            <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agent->id); ?>" 
                                        data-role="<?php echo e($agent->role); ?>"
                                        data-agence="<?php echo e($agent->agence_id ?? ''); ?>"
                                        <?php echo e(old('agent_gestionnaire_id', $adherent->agent_gestionnaire_id) == $agent->id ? 'selected' : ''); ?>>
                                    <?php echo e($agent->name); ?> 
                                    <?php if($agent->agence): ?>
                                        (<?php echo e($agent->agence->nom); ?>)
                                    <?php endif; ?>
                                    - <?php echo e(ucfirst(str_replace('_', ' ', $agent->role))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['agent_gestionnaire_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text text-muted" id="agentHelpText">
                            L'agent gestionnaire sera responsable du suivi et de la gestion de ce compte adhérent.
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> La modification de l'affectation prendra effet immédiatement.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Enregistrer l'affectation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guide -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-question-circle"></i> Guide d'affectation
                </h6>
            </div>
            <div class="card-body">
                <h6 class="text-primary"><i class="fas fa-building"></i> Agence</h6>
                <ul>
                    <li>L'agence détermine le lieu principal de gestion du compte</li>
                    <li>Les opérations bancaires seront effectuées dans cette agence</li>
                    <li>Les rapports seront associés à cette agence</li>
                </ul>

                <h6 class="text-success mt-3"><i class="fas fa-user-tie"></i> Agent gestionnaire</h6>
                <ul>
                    <li>L'agent sera notifié des activités importantes de ce compte</li>
                    <li>Il aura la responsabilité du suivi et de la relation client</li>
                    <li>Il peut voir les statistiques des adhérents qui lui sont affectés</li>
                </ul>

                <div class="alert alert-info mt-3 mb-0">
                    <strong>Astuce :</strong> Il est recommandé d'affecter l'adhérent à un agent de la même agence pour faciliter la coordination.
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const agenceSelect = document.getElementById('agence_id');
    const agentSelect = document.getElementById('agent_gestionnaire_id');
    const agentCountBadge = document.getElementById('agentCount');
    const agentHelpText = document.getElementById('agentHelpText');
    
    console.log('Total agents dans le select:', agentSelect.options.length - 1); // -1 pour exclure l'option vide
    
    // Fonction pour mettre à jour le compteur d'agents visibles
    function updateAgentCount() {
        let visibleCount = 0;
        Array.from(agentSelect.options).forEach(option => {
            if (option.value !== '' && option.style.display !== 'none') {
                visibleCount++;
            }
        });
        
        agentCountBadge.textContent = visibleCount + ' disponible(s)';
        agentSelect.options[0].text = '-- Sélectionner un agent (' + visibleCount + ' disponible(s)) --';
        
        if (visibleCount === 0) {
            agentHelpText.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Aucun agent disponible pour cette agence.';
            agentHelpText.classList.add('text-warning');
        } else {
            agentHelpText.innerHTML = "L'agent gestionnaire sera responsable du suivi et de la gestion de ce compte adhérent.";
            agentHelpText.classList.remove('text-warning');
        }
    }
    
    // Filtrer les agents par agence sélectionnée
    agenceSelect.addEventListener('change', function() {
        const selectedAgence = this.value;
        
        console.log('Agence sélectionnée:', selectedAgence);
        
        Array.from(agentSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const agentAgence = option.dataset.agence;
            console.log('Agent:', option.text, 'Agence ID:', agentAgence);
            
            // Si aucune agence n'est sélectionnée, afficher tous les agents
            if (!selectedAgence) {
                option.style.display = '';
            }
            // Si l'agent appartient à l'agence sélectionnée, l'afficher
            else if (agentAgence === selectedAgence) {
                option.style.display = '';
            }
            // Sinon, masquer l'option
            else {
                option.style.display = 'none';
            }
        });
        
        // Mettre à jour le compteur
        updateAgentCount();
        
        // Réinitialiser la sélection de l'agent si elle n'est plus visible
        if (agentSelect.selectedOptions[0] && agentSelect.selectedOptions[0].style.display === 'none') {
            agentSelect.value = '';
        }
    });
    
    // Initialiser le compteur au chargement
    updateAgentCount();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/adherents/affectation.blade.php ENDPATH**/ ?>