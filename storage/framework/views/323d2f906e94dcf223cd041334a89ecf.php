


<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="resetPasswordForm">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>Réinitialiser le Mot de Passe
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention !</strong> Un nouveau mot de passe aléatoire sera généré.
                    </div>
                    
                    <p>Êtes-vous sûr de vouloir réinitialiser le mot de passe de <strong id="resetPasswordUserName"></strong> ?</p>
                    
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="send_email" id="sendEmailCheckbox" checked>
                        <label class="form-check-label" for="sendEmailCheckbox">
                            Envoyer le nouveau mot de passe par email (si disponible)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key me-1"></i>Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="suspendAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="suspendAccountForm">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-ban me-2"></i>Suspendre le Compte
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Action critique !</strong> L'utilisateur ne pourra plus se connecter.
                    </div>
                    
                    <p>Vous êtes sur le point de suspendre le compte de <strong id="suspendAccountUserName"></strong>.</p>
                    
                    <div class="mb-3">
                        <label for="suspensionReason" class="form-label fw-bold">Raison de la suspension <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="reason" id="suspensionReason" rows="3" 
                                  placeholder="Expliquez la raison de cette suspension..." required></textarea>
                        <small class="form-text text-muted">Cette raison sera enregistrée dans l'historique.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i>Suspendre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="activateAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="activateAccountForm">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>Réactiver le Compte
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>
                        L'utilisateur pourra se reconnecter après activation.
                    </div>
                    
                    <p>Confirmer la réactivation du compte de <strong id="activateAccountUserName"></strong> ?</p>
                    
                    <div class="card border-warning mt-3" id="suspensionInfo">
                        <div class="card-header bg-light">
                            <strong>Informations de suspension</strong>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Suspendu le:</strong> <span id="suspendedAt"></span></p>
                            <p class="mb-1"><strong>Raison:</strong> <span id="suspendedReason"></span></p>
                            <p class="mb-0"><strong>Par:</strong> <span id="suspendedBy"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i>Réactiver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="newPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Mot de Passe Réinitialisé
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <i class="fas fa-check me-2"></i>Le mot de passe a été réinitialisé avec succès !
                </div>
                
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-key me-2"></i>Nouveau Mot de Passe
                    </div>
                    <div class="card-body text-center">
                        <h3 class="font-monospace text-primary mb-3" id="displayNewPassword"></h3>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="copyPasswordBtn">
                            <i class="fas fa-copy me-1"></i>Copier
                        </button>
                    </div>
                </div>
                
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important :</strong> Notez ce mot de passe maintenant. Il ne sera plus affiché.
                    <span id="emailSentInfo" class="d-none">
                        <br>
                        <i class="fas fa-envelope me-1"></i>Un email a été envoyé à <strong id="userEmailSent"></strong>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-1"></i>J'ai noté le mot de passe
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modals
    const resetPasswordModal = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
    const suspendAccountModal = new bootstrap.Modal(document.getElementById('suspendAccountModal'));
    const activateAccountModal = new bootstrap.Modal(document.getElementById('activateAccountModal'));
    const newPasswordModal = new bootstrap.Modal(document.getElementById('newPasswordModal'));

    // Réinitialiser mot de passe
    window.openResetPasswordModal = function(userId, userName, isAdherent = false) {
        const form = document.getElementById('resetPasswordForm');
        const baseUrl = isAdherent ? '/admin/account-management/adherents/' : '/admin/account-management/users/';
        form.action = baseUrl + userId + '/reset-password';
        document.getElementById('resetPasswordUserName').textContent = userName;
        resetPasswordModal.show();
    };

    // Suspendre compte
    window.openSuspendModal = function(userId, userName, isAdherent = false) {
        const form = document.getElementById('suspendAccountForm');
        const baseUrl = isAdherent ? '/admin/account-management/adherents/' : '/admin/account-management/users/';
        form.action = baseUrl + userId + '/suspend';
        document.getElementById('suspendAccountUserName').textContent = userName;
        document.getElementById('suspensionReason').value = '';
        suspendAccountModal.show();
    };

    // Réactiver compte
    window.openActivateModal = function(userId, userName, suspendedAt, reason, suspendedByName, isAdherent = false) {
        const form = document.getElementById('activateAccountForm');
        const baseUrl = isAdherent ? '/admin/account-management/adherents/' : '/admin/account-management/users/';
        form.action = baseUrl + userId + '/activate';
        document.getElementById('activateAccountUserName').textContent = userName;
        document.getElementById('suspendedAt').textContent = suspendedAt || 'N/A';
        document.getElementById('suspendedReason').textContent = reason || 'Aucune raison fournie';
        document.getElementById('suspendedBy').textContent = suspendedByName || 'N/A';
        activateAccountModal.show();
    };

    // Afficher le nouveau mot de passe après réinitialisation
    <?php if(session('new_password')): ?>
        document.getElementById('displayNewPassword').textContent = '<?php echo e(session("new_password")); ?>';
        <?php if(session('user_email')): ?>
            document.getElementById('emailSentInfo').classList.remove('d-none');
            document.getElementById('userEmailSent').textContent = '<?php echo e(session("user_email")); ?>';
        <?php endif; ?>
        newPasswordModal.show();
    <?php endif; ?>

    // Copier le mot de passe
    document.getElementById('copyPasswordBtn')?.addEventListener('click', function() {
        const password = document.getElementById('displayNewPassword').textContent;
        navigator.clipboard.writeText(password).then(() => {
            this.innerHTML = '<i class="fas fa-check me-1"></i>Copié !';
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-success');
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy me-1"></i>Copier';
                this.classList.remove('btn-success');
                this.classList.add('btn-outline-primary');
            }, 2000);
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Mes Sites Web\projet sifcash final\sif-project\resources\views/backoffice/partials/account-management-modals.blade.php ENDPATH**/ ?>