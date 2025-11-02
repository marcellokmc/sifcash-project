

<?php $__env->startSection('title', 'Tableau de Bord Adhérent'); ?>


<?php $__env->startSection('content'); ?>
    <!-- Header Dynamique -->
    <div class="sif-fade-in position-relative mb-4">
        <div class="card border-0 sif-rainbow-bg text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="position-absolute top-0 end-0 opacity-25">
                    <i class="fas fa-university" style="font-size: 8rem;"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center position-relative">
                    <div>
                        <h1 class="display-5 fw-bold mb-2 text-dark">
                            🎆 Bienvenue, <?php echo e($user->prenom ?? 'Adhérent'); ?> !
                        </h1>
                        <p class="lead mb-0 text-dark">
                            <i class="fas fa-chart-line me-2"></i>
                            Votre tableau de bord SIFCash-Burkina - <?php echo e(now()->format('d/m/Y')); ?>

                        </p>
                        <?php if($adherent && $adherent->isActif()): ?>
                            <div class="mt-2">
                                <span class="badge bg-success bg-opacity-75 px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Compte Actif
                                </span>
                            </div>
                        <?php elseif($adherent && $adherent->isEnAttente()): ?>
                            <div class="mt-2">
                                <span class="badge bg-warning bg-opacity-75 px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>En Attente de Validation
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button class="btn btn-dark btn-lg sif-pulse" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                        <a href="<?php echo e(route('adherent.profile')); ?>" class="btn btn-outline-dark btn-lg">
                            <i class="fas fa-user-edit me-2"></i>Mon Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Principales -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4 sif-fade-in">
            <div class="card sif-stat-card h-100 sif-float">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="sif-stat-icon sif-shimmer">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-primary">
                                <?php echo e(number_format($adherent->solde_epargne ?? 0, 0, ',', ' ')); ?>

                            </h2>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                FCFA 💰
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-1 fw-bold text-primary">
                            <i class="fas fa-piggy-bank me-2 text-warning"></i>
                            Mon Solde d'Épargne
                        </h6>
                        <small class="sif-text-muted">
                            <i class="fas fa-chart-line me-1 text-success"></i>
                            Solde actuel de votre épargne
                        </small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 sif-fade-in-delay-1">
            <div class="card sif-stat-card success h-100 sif-bounce">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="sif-stat-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-success">
                                <?php echo e($adherent && $adherent->credits ? $adherent->credits->where('statut', 'approuvé')->count() : 0); ?>

                            </h2>
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">
                                crédits 💳
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-1 fw-bold text-success">
                            <i class="fas fa-hand-holding-usd me-2 text-warning"></i>
                            Crédits Actifs
                        </h6>
                        <small class="sif-text-muted">
                            <i class="fas fa-check-circle me-1 text-success"></i>
                            Nombre de crédits en cours
                        </small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: <?php echo e($adherent && $adherent->credits && $adherent->credits->where('statut', 'approuvé')->count() > 0 ? '100' : '0'); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 sif-fade-in-delay-2">
            <div class="card sif-stat-card info h-100 sif-float" style="animation-delay: 1s;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="sif-stat-icon sif-pulse">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-info">
                                <?php echo e($adherent && $adherent->ayantsDroit ? $adherent->ayantsDroit->count() : 0); ?>

                            </h2>
                            <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                personnes 👥
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-1 fw-bold text-info">
                            <i class="fas fa-heart me-2 text-danger"></i>
                            Ayants Droit
                        </h6>
                        <small class="sif-text-muted">
                            <i class="fas fa-shield-alt me-1 text-info"></i>
                            Bénéficiaires de votre compte
                        </small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-info" style="width: <?php echo e($adherent && $adherent->ayantsDroit && $adherent->ayantsDroit->count() > 0 ? min($adherent->ayantsDroit->count() * 25, 100) : '5'); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 sif-fade-in-delay-3">
            <div class="card sif-stat-card warning h-100 sif-shimmer">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="sif-stat-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="text-end">
                            <h2 class="mb-0 fw-bold text-warning">
                                <?php echo e($validatedRequiredCount); ?>/<?php echo e($requiredTypesCount); ?>

                            </h2>
                            <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">
                                documents 📄
                            </span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-1 fw-bold text-warning">
                            <i class="fas fa-certificate me-2 text-success"></i>
                            Documents Validés
                        </h6>
                        <small class="sif-text-muted">
                            <i class="fas fa-upload me-1 text-warning"></i>
                            Documents obligatoires soumis
                        </small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: <?php echo e($requiredTypesCount > 0 ? ($validatedRequiredCount / $requiredTypesCount) * 100 : '0'); ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card sif-card">
                <div class="sif-card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-lightning-bolt me-2"></i>Actions Rapides
                    </h5>
                    <small class="opacity-75">Accédez rapidement à vos services favoris</small>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('adherent.paiements.create')); ?>" class="sif-quick-action text-center position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-10"></div>
                                <i class="fas fa-plus-circle text-success sif-bounce" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-1 fw-bold text-success">
                                    💰 Versement
                                </h5>
                                <small class="text-success-emphasis">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    Effectuer un versement
                                </small>
                                <div class="mt-2">
                                    <span class="badge bg-success bg-opacity-25 text-success px-3 py-1">
                                        Rapide & Sécurisé
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('adherent.credits.create')); ?>" class="sif-quick-action text-center position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-warning bg-opacity-10"></div>
                                <i class="fas fa-hand-holding-usd text-warning sif-float" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-1 fw-bold text-warning">
                                    🏦 Crédit
                                </h5>
                                <small class="text-warning-emphasis">
                                    <i class="fas fa-handshake me-1"></i>
                                    Demander un prêt
                                </small>
                                <div class="mt-2">
                                    <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-1">
                                        Taux Avantageux
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('adherent.retraits.create')); ?>" class="sif-quick-action text-center position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-info bg-opacity-10"></div>
                                <i class="fas fa-money-bill-wave text-info sif-pulse" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-1 fw-bold text-info">
                                    💵 Retrait
                                </h5>
                                <small class="text-info-emphasis">
                                    <i class="fas fa-arrow-down me-1"></i>
                                    Demander un retrait
                                </small>
                                <div class="mt-2">
                                    <span class="badge bg-info bg-opacity-25 text-info px-3 py-1">
                                        Flexible
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo e(route('adherent.ayants-droit.create')); ?>" class="sif-quick-action text-center position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-purple bg-opacity-10" style="background-color: rgba(168, 85, 247, 0.1);"></div>
                                <i class="fas fa-user-plus sif-shimmer" style="color: #a855f7; font-size: 3rem;"></i>
                                <h5 class="mt-3 mb-1 fw-bold" style="color: #a855f7;">
                                    👨‍👩‍👧‍👦 Ayant Droit
                                </h5>
                                <small style="color: #7c3aed;">
                                    <i class="fas fa-heart me-1"></i>
                                    Ajouter bénéficiaire
                                </small>
                                <div class="mt-2">
                                    <span class="badge px-3 py-1" style="background-color: rgba(168, 85, 247, 0.25); color: #a855f7;">
                                        Protection Famille
                                    </span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <?php if($adherent && $adherent->isActif()): ?>
                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="<?php echo e(route('adherent.contrat.download')); ?>" class="btn btn-success w-100">
                                    <i class="fas fa-file-pdf me-2"></i>Télécharger mon contrat
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo e(route('adherent.adhesions.index')); ?>" class="btn btn-info w-100">
                                    <i class="fas fa-handshake me-2"></i>Mes Adhésions
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card sif-card sif-card-info">
                <div class="sif-card-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2"></i>Dernières Activités
                    </h5>
                    <small class="opacity-75">Vos 2 actions les plus récentes</small>
                </div>
                <div class="card-body p-4">
                    <?php
                        // Collecte de toutes les activités récentes de l'adhérent
                        $activites = collect();
                        
                        if($adherent) {
                            // Paiements récents
                            $adherent->paiements()->latest()->limit(3)->get()->each(function($paiement) use ($activites) {
                                $activites->push([
                                    'type' => 'paiement',
                                    'action' => 'Versement effectué',
                                    'description' => number_format($paiement->montant, 0, ',', ' ') . ' FCFA',
                                    'icon' => 'fas fa-plus-circle',
                                    'color' => 'success',
                                    'date' => $paiement->created_at
                                ]);
                            });
                            
                            // Crédits récents
                            $adherent->credits()->latest()->limit(3)->get()->each(function($credit) use ($activites) {
                                $status_text = [
                                    'en_attente' => 'Demande de crédit soumise',
                                    'approuvé' => 'Crédit approuvé',
                                    'rejeté' => 'Demande rejectée'
                                ];
                                
                                $activites->push([
                                    'type' => 'credit',
                                    'action' => $status_text[$credit->statut] ?? 'Crédit mis à jour',
                                    'description' => number_format($credit->montant_demande, 0, ',', ' ') . ' FCFA',
                                    'icon' => 'fas fa-hand-holding-usd',
                                    'color' => $credit->statut === 'approuvé' ? 'success' : ($credit->statut === 'rejeté' ? 'danger' : 'warning'),
                                    'date' => $credit->updated_at
                                ]);
                            });
                            
                            // Retraits récents
                            $adherent->demandeRetraits()->latest()->limit(2)->get()->each(function($retrait) use ($activites) {
                                $status_text = [
                                    'en_attente' => 'Demande de retrait soumise',
                                    'approuvé' => 'Retrait approuvé',
                                    'rejeté' => 'Retrait rejeté'
                                ];
                                
                                $activites->push([
                                    'type' => 'retrait',
                                    'action' => $status_text[$retrait->statut] ?? 'Retrait mis à jour',
                                    'description' => number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA',
                                    'icon' => 'fas fa-money-bill-wave',
                                    'color' => $retrait->statut === 'approuvé' ? 'info' : ($retrait->statut === 'rejeté' ? 'danger' : 'warning'),
                                    'date' => $retrait->updated_at
                                ]);
                            });
                            
                            // Documents récents
                            $adherent->documents()->latest()->limit(2)->get()->each(function($document) use ($activites) {
                                $status_text = [
                                    'soumis' => 'Document soumis',
                                    'validé' => 'Document validé',
                                    'rejeté' => 'Document rejeté'
                                ];
                                
                                $activites->push([
                                    'type' => 'document',
                                    'action' => $status_text[$document->statut] ?? 'Document mis à jour',
                                    'description' => $document->typeDocument->nom ?? 'Document',
                                    'icon' => 'fas fa-file-alt',
                                    'color' => $document->statut === 'validé' ? 'success' : ($document->statut === 'rejeté' ? 'danger' : 'info'),
                                    'date' => $document->updated_at
                                ]);
                            });
                            
                            // Ayants droit récents
                            $adherent->ayantsDroit()->latest()->limit(2)->get()->each(function($ayantDroit) use ($activites) {
                                $status_text = [
                                    'en_attente' => 'Ayant droit ajouté',
                                    'validé' => 'Ayant droit validé',
                                    'rejeté' => 'Ayant droit rejeté'
                                ];
                                
                                $activites->push([
                                    'type' => 'ayant_droit',
                                    'action' => $status_text[$ayantDroit->statut_validation] ?? 'Ayant droit mis à jour',
                                    'description' => $ayantDroit->prenom . ' ' . $ayantDroit->nom,
                                    'icon' => 'fas fa-user-plus',
                                    'color' => $ayantDroit->statut_validation === 'validé' ? 'success' : ($ayantDroit->statut_validation === 'rejeté' ? 'danger' : 'warning'),
                                    'date' => $ayantDroit->updated_at
                                ]);
                            });
                            
                            // Adhésions récentes
                            $adherent->adhesions()->latest()->limit(2)->get()->each(function($adhesion) use ($activites) {
                                $activites->push([
                                    'type' => 'adhesion',
                                    'action' => 'Nouvelle adhésion',
                                    'description' => $adhesion->plan->nom ?? 'Plan d’épargne',
                                    'icon' => 'fas fa-handshake',
                                    'color' => 'primary',
                                    'date' => $adhesion->created_at
                                ]);
                            });
                        }
                        
                        // Trier les activités par date et prendre les 2 plus récentes
                        $activitesRecentes = $activites->sortByDesc('date')->take(2);
                    ?>
                    
                    <?php if($activitesRecentes->count() > 0): ?>
                        <div class="timeline">
                            <?php $__currentLoopData = $activitesRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-start mb-3 <?php echo e(!$loop->last ? 'pb-3 border-bottom border-light' : ''); ?>">
                                    <div class="bg-<?php echo e($activite['color']); ?> rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="<?php echo e($activite['icon']); ?> text-white" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <h6 class="mb-1 fw-semibold"><?php echo e($activite['action']); ?></h6>
                                        <p class="mb-1 small text-muted"><?php echo e($activite['description']); ?></p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo e($activite['date']->diffForHumans()); ?>

                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <div class="text-center pt-3 mt-2 border-top">
                                <a href="<?php echo e(route('adherent.notifications.index')); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-list me-1"></i>Voir toutes les activités
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x sif-text-muted mb-3"></i>
                            <p class="sif-text-muted mb-2">Aucune activité récente</p>
                            <small class="sif-text-muted">Vos 2 dernières actions apparaîtront ici</small>
                            <div class="mt-3">
                                <a href="<?php echo e(route('adherent.paiements.create')); ?>" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-plus me-1"></i>Premier versement
                                </a>
                                <a href="<?php echo e(route('adherent.notifications.index')); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-list me-1"></i>Historique
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouvelles sections informatives et colorées -->
    <div class="row mb-4">
        <!-- À Propos SIF -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden sif-float">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0.1;"></div>
                <div class="card-body p-4 text-center position-relative">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-info-circle text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: #667eea;">🏢 À Propos de SIF</h5>
                    <p class="text-muted small mb-3">Découvrez l'histoire, la mission et les valeurs de votre société d'investissement et de financement au Burkina Faso.</p>
                    <div class="d-flex flex-wrap gap-1 mb-3 justify-content-center">
                        <span class="badge" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">💼 Institution</span>
                        <span class="badge" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">🏛️ Fiable</span>
                        <span class="badge" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">🇧🇫 Local</span>
                    </div>
                    <a href="<?php echo e(route('about')); ?>" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-arrow-right me-1"></i>En savoir plus
                    </a>
                </div>
            </div>
        </div>

        <!-- Confidentialité et Sécurité -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden sif-bounce">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); opacity: 0.1;"></div>
                <div class="card-body p-4 text-center position-relative">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <i class="fas fa-shield-alt text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: #11998e;">🔒 Confidentialité</h5>
                    <p class="text-muted small mb-3">Vos données personnelles et financières sont protégées par les plus hauts standards de sécurité numérique.</p>
                    <div class="d-flex flex-wrap gap-1 mb-3 justify-content-center">
                        <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">🛡️ Sécurisé</span>
                        <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">🔐 Crypté</span>
                        <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">✅ RGPD</span>
                    </div>
                    <a href="<?php echo e(route('privacy')); ?>" class="btn btn-outline-success btn-sm rounded-pill px-4">
                        <i class="fas fa-lock me-1"></i>Politique
                    </a>
                </div>
            </div>
        </div>

        <!-- Support et Aide -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden sif-pulse">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); opacity: 0.1;"></div>
                <div class="card-body p-4 text-center position-relative">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                            <i class="fas fa-headset text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3" style="color: #ff6b6b;">📞 Support 24/7</h5>
                    <p class="text-muted small mb-3">Notre équipe d'experts est disponible pour vous accompagner dans toutes vos démarches financières.</p>
                    <div class="d-flex flex-wrap gap-1 mb-3 justify-content-center">
                        <span class="badge" style="background: rgba(255, 107, 107, 0.1); color: #ff6b6b;">📱 Réactif</span>
                        <span class="badge" style="background: rgba(255, 107, 107, 0.1); color: #ff6b6b;">💬 Chat</span>
                        <span class="badge" style="background: rgba(255, 107, 107, 0.1); color: #ff6b6b;">📧 Email</span>
                    </div>
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-4" onclick="alert('📞 Contactez-nous au : +226 25 XX XX XX\n📧 Email : support@sif-burkina.bf\n💬 Chat en ligne disponible')">
                        <i class="fas fa-phone me-1"></i>Contacter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Actualités et Conseils -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); opacity: 0.1;"></div>
                <div class="card-header border-0 position-relative" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-newspaper me-2"></i>💡 Conseils Financiers du Jour
                    </h5>
                    <small class="text-dark">Optimisez vos finances avec nos conseils d'experts</small>
                </div>
                <div class="card-body p-4 position-relative">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="fas fa-lightbulb text-white" style="font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">📊 Diversifiez vos épargnes</h6>
                                    <p class="small text-muted mb-0">Ne mettez pas tous vos œufs dans le même panier. Répartissez vos économies sur différents plans d'épargne.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-info rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="fas fa-chart-line text-white" style="font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">💰 Épargnez régulièrement</h6>
                                    <p class="small text-muted mb-0">Même de petits versements réguliers peuvent constituer une épargne importante sur le long terme.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-success rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="fas fa-shield-alt text-white" style="font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">🛡️ Constituez un fonds d'urgence</h6>
                                    <p class="small text-muted mb-0">Ayez toujours 3 à 6 mois de charges courantes de côté pour les imprévus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary rounded-circle p-2 me-3 flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="fas fa-target text-white" style="font-size: 0.9rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">🎯 Fixez-vous des objectifs</h6>
                                    <p class="small text-muted mb-0">Définissez des objectifs financiers clairs et mesurables pour rester motivé.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques SIF -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm position-relative overflow-hidden h-100">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0.1;"></div>
                <div class="card-header border-0 position-relative text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-chart-bar me-2"></i>📈 SIF en Chiffres
                    </h5>
                    <small class="text-white opacity-75">Nos performances 2024</small>
                </div>
                <div class="card-body p-4 position-relative">
                    <div class="text-center mb-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3">
                                    <h4 class="fw-bold text-primary mb-1">2,540</h4>
                                    <small class="text-muted">👥 Adhérents actifs</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3">
                                    <h4 class="fw-bold text-success mb-1">98%</h4>
                                    <small class="text-muted">😊 Satisfaction client</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3">
                                    <h4 class="fw-bold text-warning mb-1">15M</h4>
                                    <small class="text-muted">💰 FCFA gérés</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3">
                                    <h4 class="fw-bold text-info mb-1">12</h4>
                                    <small class="text-muted">🏢 15 ans d'expérience</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-award text-warning me-1"></i>
                            Certifiée ISO 27001 pour la sécurité
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Liens Utiles -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); opacity: 0.1;"></div>
                <div class="card-header border-0 position-relative text-center" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-link me-2"></i>🔗 Liens Utiles
                    </h5>
                    <small class="text-dark opacity-75">Accès rapide aux informations importantes</small>
                </div>
                <div class="card-body p-4 position-relative">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('cgu')); ?>" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="fas fa-file-contract text-primary mb-2" style="font-size: 1.5rem;"></i>
                                <small class="fw-bold text-dark">📋 CGU</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('data-protection')); ?>" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="fas fa-database text-success mb-2" style="font-size: 1.5rem;"></i>
                                <small class="fw-bold text-dark">🗃️ Protection Données</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('about')); ?>" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="fas fa-building text-info mb-2" style="font-size: 1.5rem;"></i>
                                <small class="fw-bold text-dark">🏢 À Propos</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?php echo e(route('privacy')); ?>" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none">
                                <i class="fas fa-user-shield text-warning mb-2" style="font-size: 1.5rem;"></i>
                                <small class="fw-bold text-dark">🔒 Confidentialité</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <button class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" onclick="alert('📧 Email: contact@sif-burkina.bf\n📞 Tél: +226 25 XX XX XX\n📍 Adresse: Ouagadougou, Burkina Faso')">
                                <i class="fas fa-envelope text-danger mb-2" style="font-size: 1.5rem;"></i>
                                <small class="fw-bold text-dark">📧 Contact</small>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <button class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" onclick="alert('🌟 Merci de faire confiance à SIFCash-Burkina !\n\n🏆 Votre partenaire financier de confiance depuis 2012\n💪 Ensemble, construisons votre avenir financier')">
                                <i class="fas fa-heart text-pink mb-2" style="font-size: 1.5rem; color: #e91e63;"></i>
                                <small class="fw-bold text-dark">❤️ Merci</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progression du Profil -->
    <?php if($adherent): ?>
    <div class="row">
        <div class="col-12">
            <div class="card sif-card sif-card-success">
                <div class="sif-card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-line me-2"></i>Progression de votre profil
                        </h5>
                        <small class="opacity-75">Complétez votre profil pour accéder à tous nos services</small>
                    </div>
                    <div class="text-end">
                        <h3 class="mb-0 text-white fw-bold"><?php echo e(round($completionProfil)); ?>%</h3>
                        <small class="opacity-75">completé</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Barre de Progression -->
                    <div class="sif-progress mb-4">
                        <div class="sif-progress-bar success" 
                             style="width: <?php echo e($completionProfil); ?>%" 
                             role="progressbar" 
                             aria-valuenow="<?php echo e($completionProfil); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                    
                    <div class="row">
                        <!-- Informations manquantes -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                À compléter :
                            </h6>
                            <div class="list-group list-group-flush">
                                <?php if(!$adherent->date_naissance): ?>
                                    <div class="list-group-item px-0 py-2 border-0">
                                        <i class="fas fa-circle-notch text-warning me-2"></i>
                                        Date de naissance
                                    </div>
                                <?php endif; ?>
                                <?php if(!$adherent->adresse): ?>
                                    <div class="list-group-item px-0 py-2 border-0">
                                        <i class="fas fa-circle-notch text-warning me-2"></i>
                                        Adresse complète
                                    </div>
                                <?php endif; ?>
                                <?php if(!$adherent->telephone): ?>
                                    <div class="list-group-item px-0 py-2 border-0">
                                        <i class="fas fa-circle-notch text-warning me-2"></i>
                                        Numéro de téléphone
                                    </div>
                                <?php endif; ?>
                                <?php if($validatedRequiredCount < $requiredTypesCount): ?>
                                    <div class="list-group-item px-0 py-2 border-0">
                                        <i class="fas fa-circle-notch text-warning me-2"></i>
                                        Documents obligatoires (<?php echo e($requiredTypesCount - $validatedRequiredCount); ?> manquants)
                                    </div>
                                <?php endif; ?>
                                <?php if($completionProfil >= 100): ?>
                                    <div class="list-group-item px-0 py-2 border-0 text-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Profil complet ! 🎉
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-md-6">
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <h6 class="fw-bold mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    Avantages d'un profil complet
                                </h6>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Accès à tous les services</li>
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Montants de crédit plus élevés</li>
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i>Process simplifivé</li>
                                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Support prioritaire</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('adherent.profile.edit')); ?>" class="btn btn-primary">
                                    <i class="fas fa-user-edit me-2"></i>Compléter mon profil
                                </a>
                                <?php if($validatedRequiredCount < $requiredTypesCount): ?>
                                    <a href="<?php echo e(route('adherent.documents.create')); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-upload me-2"></i>Ajouter des documents
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adherent-modern', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/dashboard/index.blade.php ENDPATH**/ ?>