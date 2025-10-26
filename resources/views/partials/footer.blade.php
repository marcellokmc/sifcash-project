<!-- Footer amélioré -->
<footer class="bg-dark text-white">
    <!-- Section principale du footer -->
    <div class="container py-3">
        <div class="row gy-3">
            <!-- Colonne 1: À propos -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-section">
                    <h5 class="text-primary mb-3 d-flex align-items-center">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        SIFcash-Burkina
                    </h5>
                    <p class="text-white mb-3">
                        <strong>Source Inépuisable Financière (SIFCash)</strong> vous propose une solution simple et flexible 
                        pour atteindre vos objectifs financiers à court terme.
                    </p>
                    <p class="text-white small mb-3">
                        Aucun prélèvement sur le capital - Vous récupérez la totalité de votre épargne à l'échéance prévue.
                    </p>
                    <div class="social-links">
                        <a href="https://web.facebook.com/profile.php?id=61575828571036" target="_blank" class="text-white me-3" title="Facebook">
                            <i class="fab fa-facebook-f fa-lg"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=22671337005" target="_blank" class="text-white me-3" title="WhatsApp">
                            <i class="fab fa-whatsapp fa-lg"></i>
                        </a>
                        <a href="mailto:contact@sifcash-burkina.bf" class="text-white me-3" title="Email">
                            <i class="fas fa-envelope fa-lg"></i>
                        </a>
                        <a href="tel:+22625456364" class="text-white" title="Téléphone">
                            <i class="fas fa-phone fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Colonne 2: Nos Services -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-section">
                    <h6 class="text-white fw-bold mb-3">Nos Services</h6>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-piggy-bank me-2"></i>Épargne
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-handshake me-2"></i>Crédit
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-chart-line me-2"></i>Investissement
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-graduation-cap me-2"></i>Formation
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-mobile-alt me-2"></i>Mobile Money
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Colonne 3: Liens utiles -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-section">
                    <h6 class="text-white fw-bold mb-3">Liens Utiles</h6>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="{{ route('about') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-info-circle me-2"></i>À propos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('privacy') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-shield-alt me-2"></i>Confidentialité
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('data-protection') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-lock me-2"></i>Protection des données
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('terms') }}" class="text-decoration-none text-muted">
                                <i class="fas fa-file-contract me-2"></i>CGU
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-decoration-none text-muted">
                                <i class="fas fa-question-circle me-2"></i>FAQ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Colonne 4: Contact et horaires -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-section">
                    <h6 class="text-white fw-bold mb-3">Contactez-nous</h6>
                    
                    <!-- Informations de contact -->
                    <div class="contact-info mb-4">
                        <div class="contact-item d-flex align-items-start mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                            <div>
                                <strong class="text-white">Adresse</strong><br>
                                <span class="text-white small">Ouagadougou, Burkina Faso</span>
                            </div>
                        </div>
                        
                        <div class="contact-item d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <div>
                                <strong class="text-white">Téléphone</strong><br>
                                <div class="text-white small">
                                    <a href="tel:+22625456364" class="text-white text-decoration-none">50 77 15 97</a> / 
                                    <a href="tel:+22676182726" class="text-white text-decoration-none">76 18 27 26</a> / 
                                    <a href="tel:+22604370203" class="text-white text-decoration-none">04 37 02 03</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-item d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <strong class="text-white">Email</strong><br>
                                <a href="mailto:contact@sifcash-burkina.bf" class="text-white text-decoration-none small">contact@sifcash-burkina.bf</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Séparateur -->
    <hr class="border-secondary my-0">

    <!-- Section copyright et certifications -->
    <div class="container py-2">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="text-white small">
                    <!-- Version mobile -->
                    <div class="d-md-none">
                        <p class="mb-1"><strong>RCCM:</strong> 00235418M</p>
                        <p class="mb-1"><strong>Coris:</strong> N°10003 – 01348224001 – 86</p>
                        <p class="mb-1"><strong>BP:</strong> 01 BP 5368 Ouagadougou</p>
                        <p class="mb-2">&copy; {{ date('Y') }} <strong>SIFcash-Burkina</strong>. Tous droits réservés.</p>
                    </div>
                    
                    <!-- Version desktop -->
                    <div class="d-none d-md-block text-center">
                        <p class="mb-2">
                            <strong>RCCM:</strong> 00235418M | <strong>Coris:</strong> N°10003 – 01348224001 – 86 | 
                            <strong>BP:</strong> 01 BP 5368 Ouagadougou | 
                            &copy; {{ date('Y') }} <strong>SIFcash-Burkina</strong>. Tous droits réservés.
                        </p>
                    </div>
                    
                    <p class="mb-0 text-white text-center">
                        Développé avec <i class="fas fa-heart text-danger mx-1"></i> pour le Burkina Faso
                    </p>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <!-- Certifications et labels -->
                <div class="certifications d-flex justify-content-md-end justify-content-start align-items-center flex-wrap gap-3">
                    <div class="certification-item d-flex align-items-center">
                        <i class="fas fa-shield-alt text-success me-2"></i>
                        <span class="small text-white">Sécurisé</span>
                    </div>
                    <div class="certification-item d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="small text-white">Certifié</span>
                    </div>
                    <div class="certification-item d-flex align-items-center">
                        <i class="fas fa-user-shield text-primary me-2"></i>
                        <span class="small text-white">RGPD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton de retour en haut -->
    <button type="button" class="btn btn-primary btn-floating position-fixed bottom-0 end-0 m-4 shadow" 
            id="backToTop" style="z-index: 1000; display: none;" title="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<style>
/* Styles du footer */
.footer-section h5, .footer-section h6 {
    position: relative;
    padding-bottom: 0.5rem;
}

.footer-section h5::after, .footer-section h6::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    border-radius: 1px;
}

.footer-links a {
    transition: all 0.3s ease;
    position: relative;
    display: inline-block;
}

.footer-links a {
    color: #ffffff !important;
}

.footer-links a:hover {
    color: #007bff !important;
    transform: translateX(5px);
}

.social-links a {
    display: inline-block;
    transition: all 0.3s ease;
    opacity: 0.8;
}

.social-links a:hover {
    opacity: 1;
    transform: translateY(-3px);
    color: #007bff !important;
}

.contact-item {
    transition: all 0.3s ease;
}

.contact-item:hover {
    transform: translateX(5px);
}

.contact-item a {
    color: #ffffff !important;
}

.contact-item a:hover {
    color: #007bff !important;
}

.certification-item {
    transition: all 0.3s ease;
}

.certification-item:hover {
    transform: scale(1.1);
}

.btn-floating {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex !important;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
}

.btn-floating:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4) !important;
}

.opening-hours .text-muted {
    font-size: 0.85rem;
}

/* Animation d'apparition */
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

.footer-section {
    animation: fadeInUp 0.6s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .social-links {
        text-align: center;
        margin-top: 1rem;
    }
    
    .certifications {
        justify-content: center !important;
        margin-top: 1rem;
    }
    
    .contact-info {
        text-align: center;
    }
}
</style>

<script>
// Script pour le bouton "Retour en haut"
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('backToTop');
    
    // Afficher/masquer le bouton selon le scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.display = 'flex';
        } else {
            backToTopBtn.style.display = 'none';
        }
    });
    
    // Action du bouton
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>