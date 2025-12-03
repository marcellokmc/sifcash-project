

<?php $__env->startSection('title', 'Dashboard Admin - SIFCash-Burkina'); ?>
<?php $__env->startSection('page-title', '📈 Tableau de Bord Administrateur'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
        background-size: 300% 300%;
        animation: gradient-shift 8s ease infinite;
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .modern-stat-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
        height: 100%;
    }
    
    .modern-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        transition: height 0.3s ease;
    }
    
    .modern-stat-card.primary::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .modern-stat-card.success::before { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .modern-stat-card.warning::before { background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); }
    .modern-stat-card.info::before { background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); }
    .modern-stat-card.danger::before { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }
    
    .modern-stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .modern-stat-card:hover::before {
        height: 12px;
    }
    
    .stat-icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }
    
    .stat-icon-circle.primary { background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); }
    .stat-icon-circle.success { background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%); }
    .stat-icon-circle.warning { background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(249, 115, 22, 0.1) 100%); }
    .stat-icon-circle.info { background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%); }
    .stat-icon-circle.danger { background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(248, 113, 113, 0.1) 100%); }
    
    .modern-stat-card:hover .stat-icon-circle {
        transform: scale(1.1) rotate(10deg);
    }
    
    .modern-action-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 20px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.1) 0%, transparent 60%);
        pointer-events: none;
    }
    
    .modern-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }
    
    .action-btn {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.2);
        color: white;
        border-radius: 15px;
        padding: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        margin-bottom: 0.8rem;
        backdrop-filter: blur(10px);
    }
    
    .action-btn:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.4);
        color: white;
        transform: translateX(10px);
        text-decoration: none;
    }
    
    .activity-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .activity-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }
    
    .activity-header {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        padding: 1.5rem;
        border-radius: 20px 20px 0 0;
    }
    
    .chart-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }
    
    .chart-header {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        color: #000;
        padding: 1.5rem;
    }
    
    .alert-modern {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        border: none;
        border-radius: 20px;
        color: white;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(255, 154, 158, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .alert-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 80% 20%, rgba(255,255,255,0.15) 0%, transparent 60%);
        pointer-events: none;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Dashboard Modern -->
<div class="dashboard-header mb-4">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-2" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    🚀 Dashboard Administrateur SIF
                </h1>
                <p class="lead mb-0" style="opacity: 0.9;">
                    📊 Gestion centralisée et monitoring en temps réel - <?php echo e(now()->format('d/m/Y')); ?>

                </p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0"><?php echo e($stats['total_users'] ?? 0); ?></div>
                        <small style="opacity: 0.8;">👥 Total Users</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0"><?php echo e($stats['total_adherents'] ?? 0); ?></div>
                        <small style="opacity: 0.8;">💼 Adhérents</small>
                    </div>
                    <button class="btn btn-light btn-lg rounded-pill px-4" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Statistiques Utilisateurs -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card primary">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle primary mx-auto">
                    <i class="fas fa-users" style="font-size: 2rem; color: #667eea;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #667eea;"><?php echo e($stats['total_users']); ?></h2>
                <h6 class="text-muted mb-2">👥 Utilisateurs Total</h6>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar" style="width: 85%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Adhérents -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card success">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle success mx-auto">
                    <i class="fas fa-user-check" style="font-size: 2rem; color: #11998e;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #11998e;"><?php echo e($stats['total_adherents'] ?? 0); ?></h2>
                <h6 class="text-muted mb-2">💼 Adhérents Actifs</h6>
                <?php if(isset($stats['adherents_en_attente']) && $stats['adherents_en_attente'] > 0): ?>
                    <div class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-weight: 600;">
                        <i class="fas fa-clock me-1"></i><?php echo e($stats['adherents_en_attente']); ?> en attente
                    </div>
                <?php endif; ?>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar" style="width: 75%; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents en attente -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card warning">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle warning mx-auto">
                    <i class="fas fa-file-alt" style="font-size: 2rem; color: #f59e0b;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #f59e0b;"><?php echo e($stats['documents_en_attente'] ?? 0); ?></h2>
                <h6 class="text-muted mb-2">📄 Documents à Valider</h6>
                <?php if(isset($stats['documents_en_attente']) && $stats['documents_en_attente'] > 0): ?>
                    <a href="<?php echo e(route('admin.validation.documents')); ?>" class="btn btn-sm rounded-pill px-3" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 2px solid rgba(245, 158, 11, 0.2); text-decoration: none; font-weight: 600;">
                        <i class="fas fa-eye me-1"></i>👁️ Voir
                    </a>
                <?php endif; ?>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar" style="width: <?php echo e(min(($stats['documents_en_attente'] ?? 0) * 10, 100)); ?>%; background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ayants droit en attente -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-stat-card info">
            <div class="card-body p-4 text-center">
                <div class="stat-icon-circle info mx-auto">
                    <i class="fas fa-users" style="font-size: 2rem; color: #06b6d4;"></i>
                </div>
                <h2 class="fw-bold mb-1" style="color: #06b6d4;"><?php echo e($stats['ayants_droit_en_attente'] ?? 0); ?></h2>
                <h6 class="text-muted mb-2">👨‍👩‍👧‍👦 Ayants Droit à Valider</h6>
                <?php if(isset($stats['ayants_droit_en_attente']) && $stats['ayants_droit_en_attente'] > 0): ?>
                    <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="btn btn-sm rounded-pill px-3" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4; border: 2px solid rgba(6, 182, 212, 0.2); text-decoration: none; font-weight: 600;">
                        <i class="fas fa-eye me-1"></i>✓ Valider
                    </a>
                <?php endif; ?>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar" style="width: <?php echo e(min(($stats['ayants_droit_en_attente'] ?? 0) * 15, 100)); ?>%; background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <!-- Actions rapides Module 2 & 3 -->
    <div class="col-lg-4">
        <div class="modern-action-card">
            <div class="card-body p-4 position-relative">
                <h5 class="fw-bold mb-4 text-white">
                    <i class="fas fa-lightning-bolt me-2"></i>⚡ Actions Rapides
                </h5>
                <p class="text-white mb-4" style="opacity: 0.8;">Accès direct aux tâches importantes</p>
                
                <div class="d-grid gap-3">
                    <a href="<?php echo e(route('admin.adherents.index')); ?>" class="action-btn">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-users me-3" style="font-size: 1.2rem;"></i>
                                <strong>👥 Gérer les adhérents</strong>
                            </div>
                            <?php if(isset($stats['adherents_en_attente']) && $stats['adherents_en_attente'] > 0): ?>
                                <span class="badge" style="background: rgba(245, 158, 11, 0.8); color: white; font-weight: 600;"><?php echo e($stats['adherents_en_attente']); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo e(route('admin.validation.documents')); ?>" class="action-btn">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-file-check me-3" style="font-size: 1.2rem;"></i>
                                <strong>📄 Valider les documents</strong>
                            </div>
                            <?php if(isset($stats['documents_en_attente']) && $stats['documents_en_attente'] > 0): ?>
                                <span class="badge" style="background: rgba(245, 158, 11, 0.8); color: white; font-weight: 600;"><?php echo e($stats['documents_en_attente']); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="action-btn">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-user-friends me-3" style="font-size: 1.2rem;"></i>
                                <strong>👨‍👩‍👧‍👦 Valider ayants droit</strong>
                            </div>
                            <?php if(isset($stats['ayants_droit_en_attente']) && $stats['ayants_droit_en_attente'] > 0): ?>
                                <span class="badge" style="background: rgba(6, 182, 212, 0.8); color: white; font-weight: 600;"><?php echo e($stats['ayants_droit_en_attente']); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <a href="<?php echo e(route('admin.types-documents.index')); ?>" class="action-btn">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-cogs me-3" style="font-size: 1.2rem;"></i>
                            <strong>⚙️ Configuration documents</strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Activité récente -->
    <div class="col-lg-8">
        <div class="activity-card">
            <div class="activity-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: #000;">
                            <i class="fas fa-chart-line me-2"></i>📈 Activité Récente
                        </h5>
                        <p class="mb-0" style="color: #000; opacity: 0.9;">Monitoring en temps réel des connexions</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <ul class="dropdown-menu shadow-lg border-0">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-list me-2"></i>Toutes les activités</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-sign-in-alt me-2"></i>Connexions</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2"></i>Adhérents</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-alt me-2"></i>Documents</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr style="border-bottom: 2px solid #f8f9fa;">
                                <th class="fw-bold" style="color: #667eea; border: none;">Utilisateur</th>
                                <th class="fw-bold" style="color: #667eea; border: none;">Action</th>
                                <th class="fw-bold" style="color: #667eea; border: none;">Détails</th>
                                <th class="fw-bold" style="color: #667eea; border: none;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border: none; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(102, 126, 234, 0.05)'" onmouseout="this.style.background='transparent'">
                                <td style="border: none; padding: 1rem 0.75rem;">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="fw-bold" style="color: #2d3748;"><?php echo e($log->user->name); ?></div>
                                            <small style="color: #718096;"><?php echo e($log->user->email); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td style="border: none; padding: 1rem 0.75rem;">
                                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, 
                                        <?php echo e($log->action === 'login' ? '#11998e, #38ef7d' : ($log->action === 'logout' ? '#f59e0b, #f97316' : '#06b6d4, #3b82f6')); ?>

                                        ); color: white; font-weight: 600;">
                                        <i class="fas fa-<?php echo e($log->action === 'login' ? 'sign-in-alt' : ($log->action === 'logout' ? 'sign-out-alt' : 'info-circle')); ?> me-1"></i>
                                        <?php echo e(ucfirst($log->action)); ?>

                                    </span>
                                </td>
                                <td style="border: none; padding: 1rem 0.75rem;">
                                    <div style="color: #4a5568;">
                                        <div><i class="fas fa-globe me-2" style="color: #667eea;"></i><?php echo e($log->ip_address); ?></div>
                                        <?php if($log->user_agent): ?>
                                            <small style="color: #718096;"><i class="fas fa-desktop me-1"></i><?php echo e(Str::limit($log->user_agent, 25)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="border: none; padding: 1rem 0.75rem;">
                                    <div style="color: #4a5568;">
                                        <div class="fw-bold"><?php echo e($log->created_at->format('d/m/Y')); ?></div>
                                        <small style="color: #718096;"><?php echo e($log->created_at->format('H:i:s')); ?></small>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5" style="border: none;">
                                    <div class="text-center">
                                        <i class="fas fa-history" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem;"></i>
                                        <div class="h6" style="color: #a0aec0;">Aucune activité récente</div>
                                        <small style="color: #cbd5e0;">Les connexions apparaîtront ici</small>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($recentLogs->count() >= 10): ?>
                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('admin.logs.connexions')); ?>" class="btn btn-lg rounded-pill px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; font-weight: 600;">
                        <i class="fas fa-eye me-2"></i>👁️ Voir tous les logs
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques détaillées -->
<div class="row g-4 mt-4">
    <!-- Répartition par rôle -->
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="fw-bold mb-1" style="color: #000;">
                    <i class="fas fa-chart-pie me-2"></i>🍰 Répartition par Rôle
                </h5>
                <p class="mb-0" style="color: #000; opacity: 0.9;">Distribution des utilisateurs par type</p>
            </div>
            <div class="card-body p-4">
                <canvas id="roleChart" width="100%" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques des adhérents -->
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="fw-bold mb-1" style="color: #000;">
                    <i class="fas fa-chart-bar me-2"></i>📈 Statistiques Adhérents
                </h5>
                <p class="mb-0" style="color: #000; opacity: 0.9;">Répartition par statut de validation</p>
            </div>
            <div class="card-body p-4">
                <canvas id="adherentStatsChart" width="100%" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Alertes importantes modernes -->
<?php if(isset($stats['documents_en_attente']) && $stats['documents_en_attente'] > 0 || isset($stats['ayants_droit_en_attente']) && $stats['ayants_droit_en_attente'] > 0): ?>
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="alert-modern position-relative">
            <div class="position-relative">
                <h4 class="fw-bold mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>⚠️ Actions Requises - Validations en Attente
                </h4>
                <p class="mb-4" style="opacity: 0.9;">Des éléments nécessitent votre attention et validation immédiate</p>
                
                <div class="row g-4">
                    <?php if(isset($stats['documents_en_attente']) && $stats['documents_en_attente'] > 0): ?>
                    <div class="col-lg-6">
                        <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt me-3" style="font-size: 2rem;"></i>
                                    <div>
                                        <div class="h5 fw-bold mb-1"><?php echo e($stats['documents_en_attente']); ?></div>
                                        <small style="opacity: 0.8;">📄 Document(s) à valider</small>
                                    </div>
                                </div>
                                <a href="<?php echo e(route('admin.validation.documents')); ?>" class="btn btn-light btn-lg rounded-pill px-4">
                                    <i class="fas fa-check-circle me-2"></i>Valider Maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($stats['ayants_droit_en_attente']) && $stats['ayants_droit_en_attente'] > 0): ?>
                    <div class="col-lg-6">
                        <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users me-3" style="font-size: 2rem;"></i>
                                    <div>
                                        <div class="h5 fw-bold mb-1"><?php echo e($stats['ayants_droit_en_attente']); ?></div>
                                        <small style="opacity: 0.8;">👨‍👩‍👧‍👦 Ayant(s) droit à valider</small>
                                    </div>
                                </div>
                                <a href="<?php echo e(route('admin.validation.ayants-droit')); ?>" class="btn btn-light btn-lg rounded-pill px-4">
                                    <i class="fas fa-user-check me-2"></i>Traiter
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart des rôles avec nouvelles couleurs modernes
    const roleData = <?php echo json_encode($usersByRole, 15, 512) ?>;
    const ctx = document.getElementById('roleChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: roleData.map(item => item.role === 'admin' ? '👩‍💼 Administrateur' : 
                                    item.role === 'adherent' ? '👥 Adhérent' : 
                                    item.role === 'agent' ? '👨‍💼 Agent' : 
                                    item.role === 'chef_service' ? '👨‍💼 Chef Service' : item.role),
            datasets: [{
                data: roleData.map(item => item.total),
                backgroundColor: [
                    '#667eea', // Bleu moderne
                    '#11998e', // Vert émeraude
                    '#f59e0b', // Orange doré
                    '#06b6d4', // Cyan
                    '#ef4444', // Rouge moderne
                    '#a855f7'  // Violet
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        color: '#4a5568'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 10,
                    displayColors: false
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    });

    // Chart des statistiques adhérents moderne
    <?php if(isset($stats['adherents_actifs']) && isset($stats['adherents_inactifs']) && isset($stats['adherents_en_attente'])): ?>
    const adherentCtx = document.getElementById('adherentStatsChart').getContext('2d');
    new Chart(adherentCtx, {
        type: 'bar',
        data: {
            labels: ['✅ Actifs', '⏳ En attente', '❌ Inactifs'],
            datasets: [{
                label: 'Nombre d\'adhérents',
                data: [
                    <?php echo e($stats['adherents_actifs'] ?? 0); ?>,
                    <?php echo e($stats['adherents_en_attente'] ?? 0); ?>,
                    <?php echo e($stats['adherents_inactifs'] ?? 0); ?>

                ],
                backgroundColor: [
                    'rgba(17, 153, 142, 0.8)',   // Vert pour actifs
                    'rgba(245, 158, 11, 0.8)',   // Orange pour en attente
                    'rgba(239, 68, 68, 0.8)'     // Rouge pour inactifs
                ],
                borderColor: [
                    '#11998e',
                    '#f59e0b', 
                    '#ef4444'
                ],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    cornerRadius: 10,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#718096',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    }
                },
                x: {
                    ticks: {
                        color: '#4a5568',
                        font: {
                            weight: '600'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
    <?php endif; ?>

    // Animation d'entrée pour les cartes
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.modern-stat-card, .modern-action-card, .activity-card, .chart-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Animation pulse pour les badges d'alerte
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            if (badge.textContent.trim() !== '0') {
                badge.style.animation = 'pulse 2s infinite';
            }
        });
    });
    
    // Animation pulse CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backoffice.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/dashboard/admin.blade.php ENDPATH**/ ?>