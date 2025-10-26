<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <!-- Hero Section Services -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Nos Services Financiers</h1>
                    <p class="lead">Des solutions adaptées à tous vos besoins financiers</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-handshake fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Principal - Épargne Personnalisée -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="service-highlight p-4 bg-white rounded-4 shadow-sm">
                        <h2 class="fw-bold mb-4">
                            <i class="fas fa-piggy-bank text-primary me-3"></i>
                            Épargne Personnalisée
                        </h2>
                        <p class="lead mb-4">
                            <strong>Source Inépuisable Financière (SIFCash)</strong> vous propose une solution simple et flexible 
                            pour atteindre vos objectifs financiers à court terme.
                        </p>
                        
                        <h4 class="fw-bold mb-3">Vos Avantages :</h4>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold">Montant Flexible</h6>
                                        <p class="mb-0 small text-muted">Choisissez le montant selon vos capacités</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-calendar-alt text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold">Périodicité à la Carte</h6>
                                        <p class="mb-0 small text-muted">Journalier, hebdomadaire ou mensuel</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold">Durée Personnalisée</h6>
                                        <p class="mb-0 small text-muted">Définissez la durée selon vos besoins</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-shield-alt text-success me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold">Capital Intégral</h6>
                                        <p class="mb-0 small text-muted">Aucun prélèvement sur votre épargne</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info border-0 mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-money-bill-wave me-2"></i>Montants Minimum de Cotisation
                            </h6>
                            <div class="row text-center">
                                <div class="col-md-4 mb-2">
                                    <div class="p-2 bg-white rounded">
                                        <strong class="text-primary">Option Mensuelle</strong><br>
                                        <span class="fw-bold">5,000 FCFA</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="p-2 bg-white rounded">
                                        <strong class="text-primary">Option Hebdomadaire</strong><br>
                                        <span class="fw-bold">7,500 FCFA</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="p-2 bg-white rounded">
                                        <strong class="text-primary">Option Journalière</strong><br>
                                        <span class="fw-bold">1,000 FCFA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning border-0 mb-4">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-file-invoice me-2"></i>Modalités Financières
                            </h6>
                            <ul class="mb-0 small">
                                <li><strong>Taux d'intérêt annuel brut :</strong> 3%</li>
                                <li><strong>Capital payable :</strong> Uniquement à la date d'échéance</li>
                                <li><strong>Intérêts :</strong> Calculés au prorata temporis sur la durée effective</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-success border-0">
                            <i class="fas fa-shield-check me-2"></i>
                            <strong>Garantie totale :</strong> Vous récupérez la totalité de votre épargne + intérêts à l'échéance.
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/500x400/007bff/ffffff?text=Épargne+Sécurisée" 
                             class="img-fluid rounded-4 shadow" alt="Épargne Sécurisée">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Autres Services -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">Nos Autres Services</h2>
                <p class="lead text-muted">Une gamme complète de solutions financières</p>
            </div>
            
            <div class="row g-4">
                <!-- Service Crédit -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <i class="fas fa-handshake fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Services de Crédit</h4>
                            <p class="card-text text-muted mb-4">
                                Des solutions de financement adaptées à vos projets personnels et professionnels.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Crédits personnels</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Crédits professionnels</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Microcrédits</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Crédits d'équipement</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="{{ route('register') }}" class="btn btn-primary">En savoir plus</a>
                        </div>
                    </div>
                </div>
                
                <!-- Service Conseil -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <i class="fas fa-chart-line fa-3x text-success"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Conseil Financier</h4>
                            <p class="card-text text-muted mb-4">
                                Un accompagnement personnalisé pour optimiser votre gestion financière.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Planification financière</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Analyse de projets</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Stratégies d'épargne</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Optimisation budgétaire</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="{{ route('contact') }}" class="btn btn-success">Nous contacter</a>
                        </div>
                    </div>
                </div>
                
                <!-- Service Formation -->
                <div class="col-lg-4 col-md-6 mx-auto" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-card card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-4">
                                <i class="fas fa-graduation-cap fa-3x text-dark"></i>
                            </div>
                            <h4 class="card-title fw-bold mb-3">Formation Financière</h4>
                            <p class="card-text text-muted mb-4">
                                Développez vos compétences en éducation financière avec nos formations.
                            </p>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gestion budgétaire</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Entrepreneuriat</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Investissement</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Épargne intelligente</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="{{ route('contact') }}" class="btn btn-dark">S'inscrire</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comment ça marche -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">Comment ça marche ?</h2>
                <p class="lead text-muted">Un processus simple et transparent</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">1</span>
                        </div>
                        <h4 class="fw-bold mb-3">Contactez-nous</h4>
                        <p class="text-muted">Appelez-nous ou envoyez-nous un email pour discuter de vos besoins.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">2</span>
                        </div>
                        <h4 class="fw-bold mb-3">Personnalisez</h4>
                        <p class="text-muted">Choisissez votre montant, votre périodicité et la durée qui vous conviennent.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number mb-3">
                            <span class="badge bg-primary rounded-circle p-3 fs-4">3</span>
                        </div>
                        <h4 class="fw-bold mb-3">Commencez</h4>
                        <p class="text-muted">Démarrez votre épargne selon les modalités convenues ensemble.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number mb-3">
                            <span class="badge bg-success rounded-circle p-3 fs-4">4</span>
                        </div>
                        <h4 class="fw-bold mb-3">Récupérez</h4>
                        <p class="text-muted">Récupérez la totalité de votre épargne à l'échéance prévue.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conditions Générales -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold mb-3">Conditions Générales</h2>
                <p class="lead text-muted">Informations importantes à connaître avant de commencer</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-handshake me-2"></i>Adhésion et Engagement
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    L'adhésion prend effet à compter de la première prime encaissée
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    L'adhérent déclare avoir pris connaissance des conditions générales
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Engagement à respecter les modalités convenues
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Durée selon le choix de l'adhérent
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-3 text-primary">
                                <i class="fas fa-file-alt me-2"></i>Réclamations et Remboursements
                            </h4>
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                    Toute réclamation doit être formulée par écrit
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-id-card text-info me-2"></i>
                                    Accompagnée d'un justificatif d'identité
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    Remboursement selon la périodicité initiale (quotidienne, hebdomadaire, mensuelle)
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-clock text-secondary me-2"></i>
                                    Remboursement anticipé possible selon les conditions
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12" data-aos="fade-up">
                    <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-2">Information importante</h5>
                            <p class="mb-0">
                                <strong>Les fonds épargnés et les intérêts sont versés à l'échéance,</strong> 
                                sauf en cas de remboursement anticipé. Les intérêts sont calculés au prorata temporis 
                                sur la durée effective de l'épargne.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h2 class="fw-bold mb-3">Commencez à épargner dès aujourd'hui !</h2>
                    <p class="lead mb-0">SIFCash, la source fiable de vos projets financiers.</p>
                </div>
                <div class="col-lg-4 text-center" data-aos="fade-left">
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>S'inscrire
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-phone me-2"></i>Nous appeler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Service cards hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const serviceCards = document.querySelectorAll('.service-card');
            serviceCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,0.075)';
                });
            });
        });
    </script>

    <style>
        .service-card {
            transition: all 0.3s ease;
            height: 100%;
        }

        .service-icon {
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-icon i {
            transform: scale(1.1) rotate(5deg);
        }

        .step-card {
            padding: 2rem 1rem;
            transition: all 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-5px);
        }

        .step-number .badge {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .service-highlight {
            border-left: 5px solid #007bff;
        }

        @media (max-width: 768px) {
            .step-card {
                padding: 1.5rem 1rem;
            }
            
            .step-number .badge {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
        }
    </style>
</body>
</html>