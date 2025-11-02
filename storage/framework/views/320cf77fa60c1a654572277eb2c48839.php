<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de Confidentialité - SIFcash-Burkina</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('img/SIF logo .jpg')); ?>">
    <link rel="shortcut icon" type="image/jpeg" href="<?php echo e(asset('img/SIF logo .jpg')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        .page-header {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #007bff 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="%23ffffff05" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }
        .page-header .container {
            position: relative;
            z-index: 2;
        }
        .content-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
        }
        .section-title {
            color: #007bff;
            border-left: 4px solid #007bff;
            padding-left: 1rem;
        }
        .data-category {
            background: linear-gradient(135deg, rgba(0,123,255,0.1) 0%, rgba(0,123,255,0.05) 100%);
            border: 1px solid rgba(0,123,255,0.2);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
        }
        .rights-item {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 0 10px 10px 0;
        }
        .security-measure {
            background: linear-gradient(135deg, rgba(40,167,69,0.1) 0%, rgba(40,167,69,0.05) 100%);
            border: 1px solid rgba(40,167,69,0.2);
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
        }
        .sidebar-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 15px;
        }
        .nav-pills .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link:hover,
        .nav-pills .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        html {
            scroll-behavior: smooth;
        }
        /* Ajuster le scroll pour compenser le header fixe */
        h2[id] {
            scroll-margin-top: 100px;
        }
        .gdpr-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Header -->
    <div class="page-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8" data-aos="fade-right">
                    <div class="gdpr-badge d-inline-block mb-3">
                        <i class="fas fa-shield-alt me-2"></i>Conforme RGPD
                    </div>
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-user-shield me-3"></i>
                        Politique de Confidentialité
                    </h1>
                    <p class="lead mb-0 opacity-75">Protection et gestion transparente de vos données personnelles chez SIFcash-Burkina</p>
                </div>
                <div class="col-md-4 text-center" data-aos="fade-left">
                    <div class="d-inline-block">
                        <i class="fas fa-lock fa-4x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card" data-aos="fade-up">
                    <div class="card-body p-5">
                        <p class="text-muted mb-4">Dernière mise à jour : <?php echo e(date('d/m/Y')); ?></p>

                        <!-- Section Introduction -->
                        <h2 class="section-title mb-4" id="engagement">1. ENGAGEMENT DE CONFIDENTIALITÉ</h2>
                        <div class="data-category mb-5">
                            <p class="mb-3">Chez <strong>SIFcash-Burkina</strong>, la protection de vos données personnelles est une priorité absolue. Cette politique de confidentialité détaille notre approche transparente et responsable de la collecte, du traitement et de la protection de vos informations personnelles.</p>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Notre Engagement :</h6>
                                <p class="mb-0">Nous nous engageons à respecter les standards internationaux de protection des données et à maintenir la transparence dans tous nos traitements.</p>
                            </div>
                        </div>

                        <!-- Section 2 -->
                        <h2 class="section-title mb-4" id="donnees">2. DONNÉES COLLECTÉES</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">2.1 Informations d'Identification</h5>
                            <div class="data-category">
                                <h6><i class="fas fa-user me-2"></i>Données Personnelles :</h6>
                                <ul class="mb-3">
                                    <li>Nom et prénom complets</li>
                                    <li>Date et lieu de naissance</li>
                                    <li>Numéro de CNI ou passeport</li>
                                    <li>Nationalité et statut résidentiel</li>
                                </ul>
                                
                                <h6><i class="fas fa-address-card me-2"></i>Informations de Contact :</h6>
                                <ul class="mb-0">
                                    <li>Adresse de résidence complète</li>
                                    <li>Numéros de téléphone (fixe et mobile)</li>
                                    <li>Adresse email principale</li>
                                    <li>Coordonnées professionnelles si applicable</li>
                                </ul>
                            </div>
                            
                            <h5 class="text-primary mb-3 mt-4">2.2 Informations Financières</h5>
                            <div class="data-category">
                                <h6><i class="fas fa-coins me-2"></i>Situation Financière :</h6>
                                <ul class="mb-3">
                                    <li>Revenus mensuels et sources de revenus</li>
                                    <li>Situation professionnelle et employeur</li>
                                    <li>Historique de crédit si disponible</li>
                                    <li>Autres engagements financiers</li>
                                </ul>
                                
                                <h6><i class="fas fa-file-alt me-2"></i>Documents Justificatifs :</h6>
                                <ul class="mb-0">
                                    <li>Justificatifs de revenus (bulletins de salaire, attestations)</li>
                                    <li>Justificatifs de domicile récents</li>
                                    <li>Pièces d'identité officielles</li>
                                    <li>Autres documents selon le type de service</li>
                                </ul>
                            </div>
                            
                            <h5 class="text-primary mb-3 mt-4">2.3 Données Techniques</h5>
                            <div class="data-category">
                                <ul>
                                    <li>Adresse IP et informations de connexion</li>
                                    <li>Type de navigateur et système d'exploitation</li>
                                    <li>Horodatage des connexions et actions</li>
                                    <li>Cookies et identifiants de session</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 3 -->
                        <h2 class="section-title mb-4" id="finalites">3. FINALITÉS DU TRAITEMENT</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Nous traitons vos données pour :</h5>
                            <div class="data-category">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-handshake text-success me-2"></i>Services Principaux :</h6>
                                        <ul>
                                            <li>Gestion des comptes d'épargne</li>
                                            <li>Octroi et suivi des crédits</li>
                                            <li>Calcul et versement des intérêts</li>
                                            <li>Traitement des opérations financières</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-shield-alt text-warning me-2"></i>Conformité et Sécurité :</h6>
                                        <ul>
                                            <li>Vérification d'identité (KYC)</li>
                                            <li>Lutte contre le blanchiment (LCB-FT)</li>
                                            <li>Respect des obligations réglementaires</li>
                                            <li>Prévention de la fraude</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4 -->
                        <h2 class="section-title mb-4" id="partage">4. PARTAGE ET TRANSMISSION</h2>
                        <div class="mb-5">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-lock me-2"></i>Principe Fondamental :</h6>
                                <p class="mb-0"><strong>SIFcash-Burkina ne vend jamais vos données personnelles à des tiers.</strong> Vos informations ne sont partagées que dans des cas spécifiques et encadrés.</p>
                            </div>
                            
                            <h5 class="text-primary mb-3">Cas de Partage Autorisés :</h5>
                            <div class="data-category">
                                <ul>
                                    <li><strong>Consentement explicite :</strong> Avec votre autorisation écrite préalable</li>
                                    <li><strong>Obligations légales :</strong> Réquisitions judiciaires, contrôles réglementaires</li>
                                    <li><strong>Prestataires de services :</strong> Sous contrats stricts de confidentialité</li>
                                    <li><strong>Partenaires financiers :</strong> Pour des services complémentaires avec votre accord</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section 5 -->
                        <h2 class="section-title mb-4" id="securite">5. MESURES DE SÉCURITÉ</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Protection Technique :</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="security-measure">
                                        <h6><i class="fas fa-key text-success me-2"></i>Chiffrement</h6>
                                        <p class="small mb-0">Chiffrement SSL/TLS pour toutes les communications et stockage sécurisé des données sensibles.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="security-measure">
                                        <h6><i class="fas fa-eye text-success me-2"></i>Surveillance</h6>
                                        <p class="small mb-0">Monitoring 24h/24 de nos systèmes et détection proactive des tentatives d'intrusion.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="security-measure">
                                        <h6><i class="fas fa-users text-success me-2"></i>Accès Restreint</h6>
                                        <p class="small mb-0">Contrôle strict des accès et authentification à double facteur pour le personnel autorisé.</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="security-measure">
                                        <h6><i class="fas fa-database text-success me-2"></i>Sauvegarde</h6>
                                        <p class="small mb-0">Sauvegardes chiffrées régulières et plan de continuité d'activité en cas d'incident.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6 -->
                        <h2 class="section-title mb-4" id="conservation">6. CONSERVATION DES DONNÉES</h2>
                        <div class="mb-5">
                            <div class="data-category">
                                <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Durées de Conservation :</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Données Actives :</h6>
                                        <ul class="small">
                                            <li>Comptes actifs : Durée de la relation</li>
                                            <li>Historique des transactions : 10 ans</li>
                                            <li>Documents KYC : 5 ans après clôture</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Données Archivées :</h6>
                                        <ul class="small">
                                            <li>Obligations fiscales : 10 ans</li>
                                            <li>Contentieux : Jusqu'à résolution</li>
                                            <li>Données marketing : 3 ans maximum</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <h6><i class="fas fa-info-circle me-2"></i>Suppression Automatique :</h6>
                                    <p class="mb-0">Nos systèmes suppriment automatiquement les données expirées selon les durées légales et réglementaires.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7 -->
                        <h2 class="section-title mb-4" id="droits">7. VOS DROITS</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Droits Fondamentaux :</h5>
                            
                            <div class="rights-item">
                                <h6><i class="fas fa-search me-2"></i>Droit d'Accès</h6>
                                <p class="mb-0 small">Obtenir une copie de toutes les données vous concernant que nous détenons, dans un délai de 15 jours.</p>
                            </div>
                            
                            <div class="rights-item">
                                <h6><i class="fas fa-edit me-2"></i>Droit de Rectification</h6>
                                <p class="mb-0 small">Corriger ou mettre à jour vos informations personnelles inexactes ou incomplètes.</p>
                            </div>
                            
                            <div class="rights-item">
                                <h6><i class="fas fa-trash-alt me-2"></i>Droit à l'Effacement</h6>
                                <p class="mb-0 small">Demander la suppression de vos données, sauf obligations légales de conservation.</p>
                            </div>
                            
                            <div class="rights-item">
                                <h6><i class="fas fa-download me-2"></i>Droit à la Portabilité</h6>
                                <p class="mb-0 small">Récupérer vos données dans un format structuré et standard pour les transférer ailleurs.</p>
                            </div>
                            
                            <div class="rights-item">
                                <h6><i class="fas fa-stop-circle me-2"></i>Droit d'Opposition</h6>
                                <p class="mb-0 small">Vous opposer à certains traitements de vos données, notamment à des fins de prospection.</p>
                            </div>
                        </div>

                        <!-- Section 8 -->
                        <h2 class="section-title mb-4" id="cookies">8. COOKIES ET TECHNOLOGIES</h2>
                        <div class="mb-5">
                            <div class="data-category">
                                <h5 class="mb-3"><i class="fas fa-cookie-bite me-2"></i>Types de Cookies Utilisés :</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Cookies Essentiels :</h6>
                                        <ul class="small">
                                            <li>Authentification et sécurité</li>
                                            <li>Fonctionnement du site</li>
                                            <li>Sessions utilisateur</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Cookies d'Amélioration :</h6>
                                        <ul class="small">
                                            <li>Statistiques d'utilisation</li>
                                            <li>Préférences utilisateur</li>
                                            <li>Performances du site</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <h6><i class="fas fa-cog me-2"></i>Gestion des Cookies :</h6>
                                    <p class="mb-0">Vous pouvez contrôler et supprimer les cookies via les paramètres de votre navigateur. Certains cookies étant essentiels, leur désactivation peut affecter le fonctionnement du site.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 9 -->
                        <h2 class="section-title mb-4" id="contact">9. MODIFICATIONS ET CONTACT</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Mises à Jour de la Politique :</h5>
                            <div class="data-category">
                                <p class="mb-3">Cette politique peut être modifiée pour refléter les évolutions légales, réglementaires ou opérationnelles.</p>
                                
                                <h6>Notification des Changements :</h6>
                                <ul>
                                    <li>Email de notification 30 jours avant l'entrée en vigueur</li>
                                    <li>Publication sur notre site web</li>
                                    <li>Information en agence pour les modifications importantes</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Section Contact -->
                        <div class="data-category">
                            <h5 class="mb-3"><i class="fas fa-envelope me-2"></i>Contact et Exercice des Droits</h5>
                            <p class="mb-3">Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits :</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Responsable de la Protection des Données :</h6>
                                    <p class="mb-2"><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:dpo@sifcash-burkina.bf" class="text-decoration-none">dpo@sifcash-burkina.bf</a></p>
                                    <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i>
                                        <a href="tel:+22625456364" class="text-decoration-none">+226 25 45 63 64</a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Service Client :</h6>
                                    <p class="mb-2"><i class="fas fa-envelope text-primary me-2"></i><a href="mailto:contact@sifcash-burkina.bf" class="text-decoration-none">contact@sifcash-burkina.bf</a></p>
                                    <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i>
                                        <a href="tel:+22625456364" class="text-decoration-none">+226 25 45 63 64</a> /
                                        <a href="tel:+22676182726" class="text-decoration-none">76 18 27 26</a> /
                                        <a href="tel:+22604370203" class="text-decoration-none">04 37 02 03</a>
                                    </p>
                                    <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Ouagadougou, Burkina Faso</p>
                                </div>
                            </div>
                            
                            <div class="alert alert-success mt-3">
                                <h6><i class="fas fa-clock me-2"></i>Délai de Réponse :</h6>
                                <p class="mb-0">Nous nous engageons à répondre à vos demandes dans un délai maximum de 15 jours ouvrables.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sidebar-card sticky-top" style="top: 2rem;" data-aos="fade-left">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">
                            <i class="fas fa-list me-2"></i>Sommaire
                        </h5>
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item mb-2">
                                <a class="nav-link active" href="#engagement">
                                    <i class="fas fa-shield-alt me-2"></i>Engagement
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#donnees">
                                    <i class="fas fa-database me-2"></i>Données Collectées
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#finalites">
                                    <i class="fas fa-target me-2"></i>Finalités
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#partage">
                                    <i class="fas fa-share-alt me-2"></i>Partage
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#securite">
                                    <i class="fas fa-lock me-2"></i>Sécurité
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#conservation">
                                    <i class="fas fa-calendar-alt me-2"></i>Conservation
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#droits">
                                    <i class="fas fa-user-check me-2"></i>Vos Droits
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#cookies">
                                    <i class="fas fa-cookie-bite me-2"></i>Cookies
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#contact">
                                    <i class="fas fa-envelope me-2"></i>Contact
                                </a>
                            </li>
                        </ul>
                        
                        <div class="mt-4 p-4" style="background: rgba(255,255,255,0.1); border-radius: 10px;">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i>Points Clés
                            </h6>
                            <div class="mb-3">
                                <small class="opacity-75">Vos données</small>
                                <div class="fw-bold small">Jamais vendues à des tiers</div>
                            </div>
                            <div class="mb-3">
                                <small class="opacity-75">Sécurité</small>
                                <div class="fw-bold small">Chiffrement SSL/TLS</div>
                            </div>
                            <div class="mb-3">
                                <small class="opacity-75">Réponse</small>
                                <div class="fw-bold small">Sous 15 jours</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px;">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-user-shield me-2"></i>DPO Contact
                            </h6>
                            <p class="small mb-3 opacity-75">Délégué à la Protection des Données</p>
                            <div class="d-grid gap-2">
                                <a href="mailto:dpo@sifcash-burkina.bf" class="btn btn-light btn-sm">
                                    <i class="fas fa-envelope me-1"></i>dpo@sifcash-burkina.bf
                                </a>
                                <a href="<?php echo e(route('contact')); ?>" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-phone me-1"></i>Nous contacter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            offset: 100,
            easing: 'ease-out-cubic',
            once: true
        });
    </script>
</body>
</html>
<?php /**PATH C:\Mes Sites Web\sif-project\resources\views/privacy.blade.php ENDPATH**/ ?>