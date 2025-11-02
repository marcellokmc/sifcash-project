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
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                    <div class="p-4 bg-white rounded-4 shadow-lg h-100">
                        <h3 class="fw-bold text-primary mb-4 text-center">
                            <i class="fas fa-piggy-bank me-2"></i>Épargne Sécurisée
                        </h3>
                        
                        <!-- Illustration visuelle -->
                        <div class="mb-4 p-4 text-center" style="background: linear-gradient(135deg, rgba(0,123,255,0.1) 0%, rgba(0,123,255,0.05) 100%); border-radius: 15px;">
                            <div class="d-flex justify-content-center align-items-center mb-3" style="gap: 20px;">
                                <div class="text-center">
                                    <div class="mb-2" style="font-size: 3rem;">💵</div>
                                    <small class="fw-bold text-primary">Dépôt</small>
                                </div>
                                <div style="font-size: 2rem; color: #007bff;">➡️</div>
                                <div class="text-center">
                                    <div class="mb-2" style="font-size: 3rem;">🔒</div>
                                    <small class="fw-bold text-success">Sécurité</small>
                                </div>
                                <div style="font-size: 2rem; color: #007bff;">➡️</div>
                                <div class="text-center">
                                    <div class="mb-2" style="font-size: 3rem;">📈</div>
                                    <small class="fw-bold text-warning">Intérêts</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avantages clés -->
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 h-100 text-center">
                                    <i class="fas fa-shield-alt text-success fs-2 mb-2"></i>
                                    <h6 class="fw-bold mb-1">100% Sécurisé</h6>
                                    <p class="small text-muted mb-0">Votre capital est garanti</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 h-100 text-center">
                                    <i class="fas fa-percent text-primary fs-2 mb-2"></i>
                                    <h6 class="fw-bold mb-1">3% d'intérêt</h6>
                                    <p class="small text-muted mb-0">Taux annuel brut</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 h-100 text-center">
                                    <i class="fas fa-calendar-check text-warning fs-2 mb-2"></i>
                                    <h6 class="fw-bold mb-1">Flexible</h6>
                                    <p class="small text-muted mb-0">Jour/Semaine/Mois</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 h-100 text-center">
                                    <i class="fas fa-wallet text-info fs-2 mb-2"></i>
                                    <h6 class="fw-bold mb-1">Accessible</h6>
                                    <p class="small text-muted mb-0">Dès 1000 FCFA/jour</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Citation motivante -->
                        <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-3 text-center">
                            <p class="mb-0 fst-italic text-primary">
                                <i class="fas fa-quote-left me-2"></i>
                                <strong>"Épargner aujourd'hui, c'est investir dans votre avenir"</strong>
                                <i class="fas fa-quote-right ms-2"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Crédit Détaillé -->
    <section class="py-5" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-6 fw-bold text-white mb-3">
                    <i class="fas fa-hand-holding-usd me-3"></i>Services de Crédit
                </h2>
                <p class="lead text-white">Des solutions de financement adaptées à tous vos projets</p>
            </div>
            
            <div class="row align-items-center mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="p-4 bg-white rounded-4 shadow-lg">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-rocket text-primary me-2"></i>
                            Financez vos projets avec SIFcash
                        </h3>
                        <p class="lead mb-4">
                            Accédez à des <strong>crédits rapides et flexibles</strong> pour réaliser vos projets personnels ou professionnels.
                        </p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-home text-primary me-3 mt-1 fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">Assistance Court Terme</h6>
                                        <p class="mb-0 small text-muted">1 à 30 jours pour vos besoins urgents</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-building text-primary me-3 mt-1 fs-4"></i>
                                    <div>
                                        <h6 class="fw-bold">Grands Projets</h6>
                                        <p class="mb-0 small text-muted">1 à 2 ans pour vos ambitions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-primary border-0 mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-percentage me-2"></i>Conditions Financières
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong class="text-primary">Taux d'intérêt :</strong><br>
                                    <span class="fw-bold fs-5">5%</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong class="text-primary">Pénalité retard :</strong><br>
                                    <span class="fw-bold fs-5">25%</span> sur l'échéance
                                </div>
                                <div class="col-12">
                                    <strong class="text-primary">Garantie :</strong><br>
                                    <span class="fw-bold">Votre épargne constituée</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info border-0">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-calendar-check me-2"></i>Conditions d'Éligibilité
                            </h6>
                            <ul class="mb-0 small">
                                <li><strong>6 mois d'ancienneté</strong> pour cotisation journalière/hebdomadaire</li>
                                <li><strong>1 an d'ancienneté</strong> pour cotisation mensuelle</li>
                                <li>Épargne régulière et à jour</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="row g-3">
                        <!-- Crédits Court Terme -->
                        <div class="col-12">
                            <div class="p-4 bg-white rounded-4 shadow">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-clock text-white fs-4"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">🆘 Assistance de 1 à 30 jours</h5>
                                        <p class="text-muted small mb-0">Besoin urgent d'argent ?</p>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Loyer et factures</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Aléas sanitaires</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Approvisionnement de vivres</li>
                                    <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Fêtes de fin d'année</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Crédits Long Terme -->
                        <div class="col-12">
                            <div class="p-4 bg-white rounded-4 shadow">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <div class="bg-success bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-rocket text-white fs-4"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">🚀 Grands projets de 1 à 2 ans</h5>
                                        <p class="text-muted small mb-0">Réalisez vos ambitions</p>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Terrain et construction</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Extension d'entreprise</li>
                                    <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Préparatifs de voyage</li>
                                    <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Achat de moyen roulant</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Exemple de Calcul -->
                        <div class="col-12">
                            <div class="p-4 bg-white rounded-4 shadow">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-calculator me-2"></i>Exemple de Calcul
                                </h6>
                                <p class="mb-2"><strong>Crédit de 100 000 FCFA :</strong></p>
                                <ul class="list-unstyled small">
                                    <li class="mb-1">• Intérêts : 5 000 FCFA (5%)</li>
                                    <li class="mb-1">• Total à rembourser : 105 000 FCFA</li>
                                    <li class="mb-0 text-danger">• Si retard : +25% sur l'échéance impayée</li>
                                </ul>
                            </div>
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
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h2 class="fw-bold mb-3 text-white">
                        <i class="fas fa-rocket me-2"></i>Prêt à transformer vos rêves en réalité ?
                    </h2>
                    <p class="lead mb-0 text-white">SIFCash, votre partenaire financier de confiance pour l'épargne et le crédit.</p>
                </div>
                <div class="col-lg-4 text-center" data-aos="fade-left">
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-light btn-lg shadow">
                            <i class="fas fa-user-plus me-2"></i>Devenir Adhérent
                        </a>
                        <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-phone me-2"></i>Contactez-nous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
</html><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/services.blade.php ENDPATH**/ ?>