<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AdherentController;
use App\Http\Controllers\AyantDroitController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TypeDocumentController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\AdhesionController;
use App\Http\Controllers\RenouvellementPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\DemandeRetraitController;
use App\Http\Controllers\PenaliteRetraitAnticipeController;
use App\Http\Controllers\LogConnexionController;
use App\Http\Controllers\EpargneController;
use App\Http\Middleware\AuditActions;
use App\Http\Controllers\AuditController;

// ==================== ROUTES PUBLIQUES ====================

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Pages institutionnelles
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/data-protection', function () {
    return view('data-protection');
})->name('data-protection');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/services', function () {
    return view('services');
})->name('services');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/conditions', function () {
    return view('conditions');
})->name('conditions');

// Route CGU (alias vers terms)
Route::get('/cgu', function () {
    return view('terms');
})->name('cgu');

// Route Conditions générales d'utilisation
Route::get('/terms-conditions', function () {
    return view('terms');
})->name('terms-conditions');

// Authentification générale
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Authentification adhérent (email ou téléphone)
Route::get('/adherent/login', [AuthController::class, 'showAdherentLoginForm'])->name('adherent.login');
Route::post('/adherent/login', [AuthController::class, 'adherentLogin']);

// Inscription adhérent
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// ==================== ROUTES PROTÉGÉES ====================


Route::middleware(['auth'])->group(function () {

    // ==================== DASHBOARDS ====================
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('admin.dashboard')
        ->middleware('role:admin,agent,chef_service,superviseur,test');

    Route::get('/agent/dashboard', [DashboardController::class, 'agentDashboard'])
        ->name('agent.dashboard')
        ->middleware('role:agent,chef_service,superviseur');

    Route::get('/adherent/dashboard', [DashboardController::class, 'adherentDashboard'])
        ->name('adherent.dashboard')
        ->middleware('role:adherent');

    // ==================== ESPACE ADHÉRENT ====================
    Route::middleware(['role:adherent'])->prefix('adherent')->name('adherent.')->group(function () {

        // Profil adhérent
        Route::get('/profile', [AdherentController::class, 'showProfile'])->name('profile');
        Route::get('/profile/edit', [AdherentController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [AdherentController::class, 'updateProfile'])->name('profile.update');
        
        // Changement de mot de passe
        Route::get('/password/edit', [\App\Http\Controllers\Adherent\PasswordController::class, 'edit'])->name('password.edit');
        Route::put('/password/update', [\App\Http\Controllers\Adherent\PasswordController::class, 'update'])->name('password.update');

        // Ayants droit
        Route::resource('ayants-droit', AyantDroitController::class)->except(['show']);

        // Documents
        Route::resource('documents', DocumentController::class)->except(['edit', 'update']);
        Route::get('documents/{document}/download/{type?}', [DocumentController::class, 'download'])->name('documents.download');

        // Inscription (workflow étape par étape)
        Route::get('/inscription', [InscriptionController::class, 'showInscriptionForm'])->name('inscription');
        Route::post('/inscription/profile', [InscriptionController::class, 'saveProfile'])->name('inscription.profile');
        Route::post('/inscription/ayants-droit', [InscriptionController::class, 'saveAyantsDroit'])->name('inscription.ayants-droit');
        Route::post('/inscription/documents', [InscriptionController::class, 'saveDocuments'])->name('inscription.documents');

        // Module 5 - Plans et Adhésions
        Route::get('plans', [PlanController::class, 'listForAdherent'])->name('plans.index');
        Route::get('plans/{plan}', [PlanController::class, 'showForAdherent'])->name('plans.show');

        // Adhésions (Espace adhérent)
        Route::get('adhesions', [AdhesionController::class, 'indexForAdherent'])->name('adhesions.index');
        Route::get('adhesions/create', [AdhesionController::class, 'createForAdherent'])->name('adhesions.create');
        Route::post('adhesions', [AdhesionController::class, 'storeForAdherent'])->name('adhesions.store');
        Route::get('adhesions/{adhesion}', [AdhesionController::class, 'showForAdherent'])->name('adhesions.show');

        // Crédits (gestion par l'adhérent)
        Route::get('credits/create', [CreditController::class, 'create'])
            ->middleware('can:create,App\\Models\\Credit')
            ->name('credits.create');
            
        Route::post('credits', [CreditController::class, 'store'])
            ->middleware(['throttle:5,1','can:create,App\\Models\\Credit'])
            ->name('credits.store');

        // Paiements de crédit (Espace adhérent) - AVANT les routes credits/{credit} pour éviter les conflits
        Route::get('credits/paiements', [CreditController::class, 'indexPaiementsForAdherent'])->name('credits.paiements.index');
        Route::get('credits/paiements/{paiement}', [CreditController::class, 'showPaiementForAdherent'])->middleware('can:view,paiement')->name('credits.paiements.show');
        Route::get('credits/paiements/{paiement}/preuves', [CreditController::class, 'showPreuvesForAdherent'])->middleware('can:viewPreuves,paiement')->name('credits.paiements.preuves');
        Route::get('credits/paiements/preuves/{preuve}/download', [CreditController::class, 'downloadPreuveForAdherent'])->middleware('can:downloadPreuve,paiement')->name('credits.paiements.download-preuve');

        // Crédits (lecture par l'adhérent)
        Route::get('credits', [CreditController::class, 'indexForAdherent'])
            ->name('credits.index');
        Route::get('credits/{credit}', [CreditController::class, 'showForAdherent'])
            ->middleware('can:view,credit')
            ->name('credits.show');
        Route::post('credits/{credit}/submit-payment', [CreditController::class, 'submitPaymentForAdherent'])
            ->middleware('can:view,credit')
            ->name('credits.submit-payment');

        // Paiements (Espace adhérent)
        Route::get('paiements', [PaiementController::class, 'indexForAdherent'])->name('paiements.index');
        Route::get('paiements/create', [PaiementController::class, 'createForAdherent'])->name('paiements.create');
        Route::post('paiements', [PaiementController::class, 'storeForAdherent'])->name('paiements.store');
        Route::get('paiements/{paiement}', [PaiementController::class, 'showForAdherent'])->middleware('can:view,paiement')->name('paiements.show');
        Route::get('paiements/{paiement}/edit', [PaiementController::class, 'editForAdherent'])->middleware('can:update,paiement')->name('paiements.edit');
        Route::put('paiements/{paiement}', [PaiementController::class, 'updateForAdherent'])->middleware('can:update,paiement')->name('paiements.update');
        Route::get('paiements/{paiement}/download', [PaiementController::class, 'downloadForAdherent'])->middleware('can:download,paiement')->name('paiements.download');

        // Retraits (Espace adhérent)
        Route::get('retraits', [DemandeRetraitController::class, 'indexForAdherent'])->name('retraits.index');
        Route::get('retraits/create', [DemandeRetraitController::class, 'createForAdherent'])->name('retraits.create');
        Route::post('retraits', [DemandeRetraitController::class, 'storeForAdherent'])->name('retraits.store');
        Route::get('retraits/{retrait}', [DemandeRetraitController::class, 'showForAdherent'])->middleware('can:view,retrait')->name('retraits.show');
        Route::delete('retraits/{retrait}', [DemandeRetraitController::class, 'destroyForAdherent'])->middleware('can:delete,retrait')->name('retraits.destroy');

        // Notifications adhérent
        Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'indexForAdherent'])
            ->name('notifications.index');
        Route::post('notifications/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markReadForAdherent'])
            ->name('notifications.mark-read');
        
        // Téléchargement du contrat d'adhésion
        Route::get('contrat/download', [AdherentController::class, 'downloadContract'])
            ->name('contrat.download');
    });

    // ==================== BACKOFFICE ADMINISTRATION ====================
    Route::middleware(['role:admin,agent,chef_service'])->prefix('admin')->name('admin.')->group(function () {

        // Recherche globale
        Route::get('search', [DashboardController::class, 'globalSearch'])->name('search');

        // Gestion des adhérents
        Route::resource('adherents', AdherentController::class);
        Route::post('adherents/{adherent}/activate', [AdherentController::class, 'activate'])->name('adherents.activate');
        Route::post('adherents/{adherent}/deactivate', [AdherentController::class, 'deactivate'])->name('adherents.deactivate');
        Route::get('adherents/{adherent}/contrat/download', [AdherentController::class, 'downloadContractAdmin'])->name('adherents.contrat.download');
        
        // Affectation agence/agent
        Route::get('adherents/{adherent}/affectation', [AdherentController::class, 'showAffectationForm'])->name('adherents.affectation');
        Route::post('adherents/{adherent}/affectation', [AdherentController::class, 'affecterAgenceAgent'])->name('adherents.affecter');
        Route::post('adherents/affectation-masse', [AdherentController::class, 'affectationMasse'])->name('adherents.affectation-masse');
        
        // Liste des adhérents par agent
        Route::get('agents/{agent}/adherents', [AdherentController::class, 'adherentsParAgent'])->name('agents.adherents');

        // Types de documents
        Route::resource('types-documents', TypeDocumentController::class);
        Route::post('types-documents/{typesDocument}/activate', [TypeDocumentController::class, 'activate'])->name('types-documents.activate');
        Route::post('types-documents/{typesDocument}/deactivate', [TypeDocumentController::class, 'deactivate'])->name('types-documents.deactivate');

        // Validation des documents
        Route::get('validation/documents', [DocumentController::class, 'pendingValidation'])->name('validation.documents');
        Route::post('documents/{document}/validate', [DocumentController::class, 'validateDocument'])->name('documents.validate');
        Route::post('documents/{document}/reject', [DocumentController::class, 'rejectDocument'])->name('documents.reject');
        // Téléchargement/Visionnage des documents (admin)
        Route::get('documents/{document}/download/{type?}', [DocumentController::class, 'download'])->name('documents.download');

        // Validation des ayants droit
        Route::get('validation/ayants-droit', [AyantDroitController::class, 'pendingValidation'])->name('validation.ayants-droit');
        Route::post('ayants-droit/{ayantDroit}/validate', [AyantDroitController::class, 'validateAyantDroit'])->name('ayants-droit.validate');
        Route::post('ayants-droit/{ayantDroit}/reject', [AyantDroitController::class, 'rejectAyantDroit'])->name('ayants-droit.reject');

        // Module 5 - Plans et Adhésions
        // Plans
        Route::resource('plans', PlanController::class);
        Route::post('plans/{plan}/activate', [PlanController::class, 'activate'])->name('plans.activate');
        Route::post('plans/{plan}/deactivate', [PlanController::class, 'deactivate'])->name('plans.deactivate');

        // Adhésions
        Route::resource('adhesions', AdhesionController::class);
        Route::post('adhesions/{adhesion}/activate', [AdhesionController::class, 'activate'])->name('adhesions.activate');
        Route::post('adhesions/{adhesion}/close', [AdhesionController::class, 'close'])->name('adhesions.close');
        Route::post('adhesions/{adhesion}/suspend', [AdhesionController::class, 'suspend'])->name('adhesions.suspend');
        Route::post('adhesions/{adhesion}/resume', [AdhesionController::class, 'resume'])->name('adhesions.resume');
        Route::post('adhesions/{adhesion}/renew', [AdhesionController::class, 'renew'])->name('adhesions.renew');

        // Renouvellements
        Route::resource('renouvellements', RenouvellementPlanController::class)->only(['index', 'show']);
        Route::get('adhesions/{adhesion}/renouvellements', [RenouvellementPlanController::class, 'forAdhesion'])->name('adhesions.renouvellements');
        Route::post('renouvellements/{renouvellementPlan}/complete', [RenouvellementPlanController::class, 'markAsCompleted'])->name('renouvellements.complete');
        Route::post('renouvellements/{renouvellementPlan}/cancel', [RenouvellementPlanController::class, 'cancel'])->name('renouvellements.cancel');

        // Crédit: génération d'échéancier
        Route::post('credits/{credit}/generate-schedule', [CreditController::class, 'generateSchedule'])
            ->middleware(['throttle:10,1','can:generateSchedule,credit'])
            ->name('credits.generate-schedule');

        // Gestion des crédits en retard (AVANT les routes avec paramètres dynamiques)
        Route::get('credits/en-retard', [CreditController::class, 'creditsEnRetard'])
            ->name('credits.retard')
            ->middleware('can:viewAny,App\\Models\\Credit');

        // Rapports de crédits (Admin uniquement)
        Route::get('credits/rapports', [CreditController::class, 'rapports'])
            ->name('credits.rapports')
            ->middleware('role:admin');

        // Crédit: actions agent/admin
        Route::get('credits', [CreditController::class, 'index'])->name('credits.index');
        Route::get('credits/{credit}', [CreditController::class, 'show'])
            ->middleware('can:view,credit')
            ->name('credits.show');
        Route::get('credits/{credit}/paiements', [CreditController::class, 'payments'])
            ->middleware('can:view,credit')
            ->name('credits.paiements');

        // Paiements (Backoffice admin)
        Route::get('paiements', [PaiementController::class, 'index'])->middleware('can:viewAny,App\\Models\\Paiement')->name('paiements.index');
        Route::get('paiements/{paiement}', [PaiementController::class, 'show'])->middleware('can:view,paiement')->name('paiements.show');
        Route::post('paiements/{paiement}/validate', [PaiementController::class, 'validatePayment'])->middleware('can:validate,paiement')->name('paiements.validate');
        Route::post('paiements/{paiement}/reject', [PaiementController::class, 'reject'])->middleware('can:reject,paiement')->name('paiements.reject');
        Route::get('paiements/{paiement}/download', [PaiementController::class, 'download'])->middleware('can:download,paiement')->name('paiements.download');

        // Gestion des épargnes
        Route::resource('epargnes', EpargneController::class);
        Route::get('epargnes/export', [EpargneController::class, 'export'])->name('epargnes.export');
        Route::post('epargnes/{epargne}/depot', [EpargneController::class, 'depot'])->name('epargnes.depot');
        Route::post('epargnes/{epargne}/retrait', [EpargneController::class, 'retrait'])->name('epargnes.retrait');
        Route::post('epargnes/{epargne}/calculer-interets', [EpargneController::class, 'calculerInterets'])->name('epargnes.calculer-interets');

        // Retraits (Backoffice admin)
        Route::get('retraits', [DemandeRetraitController::class, 'index'])->middleware('can:viewAny,App\\Models\\DemandeRetrait')->name('retraits.index');
        Route::get('retraits/{retrait}', [DemandeRetraitController::class, 'show'])->middleware('can:view,retrait')->name('retraits.show');
        Route::post('retraits/{retrait}/validate', [DemandeRetraitController::class, 'validateRetrait'])->middleware('can:validate,retrait')->name('retraits.validate');
        Route::post('retraits/{retrait}/reject', [DemandeRetraitController::class, 'reject'])->middleware('can:reject,retrait')->name('retraits.reject');
        Route::post('retraits/{retrait}/process', [DemandeRetraitController::class, 'process'])->middleware('can:process,retrait')->name('retraits.process');

        // Paiements de crédit (Backoffice admin)
        Route::get('credits/paiements', [CreditController::class, 'indexPaiements'])->middleware('can:viewAny,App\\Models\\PaiementCredit')->name('credits.paiements.index');
        Route::get('credits/paiements/{paiement}', [CreditController::class, 'showPaiement'])->middleware('can:view,paiement')->name('credits.paiements.show');
        Route::get('credits/paiements/{paiement}/preuves', [CreditController::class, 'showPreuves'])->middleware('can:viewPreuves,paiement')->name('credits.paiements.preuves');
        Route::get('credits/paiements/preuves/{preuve}/download', [CreditController::class, 'downloadPreuve'])->middleware('can:downloadPreuve,paiement')->name('credits.paiements.download-preuve');
        Route::get('credits/{credit}/echeances', [CreditController::class, 'getEcheances'])->middleware('can:view,credit')->name('credits.echeances');

        // Notifications admin
        Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
            ->name('notifications.index');

        // Audits admin
        Route::get('audits', [AuditController::class, 'index'])->name('audits.index');
        Route::post('credits/{credit}/approve', [CreditController::class, 'approve'])
            ->middleware(['throttle:10,1','can:approve,credit'])
            ->name('credits.approve');
        Route::post('credits/{credit}/reject', [CreditController::class, 'reject'])
            ->middleware(['throttle:10,1','can:reject,credit'])
            ->name('credits.reject');
        Route::post('credits/{credit}/contract', [CreditController::class, 'contract'])
            ->middleware(['throttle:10,1','can:contract,credit'])
            ->name('credits.contract');
        Route::post('credits/{credit}/record-payment', [CreditController::class, 'recordPayment'])
            ->middleware(['throttle:20,1','can:recordPayment,credit'])
            ->name('credits.record-payment');

        // Routes d'export pour les crédits
        Route::get('credits/{credit}/export/contract', [CreditController::class, 'exportContract'])
            ->middleware('can:view,credit')
            ->name('credits.export-contract');
        Route::get('credits/{credit}/export/schedule', [CreditController::class, 'exportSchedule'])
            ->middleware('can:view,credit')
            ->name('credits.export-schedule');
        Route::get('credits/{credit}/export/payments', [CreditController::class, 'exportPayments'])
            ->middleware('can:view,credit')
            ->name('credits.export-payments');

        // Échéances de crédit
        Route::get('credits/{credit}/echeances', [\App\Http\Controllers\EcheanceCreditController::class, 'indexByCredit'])
            ->middleware('can:view,credit')
            ->name('credits.echeances.index');
        Route::get('echeances/{echeanceCredit}', [\App\Http\Controllers\EcheanceCreditController::class, 'show'])
            ->middleware('can:view,echeanceCredit')
            ->name('echeances.show');
        Route::patch('echeances/{echeanceCredit}', [\App\Http\Controllers\EcheanceCreditController::class, 'update'])
            ->middleware('can:update,echeanceCredit')
            ->name('echeances.update');

        // Conditions d'éligibilité crédit
        Route::get('credits/eligibilites', [\App\Http\Controllers\ConditionEligibiliteCreditController::class, 'index'])
            ->middleware('can:viewAny,App\\Models\\ConditionEligibiliteCredit')
            ->name('credits.eligibilites.index');
        Route::post('credits/eligibilites', [\App\Http\Controllers\ConditionEligibiliteCreditController::class, 'store'])
            ->middleware('can:create,App\\Models\\ConditionEligibiliteCredit')
            ->name('credits.eligibilites.store');
        Route::get('credits/eligibilites/{conditionEligibiliteCredit}', [\App\Http\Controllers\ConditionEligibiliteCreditController::class, 'show'])
            ->middleware('can:view,conditionEligibiliteCredit')
            ->name('credits.eligibilites.show');
        Route::patch('credits/eligibilites/{conditionEligibiliteCredit}', [\App\Http\Controllers\ConditionEligibiliteCreditController::class, 'update'])
            ->middleware('can:update,conditionEligibiliteCredit')
            ->name('credits.eligibilites.update');
        Route::post('credits/eligibilites/{conditionEligibiliteCredit}/toggle-active', [\App\Http\Controllers\ConditionEligibiliteCreditController::class, 'toggleActive'])
            ->middleware('can:toggleActive,conditionEligibiliteCredit')
            ->name('credits.eligibilites.toggle-active');

        // Gestion des agences (Admin seulement)
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('agences', AgenceController::class);
            Route::post('agences/{agence}/toggle-status', [AgenceController::class, 'toggleStatus'])
                ->name('agences.toggle-status');
        });

        // Gestion des rôles et permissions (Admin seulement)
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
        });

        // Gestion des utilisateurs
        Route::middleware(['role:admin,chef_service'])->group(function () {
            Route::resource('users', UserController::class);
            Route::get('users/{user}/logs', [UserController::class, 'logs'])->name('users.logs');
            Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        // Gestion des pénalités de retrait anticipé
        Route::get('penalites', [PenaliteRetraitAnticipeController::class, 'index'])
            ->name('penalites.index');
        Route::get('penalites/{penalite}/edit', [PenaliteRetraitAnticipeController::class, 'edit'])
            ->name('penalites.edit');
        Route::put('penalites/{penalite}', [PenaliteRetraitAnticipeController::class, 'update'])
            ->name('penalites.update');
        Route::patch('penalites/{penalite}/activate', [PenaliteRetraitAnticipeController::class, 'activate'])
            ->name('penalites.activate');
        Route::patch('penalites/{penalite}/deactivate', [PenaliteRetraitAnticipeController::class, 'deactivate'])
            ->name('penalites.deactivate');
        
        // Journal d'audit
        Route::get('audit', [AuditController::class, 'index'])->name('audit.index');

        // Logs de connexion
        Route::get('logs/connexions', [LogConnexionController::class, 'index'])->name('logs.connexions');
        Route::get('logs/connexions/export', [LogConnexionController::class, 'export'])->name('logs.connexions.export');
        
        // Gestion des affectations
        Route::prefix('affectations')->name('affectations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AffectationController::class, 'index'])->name('index');
            Route::post('/affecter-masse', [\App\Http\Controllers\AffectationController::class, 'affecterMasse'])->name('affecter-masse');
            Route::post('/retirer-agent', [\App\Http\Controllers\AffectationController::class, 'retirerAgent'])->name('retirer-agent');
            Route::post('/definir-agent-principal', [\App\Http\Controllers\AffectationController::class, 'definirAgentPrincipal'])->name('definir-agent-principal');
            
            Route::get('/par-agence/{agenceId?}', [\App\Http\Controllers\AffectationController::class, 'parAgence'])->name('par-agence');
            Route::get('/par-agent/{agentId?}', [\App\Http\Controllers\AffectationController::class, 'parAgent'])->name('par-agent');
            Route::get('/non-affectes', [\App\Http\Controllers\AffectationController::class, 'nonAffectes'])->name('non-affectes');
        });

        // Gestion des comptes utilisateurs (Admin seulement)
        Route::middleware(['role:admin'])->prefix('account-management')->name('account.')->group(function () {
            // Utilisateurs
            Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\AccountManagementController::class, 'resetUserPassword'])
                ->name('users.reset-password');
            Route::post('users/{user}/suspend', [\App\Http\Controllers\Admin\AccountManagementController::class, 'suspendUser'])
                ->name('users.suspend');
            Route::post('users/{user}/activate', [\App\Http\Controllers\Admin\AccountManagementController::class, 'activateUser'])
                ->name('users.activate');

            // Adhérents
            Route::post('adherents/{adherent}/reset-password', [\App\Http\Controllers\Admin\AccountManagementController::class, 'resetAdherentPassword'])
                ->name('adherents.reset-password');
            Route::post('adherents/{adherent}/suspend', [\App\Http\Controllers\Admin\AccountManagementController::class, 'suspendAdherent'])
                ->name('adherents.suspend');
            Route::post('adherents/{adherent}/activate', [\App\Http\Controllers\Admin\AccountManagementController::class, 'activateAdherent'])
                ->name('adherents.activate');
        });
    });
});
