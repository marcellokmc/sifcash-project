<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIFcash-Burkina - Système d'Épargne et de Crédit</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Hero Section Améliorée */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 70vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 2rem 0;
        }
        
        .hero-bg {
            background: linear-gradient(135deg, 
                rgba(102, 126, 234, 0.9) 0%, 
                rgba(118, 75, 162, 0.9) 100%),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff10" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
            animation: backgroundPulse 6s ease-in-out infinite alternate;
        }
        
        @keyframes backgroundPulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        
        .stat-item {
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: scale(1.1);
        }
        
        /* Service Cards */
        .service-card {
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .service-icon {
            transition: transform 0.3s ease;
        }
        
        .service-card:hover .service-icon i {
            transform: scale(1.2) rotate(5deg);
        }
        
        /* CTA Box */
        .cta-box {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
            position: relative;
            overflow: hidden;
        }
        
        .cta-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Contact Form */
        .contact-form {
            transition: all 0.3s ease;
        }
        
        .contact-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        
        /* Contact Items */
        .contact-item {
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 0.5rem;
        }
        
        .contact-item:hover {
            background: rgba(0,123,255,0.05);
            transform: translateX(10px);
        }
        
        .contact-icon {
            transition: transform 0.3s ease;
        }
        
        .contact-item:hover .contact-icon i {
            transform: scale(1.2);
        }
        
        /* Scroll Indicator */
        .scroll-indicator {
            animation: bounce 2s infinite;
        }
        
        .scroll-down {
            width: 2px;
            height: 30px;
            background: white;
            opacity: 0.7;
            animation: scrollAnimation 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-10px) translateX(-50%);
            }
            60% {
                transform: translateY(-5px) translateX(-50%);
            }
        }
        
        @keyframes scrollAnimation {
            0% {
                opacity: 0;
                height: 10px;
            }
            50% {
                opacity: 1;
                height: 30px;
            }
            100% {
                opacity: 0;
                height: 10px;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Buttons */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn-light {
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
            border: 1px solid #dee2e6;
            color: #212529;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-light:hover {
            background: linear-gradient(45deg, #ffffff, #f8f9fa);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #007bff;
        }
        
        /* Credit Planning Section - Mobile First */
        .credit-section .card-body {
            padding: 1.5rem !important;
        }
        
        .credit-section .icon-circle {
            width: 60px !important;
            height: 60px !important;
        }
        
        .credit-section .icon-circle i {
            font-size: 1.5rem !important;
        }
        
        .credit-section .item-icon {
            width: 35px !important;
            height: 35px !important;
        }
        
        .credit-section h2 {
            font-size: 1.5rem !important;
        }
        
        .credit-section h3 {
            font-size: 1.1rem !important;
        }
        
        .credit-section h4 {
            font-size: 1rem !important;
        }
        
        .credit-section h5 {
            font-size: 0.9rem !important;
        }
        
        .credit-section p {
            font-size: 0.85rem !important;
        }
        
        /* Desktop optimizations */
        @media (min-width: 992px) {
            .hero-section {
                min-height: 60vh;
                padding: 2rem 0;
            }
            
            .hero-section h1 {
                font-size: 2.2rem !important;
                white-space: nowrap;
            }
            
            .hero-section .lead {
                font-size: 1rem !important;
            }
            
            .hero-section .mb-5 {
                margin-bottom: 2rem !important;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            section {
                padding-top: 2.5rem !important;
                padding-bottom: 2.5rem !important;
            }
            
            /* Credit Section Desktop */
            .credit-section .card-body {
                padding: 2rem !important;
            }
            
            .credit-section .icon-circle {
                width: 70px !important;
                height: 70px !important;
            }
            
            .credit-section .icon-circle i {
                font-size: 2rem !important;
            }
            
            .credit-section .item-icon {
                width: 40px !important;
                height: 40px !important;
            }
            
            .credit-section h2 {
                font-size: 2rem !important;
            }
            
            .credit-section h3 {
                font-size: 1.3rem !important;
            }
            
            .credit-section h4 {
                font-size: 1.1rem !important;
            }
            
            .credit-section h5 {
                font-size: 1rem !important;
            }
            
            .credit-section p {
                font-size: 0.9rem !important;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                min-height: auto;
                text-align: center;
                padding: 2rem 0 1rem;
            }
            
            .hero-section h1 {
                font-size: 1.5rem !important;
                white-space: normal;
                margin-bottom: 1rem !important;
            }
            
            .hero-section .lead {
                font-size: 0.9rem !important;
                margin-bottom: 1rem !important;
            }
            
            .hero-section p {
                font-size: 0.85rem !important;
                margin-bottom: 1.5rem !important;
            }
            
            .stat-item h3 {
                font-size: 1rem;
                margin-bottom: 0.25rem !important;
            }
            
            .stat-item p {
                font-size: 0.6rem;
                margin-bottom: 0 !important;
            }
        }
        
        /* Loading Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    @include('partials.header')

    <!-- Hero Section améliorée -->
    <section class="hero-section text-center position-relative overflow-hidden">
        <!-- Background animé -->
        <div class="hero-bg position-absolute w-100 h-100"></div>
        
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4 text-white" data-aos="fade-up">
                            Bienvenue chez <span class="text-dark">SIFcash-Burkina</span>
                        </h1>
                        <p class="lead mb-4 text-white" data-aos="fade-up" data-aos-delay="200">
                            <strong>Une solution simple et flexible 
                            pour atteindre vos objectifs financiers à court terme(SIFCash)</strong>.
                        </p>
                        <p class="mb-5 text-white-50" data-aos="fade-up" data-aos-delay="300">
                            Grâce à notre service d'épargne personnalisée, vous avez la liberté de choisir le montant, 
                            la périodicité et la durée selon vos besoins.
                        </p>
                        
                        <!-- Statistiques rapides -->
                        <div class="row text-center mb-3 mb-md-5" data-aos="fade-up" data-aos-delay="400">
                            <div class="col-4 col-md-4 mb-2 mb-md-3">
                                <div class="stat-item">
                                    <h3 class="text-white fw-bold mb-1">10K+</h3>
                                    <p class="text-white-50 small mb-0">Adhérents satisfaits</p>
                                </div>
                            </div>
                            <div class="col-4 col-md-4 mb-2 mb-md-3">
                                <div class="stat-item">
                                    <h3 class="text-white fw-bold mb-1">5.2M+</h3>
                                    <p class="text-white-50 small mb-0">FCFA d'épargne</p>
                                </div>
                            </div>
                            <div class="col-4 col-md-4 mb-2 mb-md-3">
                                <div class="stat-item">
                                    <h3 class="text-white fw-bold mb-1">15+</h3>
                                    <p class="text-white-50 small mb-0">Années d'expérience</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="hero-actions" data-aos="fade-up" data-aos-delay="600">
                            <div class="row justify-content-center g-3">
                                <div class="col-sm-6 col-lg-4">
                                    <a href="{{ route('register') }}" class="btn btn-light btn-lg w-100 shadow-lg">
                                        <i class="fas fa-user-plus me-2"></i>Devenir Adhérent
                                    </a>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg w-100">
                                        <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
                                    </a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#services" class="btn btn-link text-white text-decoration-none">
                                    <i class="fas fa-chevron-down me-1"></i>Découvrir nos services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x mb-4">
            <div class="scroll-down"></div>
        </div>
    </section>

    <!-- Section À Propos et Informations -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container">
            <div class="row mb-5">
                <!-- À Propos SIF -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100 border-0 shadow-lg position-relative overflow-hidden service-card" style="border-radius: 20px;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0.1;"></div>
                        <div class="card-body p-4 text-center position-relative">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);">
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
                            <a href="{{ route('about') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                <i class="fas fa-arrow-right me-1"></i>En savoir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Confidentialité et Sécurité -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-lg position-relative overflow-hidden service-card" style="border-radius: 20px;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); opacity: 0.1;"></div>
                        <div class="card-body p-4 text-center position-relative">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); box-shadow: 0 10px 25px rgba(17, 153, 142, 0.3);">
                                    <i class="fas fa-shield-alt text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: #11998e;">🔒 Sécurité Totale</h5>
                            <p class="text-muted small mb-3">Vos données personnelles et financières sont protégées par les plus hauts standards de sécurité numérique.</p>
                            <div class="d-flex flex-wrap gap-1 mb-3 justify-content-center">
                                <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">🛡️ Sécurisé</span>
                                <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">🔐 Crypté</span>
                                <span class="badge" style="background: rgba(17, 153, 142, 0.1); color: #11998e;">✅ RGPD</span>
                            </div>
                            <a href="{{ route('privacy') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                                <i class="fas fa-lock me-1"></i>Confidentialité
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Support et Aide -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-lg position-relative overflow-hidden service-card" style="border-radius: 20px;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); opacity: 0.1;"></div>
                        <div class="card-body p-4 text-center position-relative">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); box-shadow: 0 10px 25px rgba(255, 154, 158, 0.3);">
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
                            <button class="btn btn-outline-danger btn-sm rounded-pill px-4" onclick="alert('📞 Contactez-nous au : +226 25 45 63 64\n📧 Email : contact@sifcash-burkina.bf\n💬 Chat en ligne disponible')">
                                <i class="fas fa-phone me-1"></i>Contacter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section améliorée -->
    <section id="services" class="py-5" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="display-6 fw-bold text-dark mb-3" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">💼 Nos Services Financiers</h2>
                    <p class="text-dark" style="opacity: 0.9; font-size: 1.1rem;">Des solutions adaptées à tous vos besoins et projets</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card card h-100 border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);">
                                    <i class="fas fa-piggy-bank text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h4 class="card-title fw-bold mb-3" style="color: #22c55e;">💰 Épargne Sécurisée</h4>
                            <p class="card-text text-muted mb-4">
                                Plans d'épargne flexibles avec taux d'intérêt compétitifs pour faire fructifier vos économies.
                            </p>
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <ul class="list-unstyled mb-0 small text-dark">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>5,000 FCFA</strong> minimum mensuel</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>3% annuel</strong> taux d'intérêt</li>
                                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i><strong>Capital garanti</strong> à l'échéance</li>
                                </ul>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-plus me-1"></i>Commencer
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card card h-100 border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);">
                                    <i class="fas fa-handshake text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h4 class="card-title fw-bold mb-3" style="color: #3b82f6;">🏦 Crédit Accessible</h4>
                            <p class="card-text text-muted mb-4">
                                Solutions de crédit adaptées à vos projets avec conditions flexibles et transparentes.
                            </p>
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <ul class="list-unstyled mb-0 small text-dark">
                                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i><strong>Crédits personnels</strong></li>
                                    <li class="mb-2"><i class="fas fa-check text-primary me-2"></i><strong>Crédits professionnels</strong></li>
                                    <li class="mb-0"><i class="fas fa-check text-primary me-2"></i><strong>Microcrédits</strong> rapides</li>
                                </ul>
                            </div>
                            <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-rocket me-1"></i>Demander
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mx-auto" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card card h-100 border-0 shadow-lg" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" 
                                     style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);">
                                    <i class="fas fa-certificate text-white" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h4 class="card-title fw-bold mb-3" style="color: #f59e0b;">🏆 Excellence Certifiée</h4>
                            <p class="card-text text-muted mb-4">
                                Institution certifiée avec des années d'expériences et plusieurs clients satisfaits.
                            </p>
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <ul class="list-unstyled mb-0 small text-dark">
                                    <li class="mb-2"><i class="fas fa-award text-warning me-2"></i><strong>Client</strong> satisfaits</li>
                                    <li class="mb-2"><i class="fas fa-award text-warning me-2"></i><strong>RGPD</strong> conforme</li>
                                    <li class="mb-0"><i class="fas fa-award text-warning me-2"></i><strong>Des années</strong> d'expérience</li>
                                </ul>
                            </div>
                            <a href="{{ route('about') }}" class="btn btn-warning rounded-pill px-4 text-white">
                                <i class="fas fa-trophy me-1"></i>Découvrir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="cta-box position-relative overflow-hidden rounded-4 p-5 shadow-xl" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); background-size: 300% 300%; animation: gradient-shift 8s ease infinite;">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.1) 0%, transparent 70%); pointer-events: none;"></div>
                        <div class="position-relative">
                            <h3 class="fw-bold mb-3 text-white" style="font-size: 2.2rem; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">🚀 Prêt à transformer vos rêves en réalité ?</h3>
                            <p class="mb-4 text-white" style="font-size: 1.1rem; opacity: 0.95;">Rejoignez plus de 10,000 adhérents qui nous font confiance pour réaliser leurs projets</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap mb-4">
                                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5 shadow-lg" style="border-radius: 25px; font-weight: 600; transform: scale(1); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    <i class="fas fa-rocket me-2"></i>Commencer maintenant
                                </a>
                                <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-5" style="border-radius: 25px; font-weight: 600; border-width: 2px;">
                                    <i class="fas fa-info-circle me-2"></i>En savoir plus
                                </a>
                            </div>
                            <div class="d-flex justify-content-center gap-4 flex-wrap">
                                <div class="text-center">
                                    <div class="fw-bold text-white" style="font-size: 1.8rem;">10K+</div>
                                    <small class="text-white" style="opacity: 0.8;">👥 Clients</small>
                                </div>
                                <div class="text-center">
                                    <div class="fw-bold text-white" style="font-size: 1.8rem;">98%</div>
                                    <small class="text-white" style="opacity: 0.8;">😊 Satisfaction</small>
                                </div>
                                <div class="text-center">
                                    <div class="fw-bold text-white" style="font-size: 1.8rem;">15M</div>
                                    <small class="text-white" style="opacity: 0.8;">💰 FCFA gérés</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Planning de Crédit -->
    <section class="py-4 py-md-5 credit-section" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="container">
            <div class="row text-center mb-3 mb-md-4">
                <div class="col-lg-10 mx-auto" data-aos="fade-up">
                    <h2 class="fw-bold text-white mb-2" style="text-shadow: 0 4px 15px rgba(0,0,0,0.3);">💳 SOURCE INÉPUISABLE FINANCIÈRE</h2>
                    <p class="text-white fw-bold mb-1" style="text-shadow: 0 2px 8px rgba(0,0,0,0.2); font-size: 1.1rem;">BÂTISSEZ UN AVENIR MEILLEUR !!!</p>
                    <p class="text-white-50 mb-0">Des solutions de crédit adaptées à tous vos projets</p>
                </div>
            </div>
            
            <!-- Deux grandes catégories -->
            <div class="row g-3 g-md-4 mb-3 mb-md-4">
                <!-- Assistance court terme -->
                <div class="col-lg-6 col-md-12" data-aos="fade-right" data-aos-delay="100">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 15px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); overflow: hidden;">
                        <div class="position-absolute top-0 start-0 w-100" style="height: 5px; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 icon-circle" 
                                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);">
                                    <i class="fas fa-hand-holding-usd text-white"></i>
                                </div>
                                <h3 class="fw-bold mb-2" style="color: #667eea;">🆘 ASSISTANCE DE 1 À 30 JOURS</h3>
                                <p class="text-muted mb-3">Solutions rapides pour vos besoins urgents du quotidien</p>
                            </div>
                            
                            <div class="bg-light rounded-3 p-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2 d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="fas fa-home text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #667eea;">🏠 Loyer - Facture - Aléas sanitaire</h5>
                                            <p class="text-muted mb-0">Couvrez vos dépenses essentielles sans tracas</p>
                                        </div>
                                    </li>
                                    <li class="mb-2 d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="fas fa-shopping-basket text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #667eea;">🛒 Approvisionnement de vivres</h5>
                                            <p class="text-muted mb-0">Assurez les besoins alimentaires de votre famille</p>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="fas fa-gift text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #667eea;">🎄 Fêtes de fin d'année</h5>
                                            <p class="text-muted mb-0">Célébrez les moments importants sereinement</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Grands projets long terme -->
                <div class="col-lg-6 col-md-12" data-aos="fade-left" data-aos-delay="200">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 15px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); overflow: hidden;">
                        <div class="position-absolute top-0 start-0 w-100" style="height: 5px; background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);"></div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 icon-circle" 
                                     style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); box-shadow: 0 8px 20px rgba(240, 147, 251, 0.3);">
                                    <i class="fas fa-rocket text-white"></i>
                                </div>
                                <h3 class="fw-bold mb-2" style="color: #f5576c;">🚀 RÉALISATION DE GRAND PROJET DE 1 À 2 ANS</h3>
                                <p class="text-muted mb-3">Financez vos projets d'envergure et transformez vos rêves en réalité</p>
                            </div>
                            
                            <div class="bg-light rounded-3 p-3">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2 d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                <i class="fas fa-map-marked-alt text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #f5576c;">🗺️ Terrain - Construction</h5>
                                            <p class="text-muted mb-0">Construisez la maison de vos rêves</p>
                                        </div>
                                    </li>
                                    <li class="mb-2 d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                <i class="fas fa-chart-line text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #f5576c;">📈 Extension d'entreprise</h5>
                                            <p class="text-muted mb-0">Développez votre activité professionnelle</p>
                                        </div>
                                    </li>
                                    <li class="mb-2 d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                <i class="fas fa-plane-departure text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #f5576c;">✈️ Préparatifs de voyage</h5>
                                            <p class="text-muted mb-0">Réalisez vos projets de voyage et d'expatriation</p>
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle item-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                <i class="fas fa-car text-white" style="font-size: 0.8rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h5 class="mb-0 fw-bold" style="color: #f5576c;">🚗 Achat de moyen roulant</h5>
                                            <p class="text-muted mb-0">Acquérez votre véhicule en toute simplicité</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section Conditions d'éligibilité -->
            <div class="row">
                <div class="col-lg-10 mx-auto" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-xl" style="border-radius: 15px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.98); overflow: hidden;">
                        <div class="position-absolute top-0 start-0 w-100" style="height: 5px; background: linear-gradient(90deg, #667eea 0%, #f5576c 100%);"></div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2 icon-circle" 
                                     style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);">
                                    <i class="fas fa-clipboard-check text-white"></i>
                                </div>
                                <h3 class="fw-bold mb-2" style="color: #22c55e;">📋 CONDITIONS D'ÉLIGIBILITÉ</h3>
                                <p class="text-muted mb-0">Un service de crédit disponible selon les conditions suivantes</p>
                            </div>
                            
                            <div class="row g-3">
                                <!-- Conditions d'éligibilité -->
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-3 h-100">
                                        <h4 class="fw-bold mb-3" style="color: #667eea;">
                                            <i class="fas fa-check-circle me-2"></i>Conditions
                                        </h4>
                                        <div class="mb-2">
                                            <h5 class="fw-bold mb-2" style="color: #22c55e;">
                                                <i class="fas fa-calendar-alt me-2"></i>Ancienneté requise :
                                            </h5>
                                            <ul class="list-unstyled ms-3">
                                                <li class="mb-2 d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-2">
                                                        <span class="badge rounded-pill" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 5px 10px;">6 mois</span>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-dark">Pour la <strong>cotisation journalière/hebdomadaire</strong></p>
                                                    </div>
                                                </li>
                                                <li class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-2">
                                                        <span class="badge rounded-pill" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 5px 10px;">1 an</span>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 text-dark">Pour la <strong>cotisation mensuelle</strong></p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Avantages -->
                                <div class="col-md-6">
                                    <div class="bg-light rounded-3 p-3 h-100">
                                        <h4 class="fw-bold mb-3" style="color: #f5576c;">
                                            <i class="fas fa-star me-2"></i>Avantages
                                        </h4>
                                        <ul class="list-unstyled">
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1rem;"></i>
                                                <span class="text-dark"><strong>Taux compétitifs</strong> et transparents</span>
                                            </li>
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1rem;"></i>
                                                <span class="text-dark"><strong>Remboursement flexible</strong></span>
                                            </li>
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1rem;"></i>
                                                <span class="text-dark"><strong>Accompagnement</strong> personnalisé</span>
                                            </li>
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1rem;"></i>
                                                <span class="text-dark"><strong>Décision rapide</strong> 48h</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1rem;"></i>
                                                <span class="text-dark"><strong>Sans frais cachés</strong></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Call to action -->
                            <div class="text-center mt-3">
                                <div class="alert alert-info border-0 shadow-sm mb-3 p-2" style="border-radius: 15px; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                                    <i class="fas fa-info-circle me-1" style="color: #667eea;"></i>
                                    <strong style="color: #667eea;">Important :</strong> <span class="text-dark">Vos épargnes servent de garantie. Plus vous épargnez, plus vous avez accès à des montants élevés !</span>
                                </div>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('register') }}" class="btn btn-md px-4 shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                                        <i class="fas fa-user-plus me-1"></i>Devenir Adhérent
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-md px-4" style="border-radius: 20px; font-weight: 600; border-width: 2px; font-size: 0.9rem;">
                                        <i class="fas fa-calculator me-1"></i>Simuler mon crédit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Témoignages et Confiance -->
    <section class="py-5" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="display-6 fw-bold text-dark mb-3">🎆 Ils nous font confiance</h2>
                    <p class="text-dark" style="opacity: 0.8; font-size: 1.1rem;">Découvrez pourquoi nos clients choisissent SIFcash-Burkina</p>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9);">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 2rem; font-weight: bold; color: white;">AK</div>
                            </div>
                            <blockquote class="blockquote mb-3">
                                <p class="mb-0" style="font-style: italic; color: #4a5568;">« Grâce à SIFcash, j'ai pu financer mon commerce. Le processus est simple et l'équipe très professionnelle. »</p>
                            </blockquote>
                            <footer class="blockquote-footer">
                                <strong>Aminata K.</strong>
                                <div class="text-muted small">Commerçante à Ouagadougou</div>
                                <div class="mt-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9);">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); font-size: 2rem; font-weight: bold; color: white;">IB</div>
                            </div>
                            <blockquote class="blockquote mb-3">
                                <p class="mb-0" style="font-style: italic; color: #4a5568;">« L'épargne chez SIF m'a permis de réaliser mon rêve d'avoir ma propre maison. Merci pour la confiance ! »</p>
                            </blockquote>
                            <footer class="blockquote-footer">
                                <strong>Ibrahim B.</strong>
                                <div class="text-muted small">Enseignant à Bobo-Dioulasso</div>
                                <div class="mt-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 mx-auto" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-lg h-100" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9);">
                        <div class="card-body p-4 text-center">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 2rem; font-weight: bold; color: white;">FS</div>
                            </div>
                            <blockquote class="blockquote mb-3">
                                <p class="mb-0" style="font-style: italic; color: #4a5568;">« Service client exceptionnel et solutions adaptées. Je recommande SIF à tous mes amis ! »</p>
                            </blockquote>
                            <footer class="blockquote-footer">
                                <strong>Fatima S.</strong>
                                <div class="text-muted small">Artisane à Koudougou</div>
                                <div class="mt-2">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Pourquoi nous choisir -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="display-6 fw-bold text-white mb-3" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">🎆 Pourquoi choisir SIFcash ?</h2>
                    <p class="text-white" style="opacity: 0.9; font-size: 1.1rem;">Les raisons qui font de nous votre partenaire financier idéal</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center text-white">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
                             style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <i class="fas fa-clock" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">⚡ Rapidité</h4>
                        <p style="opacity: 0.9;">Traitement de vos demandes en moins de 48h. Votre temps est précieux, nous le respectons.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center text-white">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
                             style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <i class="fas fa-handshake" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">💖 Confiance</h4>
                        <p style="opacity: 0.9;">Des années d'expériences et plusieurs clients satisfaits. Votre confiance est notre priorité.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center text-white">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
                             style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <i class="fas fa-cogs" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">🛠️ Flexibilité</h4>
                        <p style="opacity: 0.9;">Solutions sur mesure adaptées à votre profil et vos besoins spécifiques.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center text-white">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" 
                             style="width: 100px; height: 100px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <i class="fas fa-users" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">🤝 Proximité</h4>
                        <p style="opacity: 0.9;">Présents localement au Burkina Faso, nous comprenons vos réalités et besoins.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section Liens Utiles -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto" data-aos="fade-up">
                    <h2 class="display-6 fw-bold text-dark mb-3">🔗 Informations Utiles</h2>
                    <p class="text-muted" style="font-size: 1.1rem;">Accès rapide aux informations importantes</p>
                </div>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="100">
                    <a href="{{ route('terms') }}" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none shadow-sm" style="border-radius: 15px; min-height: 120px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'">
                        <i class="fas fa-file-contract text-primary mb-2" style="font-size: 1.5rem;"></i>
                        <small class="fw-bold text-dark">📋 CGU</small>
                    </a>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('data-protection') }}" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none shadow-sm" style="border-radius: 15px; min-height: 120px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'">
                        <i class="fas fa-database text-success mb-2" style="font-size: 1.5rem;"></i>
                        <small class="fw-bold text-dark">🗃️ Protection</small>
                    </a>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('about') }}" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none shadow-sm" style="border-radius: 15px; min-height: 120px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'">
                        <i class="fas fa-building text-info mb-2" style="font-size: 1.5rem;"></i>
                        <small class="fw-bold text-dark">🏢 À Propos</small>
                    </a>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('privacy') }}" class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-decoration-none shadow-sm" style="border-radius: 15px; min-height: 120px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'">
                        <i class="fas fa-user-shield text-warning mb-2" style="font-size: 1.5rem;"></i>
                        <small class="fw-bold text-dark">🔒 Vie privée</small>
                    </a>
                </div>
                
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="500">
                    <button class="btn btn-light w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 shadow-sm" style="border-radius: 15px; min-height: 120px; transition: all 0.3s ease;" onclick="alert('🌟 Merci de faire confiance à SIFcash-Burkina !\n\n🏆 Votre partenaire financier de confiance\n💪 Ensemble, construisons votre avenir financier')" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.05)'">
                        <i class="fas fa-heart mb-2" style="font-size: 1.5rem; color: #e91e63;"></i>
                        <small class="fw-bold text-dark">❤️ Merci</small>
                    </button>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="display-6 fw-bold mb-3 text-dark">Contactez-nous</h2>
                    <p class="lead text-secondary mb-4">
                        Notre équipe est à votre disposition pour répondre à toutes vos questions
                    </p>
                    
                    <div class="contact-info">
                        <div class="contact-item d-flex align-items-center mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Adresse</h6>
                                <p class="mb-0 text-dark">Ouagadougou, Burkina Faso</p>
                            </div>
                        </div>
                        
                        <div class="contact-item d-flex align-items-center mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-phone fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Téléphone</h6>
                                <p class="mb-0">
                                    <a href="tel:+22625456364" class="text-decoration-none fw-bold" style="color: #000 !important;">+226 25 45 63 64</a> / 
                                    <a href="tel:+22676182726" class="text-decoration-none fw-bold" style="color: #000 !important;">76 18 27 26</a> / 
                                    <a href="tel:+22604370203" class="text-decoration-none fw-bold" style="color: #000 !important;">04 37 02 03</a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="contact-item d-flex align-items-center mb-3">
                            <div class="contact-icon me-3">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Email</h6>
                                <p class="mb-0 text-dark">contact@sifcash-burkina.bf</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-form bg-light rounded-4 p-4 shadow-sm">
                        <h4 class="fw-bold mb-4">Envoyez-nous un message</h4>
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nom</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Sujet</label>
                                    <input type="text" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Message</label>
                                    <textarea class="form-control" rows="4" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Counter animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start).toLocaleString() + '+';
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target.toLocaleString() + '+';
                }
            }
            updateCounter();
        }
        
        // Intersection Observer for counter animation
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-item h3');
                    counters.forEach((counter, index) => {
                        const values = ['10,000', '5.2M', '15'];
                        const targets = [10000, 5200000, 15];
                        
                        setTimeout(() => {
                            animateCounter(counter, targets[index]);
                        }, index * 200);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Start observing when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const statsSection = document.querySelector('.row.text-center.mb-5');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });
        
        // Form submission (demo)
        document.querySelector('#contact form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Message envoyé!';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                    this.reset();
                }, 3000);
            }, 2000);
        });
        
        // Parallax effect for hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            const heroSection = document.querySelector('.hero-section');
            
            if (heroSection) {
                heroSection.style.transform = `translateY(${rate}px)`;
            }
        });
    </script>
</body>
</html>