<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Adhérent - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: calc(100vh - 160px);
            padding: 2rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            background-size: 300% 300%;
            animation: gradient-shift 8s ease infinite;
            position: relative;
            overflow: hidden;
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle fill="%23ffffff08" cx="200" cy="300" r="150"/><circle fill="%23ffffff05" cx="800" cy="700" r="200"/><polygon fill="%23ffffff03" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        .login-container > .container {
            position: relative;
            z-index: 2;
        }
        .login-card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.2);
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.98);
            overflow: hidden;
        }
        .brand-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle fill="%23ffffff10" cx="20" cy="20" r="10"/><circle fill="%23ffffff08" cx="80" cy="60" r="15"/></svg>');
        }
        .brand-section > * {
            position: relative;
            z-index: 1;
        }
        .brand-logo {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255,255,255,0.4);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2), inset 0 2px 10px rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        
        .brand-logo:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3), inset 0 2px 10px rgba(255,255,255,0.4);
        }
        .form-section {
            padding: 2.5rem;
        }
        .form-floating > .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            padding-top: 1.625rem;
            padding-bottom: 0.625rem;
        }
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: scale(1.02);
        }
        .form-floating > label {
            padding-left: 0.75rem;
        }
        .btn-success {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }
        
        .btn-success:hover::before {
            left: 100%;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        .btn-outline-success {
            border: 2px solid #667eea;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: #667eea;
            position: relative;
            overflow: hidden;
        }
        
        .btn-outline-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.4s ease;
            z-index: -1;
        }
        
        .btn-outline-success:hover {
            color: white;
            border-color: #667eea;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-success:hover::before {
            width: 100%;
        }
        .divider {
            position: relative;
            text-align: center;
            margin: 2rem 0;
        }
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
        }
        .divider span {
            background: white;
            padding: 0 1.5rem;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .feature-badge {
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 25px;
            padding: 0.6rem 1.2rem;
            color: white;
            font-size: 0.875rem;
            display: inline-block;
            margin: 0.3rem;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .feature-badge:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .security-info {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(40,167,69,0.1);
        }
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        .floating-elements::before {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 15%;
        }
        .floating-elements::after {
            width: 40px;
            height: 40px;
            bottom: 30%;
            left: 10%;
            animation-delay: 3s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="login-container d-flex align-items-center">
        <div class="floating-elements"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card login-card" data-aos="fade-up" data-aos-duration="1200">
                        <!-- Brand Section -->
                        <div class="brand-section">
                            <div class="brand-logo">
                                <i class="fas fa-university fa-2x" style="color: rgba(255,255,255,0.9);"></i>
                            </div>
                            <h2 class="mb-2" style="font-weight: 700; font-size: 1.8rem;">🏦 SIFcash-Burkina</h2>
                            <p class="mb-3" style="opacity: 0.9; font-size: 1.1rem; font-weight: 500;">✨ Espace Adhérent Sécurisé</p>
                            <p class="mb-3" style="opacity: 0.8; font-size: 0.95rem;">Accédez à vos services financiers en toute sécurité</p>
                            <div class="mt-3">
                                <div class="feature-badge">
                                    <i class="fas fa-piggy-bank me-2"></i>💰 Épargne Flexible
                                </div>
                                <div class="feature-badge">
                                    <i class="fas fa-handshake me-2"></i>🤝 Crédit Rapide
                                </div>
                                <div class="feature-badge">
                                    <i class="fas fa-shield-alt me-2"></i>🔒 100% Sécurisé
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <div class="form-section">

                            <!-- Affichage des messages d'alerte -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('status'))
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('adherent.login') }}">
                                @csrf
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control @error('login') is-invalid @enderror" 
                                           id="login" name="login" value="{{ old('login') }}" required
                                           placeholder="votre@email.com ou 70 00 00 00">
                                    <label for="login">
                                        <i class="fas fa-user me-2"></i>Email ou Téléphone
                                    </label>
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2" style="color: #6366f1; font-weight: 500;">
                                        <i class="fas fa-info-circle me-1" style="color: #667eea;"></i>
                                        📱 Utilisez votre email ou numéro de téléphone enregistré
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required
                                           placeholder="Votre mot de passe">
                                    <label for="password">
                                        <i class="fas fa-lock me-2"></i>Mot de passe
                                    </label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        <i class="fas fa-check-circle me-1"></i>Se souvenir de moi
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-success w-100 mb-3" style="font-size: 1.1rem; font-weight: 600;">
                                    <i class="fas fa-sign-in-alt me-2"></i>🚀 Connexion Adhérent
                                </button>

                                <div class="text-center mt-2">
                                    <div class="small fw-bold" style="color: #64748b; font-size: 0.95rem;">
                                        <i class="fas fa-info-circle me-1" style="color:#fb923c"></i>
                                        Mot de passe oublié ? Veuillez contacter l’administrateur ou le chef de service pour réinitialiser votre mot de passe.
                                    </div>
                                </div>
                            </form>

                            <div class="divider">
                                <span style="font-weight: 600; color: #667eea;">🎆 Nouveau membre ?</span>
                            </div>

                            <div class="text-center">
                                <p class="mb-3" style="color: #4a5568; font-weight: 500;">🎉 Rejoignez SIFcash-Burkina dès aujourd'hui et profitez de nos services</p>
                                <a href="{{ route('register') }}" class="btn btn-outline-success w-100" style="font-size: 1.05rem; font-weight: 600;">
                                    <i class="fas fa-user-plus me-2"></i>✨ Créer un compte adhérent
                                </a>
                            </div>

                            <div class="security-info text-center">
                                <div class="mb-2">
                                    <i class="fas fa-shield-alt me-2" style="color: #667eea;"></i>
                                    <strong style="color: #667eea; font-size: 1.05rem;">🔒 Connexion 100% sécurisée</strong>
                                </div>
                                <small style="color: #6b7280; font-weight: 500;">
                                    🛡️ Vos données sont protégées par un chiffrement SSL 256-bit et conforme RGPD
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Informations et statistiques -->
                    <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="mb-4">
                            <h5 class="text-white fw-bold mb-2" style="font-size: 1.2rem;">🎆 SIFcash en Chiffres</h5>
                            <p class="text-white" style="opacity: 0.8; font-size: 0.95rem;">Votre partenaire financier de confiance au Burkina Faso</p>
                        </div>
                        <div class="row g-4">
                            <div class="col-4 text-center">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                    <div class="text-white">
                                        <i class="fas fa-users fa-2x mb-3" style="opacity: 0.9; color: #fbbf24;"></i>
                                        <div class="fw-bold" style="font-size: 1.4rem;">+10K</div>
                                        <div class="small" style="opacity: 0.9; font-weight: 500;">👥 Adhérents</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                    <div class="text-white">
                                        <i class="fas fa-chart-line fa-2x mb-3" style="opacity: 0.9; color: #22c55e;"></i>
                                        <div class="fw-bold" style="font-size: 1.4rem;">98%</div>
                                        <div class="small" style="opacity: 0.9; font-weight: 500;">😊 Satisfaction</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                                    <div class="text-white">
                                        <i class="fas fa-shield-alt fa-2x mb-3" style="opacity: 0.9; color: #3b82f6;"></i>
                                        <div class="fw-bold" style="font-size: 1.4rem;">+15 ans</div>
                                        <div class="small" style="opacity: 0.9; font-weight: 500;">🛡️ D'expérience</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1200,
            offset: 50,
            easing: 'ease-out-cubic',
            once: true
        });

        // Gestion de l'affichage et interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Focus sur le premier champ avec erreur
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.focus();
            } else {
                // Focus sur le champ login
                document.getElementById('login').focus();
            }
            
            // Animation douce pour les alertes
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('show');
                }, 100);
            });

            // Validation en temps réel pour le champ login
            const loginField = document.getElementById('login');
            const passwordField = document.getElementById('password');

            loginField.addEventListener('blur', function() {
                const value = this.value.trim();
                if (value) {
                    // Vérification simple email ou téléphone
                    const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                    const isPhone = /^[0-9\s\-\+\(\)]{8,15}$/.test(value);
                    
                    if (!isEmail && !isPhone) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                }
            });

            passwordField.addEventListener('input', function() {
                if (this.value.length >= 6) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else if (this.value.length > 0) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            });

            // Animation du bouton de soumission
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Connexion en cours...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>