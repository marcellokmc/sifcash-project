<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Hero Section Contact -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Contactez-nous</h1>
                    <p class="lead">Notre équipe est à votre disposition pour répondre à toutes vos questions</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-phone-alt fa-4x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Contact Principal -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Informations de Contact -->
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold mb-4">Nos Coordonnées</h2>
                    <p class="lead text-muted mb-4">
                        Contactez-nous dès maintenant pour plus d'informations sur nos services d'épargne personnalisée.
                    </p>

                    <!-- Contact Cards -->
                    <div class="contact-cards">
                        <!-- Téléphones -->
                        <div class="contact-card p-4 mb-4 bg-light rounded-4 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-4">
                                    <i class="fas fa-phone fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2">Téléphones</h5>
                                    <div class="phone-numbers">
                                        <p class="mb-1">
                                            <a href="tel:+22625456364" class="text-decoration-none text-dark fw-bold">
                                                <i class="fas fa-mobile-alt me-2"></i>+226 25 45 63 64
                                            </a>
                                        </p>
                                        <p class="mb-1">
                                            <a href="tel:+22676182726" class="text-decoration-none text-dark fw-bold">
                                                <i class="fas fa-mobile-alt me-2"></i>76 18 27 26
                                            </a>
                                        </p>
                                        <p class="mb-0">
                                            <a href="tel:+22604370203" class="text-decoration-none text-dark fw-bold">
                                                <i class="fas fa-phone me-2"></i>04 37 02 03
                                            </a>
                                        </p>
                                    </div>
                                    <p class="small text-muted mb-0 mt-2">
                                        <i class="fas fa-clock me-1"></i>
                                        Disponible 7j/7 de 8h à 18h
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="contact-card p-4 mb-4 bg-light rounded-4 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="contact-icon me-4">
                                    <i class="fas fa-envelope fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2">Email</h5>
                                    <p class="mb-1">
                                        <a href="mailto:contact@sifcash-burkina.com" class="text-decoration-none text-dark fw-bold">
                                            contact@sifcash-burkina.com
                                        </a>
                                    </p>
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-reply me-1"></i>
                                        Réponse sous 24h
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Localisation -->
                        <div class="contact-card p-4 mb-4 bg-light rounded-4 shadow-sm">
                            <div class="d-flex align-items-start">
                                <div class="contact-icon me-4">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2">Localisation</h5>
                                    <p class="mb-2 text-dark fw-bold">Ouagadougou, Burkina Faso</p>
                                    <p class="small text-muted mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Adresse exacte communiquée lors de la prise de rendez-vous
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Réseaux Sociaux -->
                    <div class="social-contact mt-4">
                        <h5 class="fw-bold mb-3">Suivez-nous</h5>
                        <div class="d-flex gap-3">
                            <a href="https://web.facebook.com/profile.php?id=61575828571036" target="_blank" class="btn btn-outline-primary btn-lg">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://api.whatsapp.com/send?phone=22671337005" target="_blank" class="btn btn-outline-success btn-lg">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="mailto:contact@sifcash-burkina.com" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <a href="tel:+22625456364" class="btn btn-outline-dark btn-lg">
                                <i class="fas fa-phone"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de Contact -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-form-card bg-white p-4 rounded-4 shadow-lg border">
                        <h2 class="fw-bold mb-4 text-center">Envoyez-nous un message</h2>
                        
                        <form id="contactForm">
                            <div class="row g-3">
                                <!-- Nom et Prénom -->
                                <div class="col-md-6">
                                    <label for="firstName" class="form-label fw-bold">Prénom *</label>
                                    <input type="text" class="form-control form-control-lg" id="firstName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lastName" class="form-label fw-bold">Nom *</label>
                                    <input type="text" class="form-control form-control-lg" id="lastName" required>
                                </div>

                                <!-- Email et Téléphone -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email *</label>
                                    <input type="email" class="form-control form-control-lg" id="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">Téléphone</label>
                                    <input type="tel" class="form-control form-control-lg" id="phone">
                                </div>

                                <!-- Type de demande -->
                                <div class="col-12">
                                    <label for="requestType" class="form-label fw-bold">Type de demande *</label>
                                    <select class="form-select form-select-lg" id="requestType" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="epargne">Épargne personnalisée</option>
                                        <option value="credit">Services de crédit</option>
                                        <option value="conseil">Conseil financier</option>
                                        <option value="formation">Formation financière</option>
                                        <option value="information">Demande d'information</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <!-- Objet -->
                                <div class="col-12">
                                    <label for="subject" class="form-label fw-bold">Objet *</label>
                                    <input type="text" class="form-control form-control-lg" id="subject" 
                                           placeholder="Ex: Demande d'information sur l'épargne" required>
                                </div>

                                <!-- Message -->
                                <div class="col-12">
                                    <label for="message" class="form-label fw-bold">Message *</label>
                                    <textarea class="form-control form-control-lg" id="message" rows="5" 
                                              placeholder="Décrivez votre demande en détail..." required></textarea>
                                </div>

                                <!-- Préférences de contact -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Préférence de contact</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="contactPreference" 
                                                       id="prefPhone" value="phone" checked>
                                                <label class="form-check-label" for="prefPhone">
                                                    <i class="fas fa-phone me-1"></i>Téléphone
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="contactPreference" 
                                                       id="prefEmail" value="email">
                                                <label class="form-check-label" for="prefEmail">
                                                    <i class="fas fa-envelope me-1"></i>Email
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="contactPreference" 
                                                       id="prefWhatsapp" value="whatsapp">
                                                <label class="form-check-label" for="prefWhatsapp">
                                                    <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton d'envoi -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Horaires et FAQ -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-5">
                <!-- Horaires -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Horaires d'ouverture
                        </h3>
                        
                        <div class="hours-list">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-bold">Lundi - Vendredi</span>
                                <span class="text-primary fw-bold">8h00 - 17h00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="fw-bold">Samedi</span>
                                <span class="text-primary fw-bold">8h00 - 12h00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="fw-bold">Dimanche</span>
                                <span class="text-danger fw-bold">Fermé</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded">
                            <p class="mb-0 small">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>Service client téléphonique disponible 7j/7</strong> 
                                pour les urgences et renseignements
                            </p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Rapide -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="bg-white p-4 rounded-4 shadow-sm">
                        <h3 class="fw-bold mb-4">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Questions Fréquentes
                        </h3>
                        
                        <div class="accordion" id="contactFAQ">
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header" id="faq1">
                                    <button class="accordion-button collapsed bg-light" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse1">
                                        Comment commencer à épargner ?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        Contactez-nous par téléphone ou email pour discuter de vos besoins. 
                                        Nous vous aiderons à personnaliser votre plan d'épargne.
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header" id="faq2">
                                    <button class="accordion-button collapsed bg-light" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse2">
                                        Quels sont les montants minimum ?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        <strong>Montants minimum :</strong><br>
                                        • Option mensuelle : 5,000 FCFA<br>
                                        • Option hebdomadaire : 7,500 FCFA<br>
                                        • Option journalière : 1,000 FCFA
                                    </div>
                                </div>
                            </div>
                            
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="faq3">
                                    <button class="accordion-button collapsed bg-light" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse3">
                                        Quels sont les intérêts et frais ?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#contactFAQ">
                                    <div class="accordion-body">
                                        <strong>Taux d'intérêt :</strong> 3% annuel brut<br>
                                        <strong>Capital :</strong> Payable uniquement à l'échéance<br>
                                        <small class="text-success">Intérêts calculés au prorata temporis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <h2 class="fw-bold mb-3">Prêt à commencer votre projet d'épargne ?</h2>
                <p class="lead mb-4">
                    SIFCash, la source fiable de vos projets financiers. Contactez-nous maintenant !
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="tel:+22625456364" class="btn btn-light btn-lg">
                        <i class="fas fa-phone me-2"></i>
                        Appelez maintenant
                    </a>
                    <a href="mailto:contact@sifcash-burkina.com" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        Envoyez un email
                    </a>
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

        // Form submission handling
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
            btn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Message envoyé avec succès !';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
                
                // Show success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show mt-3';
                successAlert.innerHTML = `
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Message envoyé !</strong> Nous vous recontacterons dans les plus brefs délais.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                this.appendChild(successAlert);
                
                // Reset form after 3 seconds
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-primary');
                    this.reset();
                    successAlert.remove();
                }, 3000);
            }, 2000);
        });

        // Contact cards hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const contactCards = document.querySelectorAll('.contact-card');
            contactCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 30px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,0.075)';
                });
            });
        });
    </script>

    <style>
        .contact-card {
            transition: all 0.3s ease;
        }

        .contact-icon {
            transition: transform 0.3s ease;
        }

        .contact-card:hover .contact-icon i {
            transform: scale(1.1) rotate(5deg);
        }

        .contact-form-card {
            border-left: 5px solid #007bff;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .accordion-button:not(.collapsed) {
            background-color: #007bff;
            color: white;
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .phone-numbers a:hover {
            color: #007bff !important;
            transform: translateX(5px);
        }

        .social-contact a {
            transition: all 0.3s ease;
        }

        .social-contact a:hover {
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .contact-form-card {
                margin-top: 2rem;
            }
        }
    </style>
</body>
</html><?php /**PATH C:\Mes Sites Web\sif-project\resources\views/contact.blade.php ENDPATH**/ ?>