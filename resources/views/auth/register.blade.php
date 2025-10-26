<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Adhérent - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #007bff 100%);
            position: relative;
        }
        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff05" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .brand-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 2rem 1.5rem;
        }
        .brand-logo {
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.3));
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 1rem;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: linear-gradient(90deg, #e9ecef 0%, #e9ecef 100%);
            z-index: 1;
            border-radius: 10px;
        }
        .step {
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 2;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: bold;
            border: 3px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            color: #6c757d;
            transition: all 0.3s ease;
        }
        .step.active .step-number {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,123,255,0.4);
        }
        .step.completed .step-number {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40,167,69,0.4);
        }
        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }
        .step.active .step-label {
            color: #007bff;
            font-weight: 700;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #007bff;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,123,255,0.3);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,123,255,0.4);
        }
        .password-strength {
            height: 4px;
            margin-top: 8px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-medium { background: #ffc107; width: 50%; }
        .strength-strong { background: #28a745; width: 75%; }
        .strength-very-strong { background: linear-gradient(90deg, #28a745 0%, #007bff 100%); width: 100%; }
        .alert {
            border: none;
            border-radius: 10px;
            border-left: 4px solid;
        }
        .alert-info {
            background: rgba(0,123,255,0.1);
            border-left-color: #007bff;
            color: #004085;
        }
        .alert-danger {
            background: rgba(220,53,69,0.1);
            border-left-color: #dc3545;
            color: #721c24;
        }
        .alert-success {
            background: rgba(40,167,69,0.1);
            border-left-color: #28a745;
            color: #155724;
        }
        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }
        .text-primary {
            color: #007bff !important;
        }
        .security-badge {
            background: rgba(0,123,255,0.1);
            color: #007bff;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            border: 1px solid rgba(0,123,255,0.2);
        }
        .back-to-home {
            position: absolute;
            top: 1rem;
            left: 1rem;
            z-index: 10;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .register-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="register-container d-flex align-items-center py-4">
        <!-- Bouton retour accueil -->
        <a href="{{ route('home') }}" class="back-to-home btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Accueil
        </a>
        
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="card register-card" data-aos="fade-up">
                        <!-- En-tête avec logo -->
                        <div class="brand-header text-center">
                            <div class="brand-logo mb-3">
                                <i class="fas fa-hand-holding-usd fa-3x"></i>
                            </div>
                            <h2 class="fw-bold mb-2">SIFcash-Burkina</h2>
                            <p class="mb-0 opacity-75">Création de votre compte adhérent</p>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">

                            <!-- Affichage des messages d'alerte -->
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Veuillez corriger les erreurs ci-dessous.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Indicateur d'étapes -->
                            <div class="step-indicator">
                                <div class="step active">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Compte</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Profil</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Documents</div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                
                                <h5 class="mb-4 text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Étape 1 : Création de votre compte
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nom complet *</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                       id="name" name="name" value="{{ old('name') }}" 
                                                       placeholder="Votre nom complet" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Téléphone *</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                                       placeholder="70 00 00 00" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Format: 70 00 00 00 ou 01 00 00 00</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse email *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" 
                                               placeholder="votre@email.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Utilisez une ademail valide pour recevoir les confirmations</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Mot de passe *</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" name="password" 
                                                       placeholder="Minimum 8 caractères" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div id="password-strength" class="password-strength d-none"></div>
                                            <div class="form-text">
                                                <small>Le mot de passe doit contenir au moins 8 caractères</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmation *</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                       id="password_confirmation" name="password_confirmation" 
                                                       placeholder="Retapez votre mot de passe" required>
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div id="password-match" class="form-text"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" 
                                               id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="terms">
                                            J'accepte les 
                                            <a href="{{ route('terms') }}" target="_blank" class="text-primary text-decoration-none fw-bold">conditions générales</a> 
                                            et la 
                                            <a href="{{ route('privacy') }}" target="_blank" class="text-primary text-decoration-none fw-bold">politique de confidentialité</a>
                                            *
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Information :</strong> Après cette étape, vous devrez compléter votre profil 
                                        personnel et uploader vos documents d'identité pour finaliser votre inscription.
                                    </small>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg py-3">
                                        <i class="fas fa-user-plus me-2"></i>Créer mon compte et continuer
                                    </button>
                                    <div class="text-center mt-3">
                                        <span class="text-muted">Déjà membre ? </span>
                                        <a href="{{ route('adherent.login') }}" class="text-decoration-none fw-bold">
                                            Connectez-vous ici
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <hr class="my-4">

                            <div class="text-center">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Vos informations sont sécurisées et confidentielles
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });
            
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const strengthBar = document.getElementById('password-strength');
            const matchText = document.getElementById('password-match');

            // Vérification de la force du mot de passe
            password.addEventListener('input', function() {
                const value = password.value;
                let strength = 0;
                
                if (value.length >= 8) strength++;
                if (value.match(/[a-z]/) && value.match(/[A-Z]/)) strength++;
                if (value.match(/\d/)) strength++;
                if (value.match(/[^a-zA-Z\d]/)) strength++;
                
                strengthBar.className = 'password-strength';
                
                if (value.length > 0) {
                    strengthBar.classList.remove('d-none');
                    if (strength < 2) {
                        strengthBar.className += ' strength-weak';
                    } else if (strength < 3) {
                        strengthBar.className += ' strength-medium';
                    } else if (strength < 4) {
                        strengthBar.className += ' strength-strong';
                    } else {
                        strengthBar.className += ' strength-very-strong';
                    }
                } else {
                    strengthBar.classList.add('d-none');
                }
            });

            // Vérification de la correspondance des mots de passe
            confirmPassword.addEventListener('input', function() {
                if (confirmPassword.value !== password.value) {
                    matchText.innerHTML = '<small class="text-danger"><i class="fas fa-times me-1"></i>Les mots de passe ne correspondent pas</small>';
                } else if (confirmPassword.value.length > 0) {
                    matchText.innerHTML = '<small class="text-success"><i class="fas fa-check me-1"></i>Les mots de passe correspondent</small>';
                } else {
                    matchText.innerHTML = '';
                }
            });

            // Focus sur le premier champ avec erreur
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
            }

            // Formatage automatique du téléphone
            const phoneInput = document.getElementById('phone');
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    value = value.match(/.{1,2}/g).join(' ');
                }
                e.target.value = value;
            });
        });
    </script>
</body>
</html>