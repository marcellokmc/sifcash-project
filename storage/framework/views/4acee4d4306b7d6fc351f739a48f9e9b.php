

<?php $__env->startSection('title', 'Changer mon mot de passe'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- En-tête -->
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-key fa-2x"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-1">Changer mon mot de passe</h2>
                    <p class="text-muted mb-0">Modifiez votre mot de passe de connexion</p>
                </div>
            </div>

            <!-- Messages de succès/erreur -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Carte principale -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Nouveau mot de passe
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Alerte informative -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Information :</strong> Vous pouvez changer votre mot de passe sans avoir à saisir l'ancien. 
                        Assurez-vous de choisir un mot de passe sécurisé.
                    </div>

                    <form method="POST" action="<?php echo e(route('adherent.password.update')); ?>" id="passwordForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Nouveau mot de passe -->
                        <div class="mb-4">
                            <label for="new_password" class="form-label fw-bold">
                                <i class="fas fa-key text-primary me-2"></i>Nouveau mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="new_password" 
                                       name="new_password" 
                                       required
                                       minlength="8"
                                       placeholder="Entrez votre nouveau mot de passe">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Minimum 8 caractères
                            </small>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-check-circle text-success me-2"></i>Confirmer le mot de passe
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       required
                                       minlength="8"
                                       placeholder="Confirmez votre nouveau mot de passe">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fas fa-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Retapez le même mot de passe
                            </small>
                        </div>

                        <!-- Indicateur de force du mot de passe -->
                        <div class="mb-4" id="passwordStrength" style="display: none;">
                            <label class="form-label fw-bold">Force du mot de passe</label>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" id="strengthBar" style="width: 0%"></div>
                            </div>
                            <small id="strengthText" class="text-muted"></small>
                        </div>

                        <!-- Conseils de sécurité -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">
                                    <i class="fas fa-shield-alt text-success me-2"></i>Conseils pour un mot de passe sécurisé
                                </h6>
                                <ul class="mb-0 small">
                                    <li>Utilisez au moins 8 caractères</li>
                                    <li>Mélangez lettres majuscules et minuscules</li>
                                    <li>Incluez des chiffres et des caractères spéciaux</li>
                                    <li>Évitez les informations personnelles évidentes</li>
                                    <li>Ne réutilisez pas vos anciens mots de passe</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('adherent.profile')); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Retour au profil
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Enregistrer le nouveau mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Carte de sécurité supplémentaire -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Sécurité de votre compte
                    </h6>
                    <p class="text-muted mb-0">
                        Pour votre sécurité, nous vous recommandons de changer votre mot de passe régulièrement 
                        et de ne jamais le partager avec qui que ce soit. Si vous pensez que votre compte a été compromis, 
                        changez immédiatement votre mot de passe et contactez notre support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('new_password_confirmation');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeIconConfirm = document.getElementById('eyeIconConfirm');
    const strengthDiv = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const form = document.getElementById('passwordForm');
    const submitBtn = document.getElementById('submitBtn');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });

    togglePasswordConfirm.addEventListener('click', function() {
        const type = confirmInput.type === 'password' ? 'text' : 'password';
        confirmInput.type = type;
        eyeIconConfirm.classList.toggle('fa-eye');
        eyeIconConfirm.classList.toggle('fa-eye-slash');
    });

    // Check password strength
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length === 0) {
            strengthDiv.style.display = 'none';
            return;
        }
        
        strengthDiv.style.display = 'block';
        let strength = 0;
        let message = '';
        
        // Length check
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 25;
        
        // Character variety checks
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
        if (/\d/.test(password)) strength += 15;
        if (/[^a-zA-Z\d]/.test(password)) strength += 10;
        
        // Set progress bar and message
        strengthBar.style.width = strength + '%';
        
        if (strength < 30) {
            strengthBar.className = 'progress-bar bg-danger';
            message = 'Faible';
        } else if (strength < 60) {
            strengthBar.className = 'progress-bar bg-warning';
            message = 'Moyen';
        } else if (strength < 80) {
            strengthBar.className = 'progress-bar bg-info';
            message = 'Bon';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            message = 'Excellent';
        }
        
        strengthText.textContent = message;
    });

    // Check password match on confirmation input
    confirmInput.addEventListener('input', function() {
        if (passwordInput.value && this.value) {
            if (passwordInput.value === this.value) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas !');
            return false;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
    });
});
</script>
<?php $__env->stopPush(); ?>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.input-group-text {
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #86b7fe;
    box-shadow: none;
}

.input-group-text {
    background-color: #f8f9fa;
}

.form-control.is-valid {
    border-color: #198754;
}

.form-control.is-invalid {
    border-color: #dc3545;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adherent.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/adherent/password/edit.blade.php ENDPATH**/ ?>