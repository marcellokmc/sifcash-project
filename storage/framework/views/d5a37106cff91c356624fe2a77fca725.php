

<?php $__env->startSection('title', 'Gestion des Adhérents'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .stats-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .filter-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8f9fc 0%, #fff 100%);
    }
    .table-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
    }
    .search-input {
        border-radius: 25px;
        padding-left: 45px;
        border: 2px solid #e3e6f0;
        transition: all 0.3s ease;
    }
    .search-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['total'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['actifs'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">En attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['en_attente'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Inactifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['inactifs'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Docs en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['documents_attente'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card stats-card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Ayants droit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($stats['ayants_droit_attente'])); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card filter-card mb-4">
        <div class="card-header bg-gradient-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-search me-2"></i>Recherche et Filtres
                </h5>
                <div>
                    <a href="<?php echo e(route('admin.validation.documents')); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-file-alt"></i> Documents (<?php echo e($stats['documents_attente']); ?>)
                    </a>
                    <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-users"></i> Ayants droit (<?php echo e($stats['ayants_droit_attente']); ?>)
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.adherents.index')); ?>" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="position-relative">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" class="form-control search-input" 
                                   placeholder="Rechercher par nom, prénom, ID..." 
                                   value="<?php echo e(request('search')); ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="actif" <?php echo e(request('status') == 'actif' ? 'selected' : ''); ?>>Actifs</option>
                            <option value="en_attente" <?php echo e(request('status') == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                            <option value="inactif" <?php echo e(request('status') == 'inactif' ? 'selected' : ''); ?>>Inactifs</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <input type="text" name="phone" class="form-control" 
                               placeholder="Téléphone" 
                               value="<?php echo e(request('phone')); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <input type="text" name="email" class="form-control" 
                               placeholder="Email" 
                               value="<?php echo e(request('email')); ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <select name="profession" class="form-select">
                            <option value="">Toutes professions</option>
                            <?php $__currentLoopData = $professions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($profession); ?>" <?php echo e(request('profession') == $profession ? 'selected' : ''); ?>>
                                    <?php echo e($profession); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-1">
                        <div class="btn-group w-100">
                            <button type="submit" class="btn btn-primary" title="Rechercher">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="<?php echo e(route('admin.adherents.index')); ?>" class="btn btn-outline-secondary" title="Réinitialiser">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des adhérents -->
    <div class="card table-container">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Liste des Adhérents (<?php echo e($adherents->total()); ?> trouvé<?php echo e($adherents->total() > 1 ? 's' : ''); ?>)
                </h6>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="exportData('excel')">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button class="btn btn-outline-secondary" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="adherentsTable">
                <thead class="table-light">
                    <tr>
                        <th class="border-0">Adhérent</th>
                        <th class="border-0">Contact</th>
                        <th class="border-0">Statut</th>
                        <th class="border-0">Ayants droit</th>
                        <th class="border-0">Documents</th>
                        <th class="border-0">Adhésions</th>
                        <th class="border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    <?php echo e(strtoupper(substr($adherent->nom, 0, 1) . substr($adherent->prenom, 0, 1))); ?>

                                </div>
                                <div>
                                    <div class="fw-bold"><?php echo e($adherent->nom_complet); ?></div>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card me-1"></i><?php echo e($adherent->membre_id); ?>

                                    </small>
                                    <?php if($adherent->profession): ?>
                                        <div><small class="text-info"><?php echo e($adherent->profession); ?></small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <div class="small">
                                <div><i class="fas fa-phone text-success me-1"></i><?php echo e($adherent->telephone); ?></div>
                                <?php if($adherent->email): ?>
                                    <div><i class="fas fa-envelope text-primary me-1"></i><?php echo e(Str::limit($adherent->email, 20)); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        
                        <td>
                            <?php
                                $statutClass = 'secondary';
                                $statutIcon = 'user';
                                $statutText = ucfirst($adherent->statut_compte ?? 'Non défini');
                                
                                if($adherent->isSuspended()) {
                                    $statutClass = 'danger';
                                    $statutIcon = 'ban';
                                    $statutText = 'Suspendu';
                                } elseif($adherent->isActif()) {
                                    $statutClass = 'success';
                                    $statutIcon = 'check-circle';
                                    $statutText = 'Actif';
                                } elseif($adherent->isEnAttente()) {
                                    $statutClass = 'warning';
                                    $statutIcon = 'clock';
                                    $statutText = 'En attente';
                                } elseif(isset($adherent->statut_compte) && $adherent->statut_compte === 'inactif') {
                                    $statutClass = 'danger';
                                    $statutIcon = 'times-circle';
                                    $statutText = 'Inactif';
                                }
                            ?>
                            <span class="badge bg-<?php echo e($statutClass); ?> status-badge">
                                <i class="fas fa-<?php echo e($statutIcon); ?> me-1"></i><?php echo e($statutText); ?>

                            </span>
                            <?php if($adherent->isSuspended()): ?>
                                <div class="small text-danger mt-1">
                                    <i class="fas fa-exclamation-triangle"></i> Compte suspendu
                                </div>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <span class="badge bg-info rounded-pill"><?php echo e($adherent->ayants_droit_count ?? 0); ?></span>
                            <?php if($adherent->ayantsDroit && $adherent->ayantsDroit->where('statut_validation', 'en_attente')->count() > 0): ?>
                                <span class="badge bg-warning rounded-pill ms-1" title="En attente de validation">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <span class="badge bg-secondary rounded-pill"><?php echo e($adherent->documents_count ?? 0); ?></span>
                            <?php if($adherent->documents && $adherent->documents->where('statut', 'soumis')->count() > 0): ?>
                                <span class="badge bg-warning rounded-pill ms-1" title="Documents à valider">
                                    <?php echo e($adherent->documents->where('statut', 'soumis')->count()); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <span class="badge bg-primary rounded-pill"><?php echo e($adherent->adhesions_count ?? 0); ?></span>
                        </td>
                        
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" 
                                   class="btn btn-outline-primary" 
                                   title="Voir les détails"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.adherents.edit', $adherent)); ?>" 
                                   class="btn btn-outline-secondary" 
                                   title="Modifier"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <?php if($adherent->isEnAttente()): ?>
                                    <button type="button" 
                                            class="btn btn-outline-success" 
                                            title="Activer le compte" 
                                            onclick="activateAdherent(<?php echo e($adherent->id); ?>)"
                                            data-bs-toggle="tooltip">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-info dropdown-toggle" 
                                            data-bs-toggle="dropdown"
                                            title="Plus d'actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <h6 class="dropdown-header"><i class="fas fa-user me-2"></i>Profil</h6>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('admin.adherents.show', $adherent)); ?>">
                                                <i class="fas fa-id-card me-2"></i>Profil complet
                                            </a>
                                        </li>
                                        <?php if($adherent->documents_count > 0): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('admin.adherents.show', $adherent)); ?>#documents">
                                                    <i class="fas fa-file-alt me-2"></i>Documents (<?php echo e($adherent->documents_count); ?>)
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($adherent->ayants_droit_count > 0): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('admin.adherents.show', $adherent)); ?>#ayants-droit">
                                                    <i class="fas fa-users me-2"></i>Ayants droit (<?php echo e($adherent->ayants_droit_count); ?>)
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <h6 class="dropdown-header"><i class="fas fa-shield-alt me-2"></i>Gestion compte</h6>
                                        </li>
                                        
                                        <?php if($adherent->user): ?>
                                            <li>
                                                <a class="dropdown-item text-warning" 
                                                   href="#"
                                                   onclick="event.preventDefault(); openResetPasswordModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', true);">
                                                    <i class="fas fa-key me-2"></i>Réinitialiser mot de passe
                                                </a>
                                            </li>
                                            
                                            <?php if($adherent->isSuspended()): ?>
                                                <li>
                                                    <a class="dropdown-item text-success" 
                                                       href="#"
                                                       onclick="event.preventDefault(); openActivateModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', '<?php echo e($adherent->suspended_at?->format('d/m/Y H:i')); ?>', '<?php echo e($adherent->suspension_reason); ?>', '<?php echo e($adherent->suspendedByUser?->name); ?>', true);">
                                                        <i class="fas fa-check-circle me-2"></i>Réactiver le compte
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a class="dropdown-item text-danger" 
                                                       href="#"
                                                       onclick="event.preventDefault(); openSuspendModal(<?php echo e($adherent->id); ?>, '<?php echo e($adherent->nom_complet); ?>', true);">
                                                        <i class="fas fa-ban me-2"></i>Suspendre le compte
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <li>
                                                <span class="dropdown-item text-muted" style="cursor: not-allowed;">
                                                    <i class="fas fa-info-circle me-2"></i>Aucun compte utilisateur
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                <h5>Aucun adhérent trouvé</h5>
                                <p>Essayez de modifier vos critères de recherche</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($adherents->hasPages()): ?>
            <div class="card-footer bg-white">
                <?php echo e($adherents->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal d'activation -->
<div class="modal fade" id="activateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activation du compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir activer ce compte adhérent ?</p>
                <p class="text-muted small">Cette action enverra une notification à l'adhérent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="activateForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Activer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('backoffice.partials.account-management-modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Variables globales
let currentAdherentId = null;

// Fonction d'activation d'adhérent avec modal
function activateAdherent(adherentId) {
    currentAdherentId = adherentId;
    const form = document.getElementById('activateForm');
    form.action = `/admin/adherents/${adherentId}/activate`;
    
    const modal = new bootstrap.Modal(document.getElementById('activateModal'));
    modal.show();
}

// Fonction d'export fonctionnelle
function exportData(format) {
    // Créer une URL avec tous les paramètres de filtre actuels
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('export', format);
    
    // Feedback visuel
    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Export...';
    btn.disabled = true;
    
    // Lancer le téléchargement
    window.location.href = currentUrl.toString();
    
    // Réactiver le bouton après 3 secondes
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }, 3000);
}

// Recherche en temps réel (avec debouncing)
let searchTimeout;
function setupLiveSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    document.getElementById('filterForm').submit();
                }
            }, 800); // Attendre 800ms après la dernière frappe
        });
    }
}

// Filtre automatique pour les selects
function setupAutoFilter() {
    const selects = document.querySelectorAll('#filterForm select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
}

// Animation des statistiques au chargement
function animateStats() {
    const statsNumbers = document.querySelectorAll('.stats-card .h5');
    statsNumbers.forEach((stat, index) => {
        const finalNumber = parseInt(stat.textContent.replace(/,/g, '')) || 0;
        let currentNumber = 0;
        const increment = Math.ceil(finalNumber / 20);
        
        const timer = setInterval(() => {
            currentNumber += increment;
            if (currentNumber >= finalNumber) {
                currentNumber = finalNumber;
                clearInterval(timer);
            }
            stat.textContent = new Intl.NumberFormat().format(currentNumber);
        }, 50 + (index * 20));
    });
}

// Confirmation pour les actions sensibles
function setupConfirmations() {
    // Activation des comptes
    document.querySelectorAll('form[action*="activate"] button[type="submit"]').forEach(button => {
        button.addEventListener('click', function(e) {
            const adherentName = this.closest('tr').querySelector('.fw-bold').textContent;
            if (!confirm(`Êtes-vous sûr de vouloir activer le compte de ${adherentName} ?`)) {
                e.preventDefault();
            }
        });
    });
}

// Amélioration du UX avec tooltips
function setupTooltips() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Ajouter des tooltips dynamiques
    document.querySelectorAll('.badge[title]').forEach(badge => {
        new bootstrap.Tooltip(badge);
    });
}

// Gestion des états de loading
function setupLoadingStates() {
    const form = document.getElementById('filterForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        if (submitBtn) {
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            
            // Restaurer après 3 secondes au cas où
            setTimeout(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }, 3000);
        }
    });
}

// Gestion du responsive pour les tables
function setupResponsiveTable() {
    function toggleTableColumns() {
        const table = document.getElementById('adherentsTable');
        const windowWidth = window.innerWidth;
        
        if (windowWidth < 768) {
            // Sur mobile, masquer certaines colonnes
            table.querySelectorAll('th:nth-child(4), td:nth-child(4)').forEach(el => {
                el.style.display = 'none';
            });
            table.querySelectorAll('th:nth-child(5), td:nth-child(5)').forEach(el => {
                el.style.display = 'none';
            });
        } else {
            // Sur desktop, afficher toutes les colonnes
            table.querySelectorAll('th, td').forEach(el => {
                el.style.display = '';
            });
        }
    }
    
    // Appliquer au chargement et au redimensionnement
    toggleTableColumns();
    window.addEventListener('resize', toggleTableColumns);
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎉 Interface adhérents chargée avec succès!');
    
    // Initialiser toutes les fonctionnalités
    setupLiveSearch();
    setupAutoFilter();
    setupConfirmations();
    setupTooltips();
    setupLoadingStates();
    setupResponsiveTable();
    
    // Animer les statistiques
    setTimeout(animateStats, 200);
    
    // Afficher un message de succès si présent
    <?php if(session('success')): ?>
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-success border-0';
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toast);
        });
    <?php endif; ?>
    
    // Message d'erreur si présent
    <?php if(session('error')): ?>
        const errorToast = document.createElement('div');
        errorToast.className = 'toast align-items-center text-white bg-danger border-0';
        errorToast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
        errorToast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(errorToast);
        
        const bsErrorToast = new bootstrap.Toast(errorToast, { delay: 5000 });
        bsErrorToast.show();
        
        errorToast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(errorToast);
        });
    <?php endif; ?>
});

// Gestion des erreurs AJAX globales
window.addEventListener('error', function(e) {
    console.error('Erreur détectée:', e.error);
});

// Prevention des double-clics sur les boutons
document.addEventListener('click', function(e) {
    if (e.target.matches('button[type="submit"], input[type="submit"]')) {
        setTimeout(() => {
            e.target.disabled = true;
            setTimeout(() => {
                e.target.disabled = false;
            }, 2000);
        }, 100);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Projetsw\sif-project\resources\views/backoffice/adherents/index.blade.php ENDPATH**/ ?>