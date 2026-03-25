

<?php $__env->startSection('title', 'Détails du Commercial'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Détails du Commercial
                        </h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="exportData()">
                                <i class="fas fa-download me-2"></i>Exporter
                            </button>
                            <a href="<?php echo e(route('admin.commercials.edit', $commercial)); ?>" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <a href="<?php echo e(route('admin.commercials.index')); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informations Personnelles</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nom :</strong></td>
                                    <td><?php echo e($commercial->nom); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Prénoms :</strong></td>
                                    <td><?php echo e($commercial->prenoms); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nom Complet :</strong></td>
                                    <td><?php echo e($commercial->nom_complet); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Téléphone :</strong></td>
                                    <td>
                                        <a href="tel:<?php echo e($commercial->telephone); ?>" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i><?php echo e($commercial->telephone); ?>

                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Code Commercial :</strong></td>
                                    <td><span class="badge bg-info"><?php echo e($commercial->code_commercial); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Statut :</strong></td>
                                    <td>
                                        <?php if($commercial->actif): ?>
                                            <span class="badge bg-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informations Système</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Date de création :</strong></td>
                                    <td><?php echo e($commercial->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Dernière modification :</strong></td>
                                    <td><?php echo e($commercial->updated_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php if($commercial->notes): ?>
                                    <tr>
                                        <td><strong>Notes :</strong></td>
                                        <td><?php echo e($commercial->notes); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Section Adhérents avec filtres multicritères -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-muted mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Adhérents associés (<?php echo e($adherents->total()); ?>)
                                </h6>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="toggleFilters()">
                                        <i class="fas fa-filter me-1"></i>Filtres
                                        <?php if(request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin'])): ?>
                                            <span class="badge bg-primary ms-1"><?php echo e(collect(request()->only(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']))->filter()->count()); ?></span>
                                        <?php endif; ?>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="clearFilters()">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </button>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="toggleView('table')" id="tableViewBtn">
                                            <i class="fas fa-table"></i>
                                        </button>
                                        <button class="btn btn-outline-info" onclick="toggleView('cards')" id="cardsViewBtn">
                                            <i class="fas fa-th-large"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtres multicritères -->
                            <div class="mb-4" id="filterCollapse" style="display: <?php echo e(request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin']) ? 'block' : 'none'); ?>;">
                                <div class="card border-secondary">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-filter me-2"></i>
                                            Filtres multicritères
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <form method="GET" action="<?php echo e(route('admin.commercials.show', $commercial)); ?>" id="filterForm">
                                            <div class="row g-3">
                                                <!-- Recherche par nom/prénom -->
                                                <div class="col-md-4">
                                                    <label for="search" class="form-label">Recherche (Nom/Prénom)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                        <input type="text" class="form-control" id="search" name="search" 
                                                               value="<?php echo e(request('search')); ?>" placeholder="Rechercher un adhérent..." 
                                                               onchange="submitFilters()" onkeyup="handleSearchKeyup(event)">
                                                    </div>
                                                </div>

                                                <!-- Filtre par statut -->
                                                <div class="col-md-3">
                                                    <label for="statut" class="form-label">Statut</label>
                                                    <select class="form-select" id="statut" name="statut" onchange="submitFilters()">
                                                        <option value="">Tous les statuts</option>
                                                        <option value="actif" <?php echo e(request('statut') == 'actif' ? 'selected' : ''); ?>>Actif</option>
                                                        <option value="en_attente_de_verification" <?php echo e(request('statut') == 'en_attente_de_verification' ? 'selected' : ''); ?>>En attente</option>
                                                        <option value="suspendu" <?php echo e(request('statut') == 'suspendu' ? 'selected' : ''); ?>>Suspendu</option>
                                                    </select>
                                                </div>

                                                <!-- Filtre par résidence -->
                                                <div class="col-md-3">
                                                    <label for="residence" class="form-label">Résidence</label>
                                                    <input type="text" class="form-control" id="residence" name="residence" 
                                                           value="<?php echo e(request('residence')); ?>" placeholder="Filtrer par résidence..." 
                                                           onchange="submitFilters()">
                                                </div>

                                                <!-- Filtre par profession -->
                                                <div class="col-md-2">
                                                    <label for="profession" class="form-label">Profession</label>
                                                    <input type="text" class="form-control" id="profession" name="profession" 
                                                           value="<?php echo e(request('profession')); ?>" placeholder="Profession..." 
                                                           onchange="submitFilters()">
                                                </div>

                                                <!-- Filtre par dates -->
                                                <div class="col-md-3">
                                                    <label for="date_debut" class="form-label">Date début</label>
                                                    <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                                           value="<?php echo e(request('date_debut')); ?>" onchange="submitFilters()">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="date_fin" class="form-label">Date fin</label>
                                                    <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                                           value="<?php echo e(request('date_fin')); ?>" onchange="submitFilters()">
                                                </div>

                                                <!-- Tri -->
                                                <div class="col-md-3">
                                                    <label for="sort_by" class="form-label">Trier par</label>
                                                    <select class="form-select" id="sort_by" name="sort_by" onchange="submitFilters()">
                                                        <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>Date d'inscription</option>
                                                        <option value="nom" <?php echo e(request('sort_by') == 'nom' ? 'selected' : ''); ?>>Nom</option>
                                                        <option value="prenom" <?php echo e(request('sort_by') == 'prenom' ? 'selected' : ''); ?>>Prénom</option>
                                                        <option value="statut_compte" <?php echo e(request('sort_by') == 'statut_compte' ? 'selected' : ''); ?>>Statut</option>
                                                        <option value="residence" <?php echo e(request('sort_by') == 'residence' ? 'selected' : ''); ?>>Résidence</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="sort_order" class="form-label">Ordre</label>
                                                    <select class="form-select" id="sort_order" name="sort_order" onchange="submitFilters()">
                                                        <option value="desc" <?php echo e(request('sort_order') == 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                                                        <option value="asc" <?php echo e(request('sort_order') == 'asc' ? 'selected' : ''); ?>>Croissant</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <div class="d-flex align-items-center">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-search me-2"></i>Appliquer les filtres
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="clearFilters()">
                                                            <i class="fas fa-times me-2"></i>Effacer
                                                        </button>
                                                        <div class="form-check ms-3">
                                                            <input class="form-check-input" type="checkbox" id="autoFilter" checked>
                                                            <label class="form-check-label" for="autoFilter">
                                                                Filtrage automatique
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques améliorées -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0"><?php echo e($stats['total']); ?></h4>
                                            <small>Total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0"><?php echo e($stats['actifs']); ?></h4>
                                            <small>Actifs</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0"><?php echo e($stats['en_attente']); ?></h4>
                                            <small>En attente</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0"><?php echo e($stats['suspendus']); ?></h4>
                                            <small>Suspendus</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center py-2">
                                            <h4 class="mb-0"><?php echo e($adherents->count() > 0 ? number_format(($stats['actifs'] / $adherents->total()) * 100, 1) : 0); ?>%</h4>
                                            <small>Taux d'activation</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtres actifs -->
                            <?php if(request()->hasAny(['search', 'statut', 'residence', 'profession', 'date_debut', 'date_fin'])): ?>
                                <div class="alert alert-info mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-filter me-2"></i>
                                            <strong>Filtres appliqués:</strong>
                                            <?php if(request('search')): ?><span class="badge bg-secondary me-1">Recherche: <?php echo e(request('search')); ?></span><?php endif; ?>
                                            <?php if(request('statut')): ?><span class="badge bg-secondary me-1">Statut: <?php echo e(request('statut')); ?></span><?php endif; ?>
                                            <?php if(request('residence')): ?><span class="badge bg-secondary me-1">Résidence: <?php echo e(request('residence')); ?></span><?php endif; ?>
                                            <?php if(request('profession')): ?><span class="badge bg-secondary me-1">Profession: <?php echo e(request('profession')); ?></span><?php endif; ?>
                                            <?php if(request('date_debut')): ?><span class="badge bg-secondary me-1">Du: <?php echo e(request('date_debut')); ?></span><?php endif; ?>
                                            <?php if(request('date_fin')): ?><span class="badge bg-secondary me-1">Au: <?php echo e(request('date_fin')); ?></span><?php endif; ?>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if($adherents->count() > 0): ?>
                                <!-- Vue Tableau -->
                                <div id="tableView">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" id="adherentsTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Nom Complet</th>
                                                    <th>Contact</th>
                                                    <th>Localisation</th>
                                                    <th>Profession</th>
                                                    <th>Date d'inscription</th>
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="form-check-input adherent-checkbox" value="<?php echo e($adherent->id); ?>">
                                                        </td>
                                                        <td><?php echo e($adherent->id); ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    <?php echo e(strtoupper(substr($adherent->nom, 0, 1))); ?>

                                                                </div>
                                                                <div>
                                                                    <strong><?php echo e($adherent->nom); ?> <?php echo e($adherent->prenom); ?></strong>
                                                                    <?php if($adherent->commercial_id): ?>
                                                                        <br><small class="text-muted">Ref: <?php echo e($adherent->commercial->code_commercial); ?></small>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <div><i class="fas fa-phone me-1"></i><?php echo e($adherent->telephone); ?></div>
                                                                <div><i class="fas fa-envelope me-1"></i><?php echo e($adherent->email); ?></div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="small">
                                                                <div><i class="fas fa-map-marker-alt me-1"></i><?php echo e($adherent->residence ?? '-'); ?></div>
                                                                <?php if($adherent->secteur_numero): ?>
                                                                    <div><i class="fas fa-hashtag me-1"></i>Secteur <?php echo e($adherent->secteur_numero); ?></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td><?php echo e($adherent->profession ?? '-'); ?></td>
                                                        <td>
                                                            <div class="small">
                                                                <div><?php echo e($adherent->created_at->format('d/m/Y')); ?></div>
                                                                <div class="text-muted"><?php echo e($adherent->created_at->format('H:i')); ?></div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo e($adherent->statut_compte == 'actif' ? 'success' : ($adherent->statut_compte == 'en_attente_de_verification' ? 'warning' : 'danger')); ?>">
                                                                <?php echo e($adherent->statut_compte); ?>

                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" class="btn btn-outline-primary" title="Voir">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo e(route('admin.adherents.edit', $adherent)); ?>" class="btn btn-outline-secondary" title="Modifier">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <?php if($adherent->statut_compte == 'actif'): ?>
                                                                    <form method="POST" action="<?php echo e(route('admin.adherents.deactivate', $adherent)); ?>" class="d-inline">
                                                                        <?php echo csrf_field(); ?>
                                                                        <button type="submit" class="btn btn-outline-warning" title="Désactiver" onclick="return confirm('Désactiver cet adhérent?')">
                                                                            <i class="fas fa-pause"></i>
                                                                        </button>
                                                                    </form>
                                                                <?php else: ?>
                                                                    <form method="POST" action="<?php echo e(route('admin.adherents.activate', $adherent)); ?>" class="d-inline">
                                                                        <?php echo csrf_field(); ?>
                                                                        <button type="submit" class="btn btn-outline-success" title="Activer" onclick="return confirm('Activer cet adhérent?')">
                                                                            <i class="fas fa-play"></i>
                                                                        </button>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Vue Cartes -->
                                <div id="cardsView" style="display: none;">
                                    <div class="row">
                                        <?php $__currentLoopData = $adherents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adherent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card h-100">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                                <?php echo e(strtoupper(substr($adherent->nom, 0, 1))); ?>

                                                            </div>
                                                            <div>
                                                                <strong><?php echo e($adherent->nom); ?> <?php echo e($adherent->prenom); ?></strong>
                                                                <br><small class="text-muted">#<?php echo e($adherent->id); ?></small>
                                                            </div>
                                                        </div>
                                                        <span class="badge bg-<?php echo e($adherent->statut_compte == 'actif' ? 'success' : ($adherent->statut_compte == 'en_attente_de_verification' ? 'warning' : 'danger')); ?>">
                                                            <?php echo e($adherent->statut_compte); ?>

                                                        </span>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="small">
                                                            <div class="mb-2">
                                                                <i class="fas fa-phone me-2 text-muted"></i><?php echo e($adherent->telephone); ?>

                                                            </div>
                                                            <div class="mb-2">
                                                                <i class="fas fa-envelope me-2 text-muted"></i><?php echo e($adherent->email); ?>

                                                            </div>
                                                            <div class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2 text-muted"></i><?php echo e($adherent->residence ?? '-'); ?>

                                                            </div>
                                                            <?php if($adherent->profession): ?>
                                                                <div class="mb-2">
                                                                    <i class="fas fa-briefcase me-2 text-muted"></i><?php echo e($adherent->profession); ?>

                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="mb-2">
                                                                <i class="fas fa-calendar me-2 text-muted"></i><?php echo e($adherent->created_at->format('d/m/Y H:i')); ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="btn-group btn-group-sm w-100">
                                                            <a href="<?php echo e(route('admin.adherents.show', $adherent)); ?>" class="btn btn-outline-primary">
                                                                <i class="fas fa-eye"></i> Voir
                                                            </a>
                                                            <a href="<?php echo e(route('admin.adherents.edit', $adherent)); ?>" class="btn btn-outline-secondary">
                                                                <i class="fas fa-edit"></i> Modifier
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <!-- Actions groupées -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <span class="text-muted me-3">
                                                    <span id="selectedCount">0</span> sélectionné(s)
                                                </span>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-success" onclick="bulkAction('activate')" id="bulkActivate" disabled>
                                                        <i class="fas fa-play me-1"></i>Activer
                                                    </button>
                                                    <button class="btn btn-outline-warning" onclick="bulkAction('deactivate')" id="bulkDeactivate" disabled>
                                                        <i class="fas fa-pause me-1"></i>Désactiver
                                                    </button>
                                                    <button class="btn btn-outline-info" onclick="bulkAction('export')" id="bulkExport" disabled>
                                                        <i class="fas fa-download me-1"></i>Exporter
                                                    </button>
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-muted">
                                                    Affichage de <?php echo e($adherents->firstItem()); ?> à <?php echo e($adherents->lastItem()); ?> 
                                                    sur <?php echo e($adherents->total()); ?> résultats
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    <?php echo e($adherents->links()); ?>

                                </div>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun adhérent trouvé avec les critères de filtrage actuels.
                                    <br><button class="btn btn-outline-secondary mt-2" onclick="clearFilters()">Réinitialiser les filtres</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentView = 'table';

// Gérer manuellement l'affichage des filtres
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filterCollapse = document.getElementById('filterCollapse');
    
    console.log('Filter toggle:', filterToggle);
    console.log('Filter collapse:', filterCollapse);
    
    if (filterToggle && filterCollapse) {
        filterToggle.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Toggle clicked!');
            
            const currentDisplay = filterCollapse.style.display;
            console.log('Current display:', currentDisplay);
            
            if (currentDisplay === 'none' || currentDisplay === '') {
                filterCollapse.style.display = 'block';
                filterToggle.innerHTML = '<i class="fas fa-filter me-1"></i>Filtres <i class="fas fa-chevron-up ms-1"></i>';
                console.log('Showing filters');
            } else {
                filterCollapse.style.display = 'none';
                filterToggle.innerHTML = '<i class="fas fa-filter me-1"></i>Filtres';
                console.log('Hiding filters');
            }
        });
    } else {
        console.error('Filter elements not found!');
    }
});

function submitFilters() {
    // Vérifier si le filtrage automatique est activé
    const autoFilter = document.getElementById('autoFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Ajouter un petit délai pour éviter les requêtes trop fréquentes
    clearTimeout(window.filterTimeout);
    window.filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 300);
}

function handleSearchKeyup(event) {
    const autoFilter = document.getElementById('autoFilter');
    if (!autoFilter || !autoFilter.checked) {
        return;
    }
    
    // Soumettre après 500ms d'inactivité pour la recherche
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
}

function toggleFilters() {
    const filterCollapse = document.getElementById('filterCollapse');
    if (!filterCollapse) {
        console.error('Filter collapse element not found!');
        return;
    }
    
    const currentDisplay = filterCollapse.style.display;
    console.log('Current display:', currentDisplay);
    
    if (currentDisplay === 'none' || currentDisplay === '') {
        filterCollapse.style.display = 'block';
        console.log('Showing filters');
    } else {
        filterCollapse.style.display = 'none';
        console.log('Hiding filters');
    }
}

function clearFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = "<?php echo e(route('admin.commercials.show', $commercial)); ?>";
}

function toggleView(view) {
    currentView = view;
    
    // Cacher les deux vues
    const tableView = document.getElementById('tableView');
    const cardsView = document.getElementById('cardsView');
    
    if (tableView) tableView.style.display = 'none';
    if (cardsView) cardsView.style.display = 'none';
    
    // Afficher la vue sélectionnée
    if (view === 'table' && tableView) {
        tableView.style.display = 'block';
        document.getElementById('tableViewBtn').classList.add('active');
        document.getElementById('cardsViewBtn').classList.remove('active');
    } else if (view === 'cards' && cardsView) {
        cardsView.style.display = 'block';
        document.getElementById('cardsViewBtn').classList.add('active');
        document.getElementById('tableViewBtn').classList.remove('active');
    }
}

function exportData() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.location.href = "<?php echo e(route('admin.commercials.show', $commercial)); ?>?" + params.toString();
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.adherent-checkbox:checked');
    const ids = Array.from(selected).map(cb => cb.value);
    
    if (ids.length === 0) {
        alert('Veuillez sélectionner au moins un adhérent');
        return;
    }
    
    if (action === 'export') {
        exportData();
    } else if (action === 'activate' || action === 'deactivate') {
        if (!confirm(`${action === 'activate' ? 'Activer' : 'Désactiver'} les ${ids.length} adhérent(s) sélectionné(s)?`)) {
            return;
        }
        
        // Implémenter l'action groupée ici
        console.log(`${action} sur les adhérents:`, ids);
    }
}

// Gestion de la sélection
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.adherent-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const bulkButtons = ['bulkActivate', 'bulkDeactivate', 'bulkExport'];
    
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.adherent-checkbox:checked');
        if (selectedCount) selectedCount.textContent = selected.length;
        
        // Activer/désactiver les boutons groupés
        bulkButtons.forEach(btnId => {
            const btn = document.getElementById(btnId);
            if (btn) btn.disabled = selected.length === 0;
        });
    }
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectedCount();
        });
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/backoffice/commercials/show.blade.php ENDPATH**/ ?>