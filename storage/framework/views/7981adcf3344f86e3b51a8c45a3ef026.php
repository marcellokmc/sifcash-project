<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions d'Utilisation - SIFcash-Burkina</title>
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
        .highlight-box {
            background: linear-gradient(135deg, rgba(0,123,255,0.1) 0%, rgba(0,123,255,0.05) 100%);
            border: 1px solid rgba(0,123,255,0.2);
            border-radius: 10px;
            padding: 1.5rem;
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
        .definition-term {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 0 10px 10px 0;
        }
        .company-info {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
        }
        html {
            scroll-behavior: smooth;
        }
        h2[id] {
            scroll-margin-top: 100px;
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
                    <h1 class="display-5 fw-bold mb-3">
                        <i class="fas fa-file-contract me-3"></i>
                        Conditions Générales d'Utilisation
                    </h1>
                    <p class="lead mb-0 opacity-75">Termes et conditions régissant l'utilisation des services SIFcash-Burkina</p>
                </div>
                <div class="col-md-4 text-center" data-aos="fade-left">
                    <div class="d-inline-block">
                        <i class="fas fa-balance-scale fa-4x opacity-50"></i>
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
                        
                        <!-- Section Préambule -->
                        <h2 class="section-title mb-4" id="preambule">PRÉAMBULE</h2>
                        <div class="highlight-box mb-5">
                            <p class="mb-3"><strong>SIFcash-Burkina</strong> est une institution de microfinance innovante qui révolutionne l'accès aux services financiers au Burkina Faso. En tant que système coopératif d'épargne et de crédit, nous nous engageons à offrir des solutions financières inclusives et transparentes à nos membres.</p>
                            
                            <div class="company-info">
                                <h5 class="mb-3"><i class="fas fa-building me-2"></i>Informations Légales de la Société</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Dénomination :</strong> SIFcash-Burkina</p>
                                        <p><strong>Forme juridique :</strong> Société à Responsabilité Limitée (SARL)</p>
                                        <p><strong>Siège social :</strong> Ouagadougou, Burkina Faso</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>RCCM :</strong> BF.OUA.2023.B.6789</p>
                                        <p><strong>IFU :</strong> 00234567A</p>
                                        <p><strong>Capital social :</strong> 1 277 500 000 FCFA</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Article 1 -->
                        <h2 class="section-title mb-4" id="definitions">ARTICLE 1 : DÉFINITIONS</h2>
                        <div class="mb-5">
                            <div class="definition-term">
                                <strong>SIFcash-Burkina :</strong> Société à responsabilité limitée proposant des services de microfinance et d'épargne coopérative.
                            </div>
                            <div class="definition-term">
                                <strong>Membre/Adhérent :</strong> Toute personne physique ayant souscrit aux services et accepté les présentes conditions.
                            </div>
                            <div class="definition-term">
                                <strong>Épargne :</strong> Montants déposés par le membre selon les modalités définies (mensuelle, hebdomadaire, journalière).
                            </div>
                            <div class="definition-term">
                                <strong>Intérêts sur épargne :</strong> Rémunération calculée sur la base d'un taux annuel brut de 3% sur les sommes épargnées.
                            </div>
                            <div class="definition-term">
                                <strong>Intérêts sur crédit :</strong> Taux d'intérêt de 5% appliqué sur les crédits accordés.
                            </div>
                            <div class="definition-term">
                                <strong>Capital :</strong> Montant total épargné par le membre, payable uniquement à l'échéance du contrat.
                            </div>
                        </div>

                        <!-- Article 2 -->
                        <h2 class="section-title mb-4" id="objet">ARTICLE 2 : OBJET ET CHAMP D'APPLICATION</h2>
                        <div class="mb-5">
                            <p class="mb-3">Les présentes conditions générales régissent l'utilisation des services de SIFcash-Burkina et définissent les droits et obligations de chaque partie.</p>
                            
                            <h5 class="text-primary mb-3">Nos Services :</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Épargne coopérative avec intérêts de 3% annuel brut</li>
                                <li><i class="fas fa-check text-success me-2"></i>Plans d'épargne flexibles (mensuel, hebdomadaire, journalier)</li>
                                <li><i class="fas fa-check text-success me-2"></i>Microcrédits adaptés aux besoins locaux avec taux de 5%</li>
                                <li><i class="fas fa-check text-success me-2"></i>Services financiers numériques</li>
                                <li><i class="fas fa-check text-success me-2"></i>Accompagnement et conseil financier</li>
                            </ul>
                        </div>

                        <!-- Article 3 -->
                        <h2 class="section-title mb-4" id="adhesion">ARTICLE 3 : CONDITIONS D'ADHÉSION</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Conditions requises :</h5>
                            <ul>
                                <li>Être âgé de 18 ans révolus</li>
                                <li>Résider au Burkina Faso</li>
                                <li>Fournir les pièces justificatives demandées</li>
                                <li>Accepter les présentes conditions générales</li>
                                <li>Verser les frais de dossier de 5 000 FCFA</li>
                            </ul>
                            
                            <div class="alert alert-info mt-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Montants Minimums par Option :</h6>
                                <ul class="mb-0">
                                    <li><strong>Option Mensuelle :</strong> 5 000 FCFA minimum</li>
                                    <li><strong>Option Hebdomadaire :</strong> 7 500 FCFA minimum</li>
                                    <li><strong>Option Journalière :</strong> 1 000 FCFA minimum</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Article 4 -->
                        <h2 class="section-title mb-4" id="finances">ARTICLE 4 : CONDITIONS FINANCIÈRES</h2>
                        <div class="mb-5">
                            <div class="highlight-box mb-4">
                                <h5 class="mb-3"><i class="fas fa-piggy-bank me-2"></i>Épargne</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Taux et Conditions :</h6>
                                        <ul>
                                            <li><strong>Taux d'intérêt :</strong> 3% annuel brut</li>
                                            <li><strong>Frais de dossier :</strong> 5 000 FCFA</li>
                                            <li><strong>Capital :</strong> Garanti et payable à l'échéance uniquement</li>
                                            <li><strong>Intérêts :</strong> Calculés mensuellement</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Exemple de Calcul :</h6>
                                        <p class="small">Pour un dépôt de 100 000 FCFA :</p>
                                        <ul class="small">
                                            <li>Intérêts annuels : 3 000 FCFA</li>
                                            <li>Intérêts mensuels : 250 FCFA</li>
                                            <li>Capital garanti et restitué à l'échéance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="highlight-box mb-4">
                                <h5 class="mb-3"><i class="fas fa-hand-holding-usd me-2"></i>Crédit</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Taux et Conditions :</h6>
                                        <ul>
                                            <li><strong>Taux d'intérêt appliqué :</strong> 5%</li>
                                            <li><strong>Pénalité de retard :</strong> 25% sur le montant de l'échéance impayé</li>
                                            <li><strong>Remboursement :</strong> Selon échéancier convenu</li>
                                            <li><strong>Garantie :</strong> Épargne constituée</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Exemple de Calcul :</h6>
                                        <p class="small">Pour un crédit de 100 000 FCFA :</p>
                                        <ul class="small">
                                            <li>Intérêts : 5 000 FCFA (5%)</li>
                                            <li>Montant total à rembourser : 105 000 FCFA</li>
                                            <li>En cas de retard : Pénalité de 25% sur l'échéance impayée</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-3 mb-0">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Important - Pénalités de retard :</h6>
                                    <p class="mb-0">En cas de non-remboursement d'une échéance, une pénalité de <strong>25%</strong> sera appliquée sur le montant de l'échéance impayée, en plus des intérêts dus.</p>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mb-0">
                                <h6><i class="fas fa-money-bill-transfer me-2"></i>Frais de transfert et de retrait :</h6>
                                <p class="mb-0">Les frais de transfert et de retrait sont à la charge du client si applicable. Ces frais varient selon le mode de paiement et l'opérateur utilisé.</p>
                            </div>
                        </div>

                        <!-- Article 5 -->
                        <h2 class="section-title mb-4" id="engagements">ARTICLE 5 : ENGAGEMENTS DE L'ADHÉRENT</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">L'adhérent s'engage à :</h5>
                            <ul>
                                <li>Effectuer ses versements aux dates convenues</li>
                                <li>Fournir des informations exactes et complètes</li>
                                <li>Signaler tout changement de situation</li>
                                <li>Respecter les règlements intérieurs</li>
                                <li>Utiliser les services de manière conforme à leur destination</li>
                            </ul>
                            
                            <div class="alert alert-warning mt-4">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Important :</h6>
                                <p class="mb-0">Tout manquement aux engagements peut entraîner la suspension ou la résiliation du contrat d'adhésion.</p>
                            </div>
                        </div>

                        <!-- Article 6 -->
                        <h2 class="section-title mb-4" id="reclamations">ARTICLE 6 : MODALITÉS DE RÉCLAMATION ET REMBOURSEMENT</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Procédure de Réclamation :</h5>
                            <ol>
                                <li>Dépôt de réclamation écrite dans les 30 jours</li>
                                <li>Examen de la demande sous 15 jours ouvrables</li>
                                <li>Réponse motivée de SIFcash-Burkina</li>
                                <li>En cas de désaccord, médiation possible</li>
                            </ol>
                            
                            <h5 class="text-primary mb-3 mt-4">Conditions de Remboursement :</h5>
                            <ul>
                                <li>Remboursement anticipé possible avec pénalités</li>
                                <li>Délai de traitement : 7 à 15 jours ouvrables</li>
                                <li>Retenue des frais administratifs le cas échéant</li>
                                <li>Justificatifs requis selon les montants</li>
                            </ul>
                        </div>

                        <!-- Article 7 -->
                        <h2 class="section-title mb-4" id="donnees">ARTICLE 7 : PROTECTION DES DONNÉES</h2>
                        <div class="mb-5">
                            <p class="mb-3">SIFcash-Burkina s'engage à protéger les données personnelles de ses membres conformément aux réglementations en vigueur.</p>
                            
                            <h5 class="text-primary mb-3">Nos Engagements :</h5>
                            <ul>
                                <li>Collecte limitée aux besoins du service</li>
                                <li>Sécurisation des données par cryptage</li>
                                <li>Pas de transmission à des tiers sans accord</li>
                                <li>Droit d'accès et de rectification</li>
                                <li>Conservation limitée dans le temps</li>
                            </ul>
                        </div>

                        <!-- Article 8 -->
                        <h2 class="section-title mb-4" id="responsabilite">ARTICLE 8 : RESPONSABILITÉ ET GARANTIES</h2>
                        <div class="mb-5">
                            <div class="highlight-box">
                                <h5 class="mb-3"><i class="fas fa-shield-alt me-2"></i>Garanties SIFcash-Burkina :</h5>
                                <ul class="mb-3">
                                    <li>Sécurité des fonds déposés</li>
                                    <li>Transparence dans les calculs d'intérêts</li>
                                    <li>Respect des échéances de remboursement</li>
                                    <li>Confidentialité des opérations</li>
                                </ul>
                                
                                <h5 class="mb-3"><i class="fas fa-exclamation-circle me-2"></i>Limitations :</h5>
                                <ul class="mb-0">
                                    <li>Force majeure et cas fortuits</li>
                                    <li>Modifications réglementaires</li>
                                    <li>Utilisation non conforme des services</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Article 9 -->
                        <h2 class="section-title mb-4" id="modification">ARTICLE 9 : MODIFICATION ET RÉSILIATION</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Modification des Conditions :</h5>
                            <ul>
                                <li>Préavis de 30 jours pour toute modification</li>
                                <li>Information par courrier ou email</li>
                                <li>Droit de résiliation en cas de désaccord</li>
                            </ul>
                            
                            <h5 class="text-primary mb-3 mt-4">Résiliation :</h5>
                            <ul>
                                <li>Résiliation libre avec préavis d'un mois</li>
                                <li>Résiliation pour manquement après mise en demeure</li>
                                <li>Solde de tout compte sous 30 jours</li>
                            </ul>
                        </div>

                        <!-- Article 10 -->
                        <h2 class="section-title mb-4" id="dispositions">ARTICLE 10 : DISPOSITIONS FINALES</h2>
                        <div class="mb-5">
                            <h5 class="text-primary mb-3">Droit Applicable :</h5>
                            <p class="mb-3">Les présentes conditions sont régies par le droit burkinabè et les réglementations de l'UMOA en matière de microfinance.</p>
                            
                            <h5 class="text-primary mb-3">Règlement des Litiges :</h5>
                            <ul>
                                <li>Tentative de règlement amiable privilégiée</li>
                                <li>Médiation par les autorités compétentes</li>
                                <li>Juridiction compétente : Tribunaux de Ouagadougou</li>
                            </ul>
                            
                            <div class="alert alert-primary mt-4">
                                <h6><i class="fas fa-gavel me-2"></i>Entrée en Vigueur :</h6>
                                <p class="mb-0">Les présentes conditions entrent en vigueur dès l'adhésion du membre et restent applicables tant que la relation contractuelle perdure.</p>
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
                                <a class="nav-link active" href="#preambule">
                                    <i class="fas fa-file-alt me-2"></i>Préambule
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#definitions">
                                    <i class="fas fa-book me-2"></i>Définitions
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#objet">
                                    <i class="fas fa-target me-2"></i>Objet et Champ
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#adhesion">
                                    <i class="fas fa-user-check me-2"></i>Adhésion
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#finances">
                                    <i class="fas fa-calculator me-2"></i>Conditions Financières
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#engagements">
                                    <i class="fas fa-handshake me-2"></i>Engagements
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#reclamations">
                                    <i class="fas fa-comment-dots me-2"></i>Réclamations
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#donnees">
                                    <i class="fas fa-shield-alt me-2"></i>Protection Données
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#responsabilite">
                                    <i class="fas fa-balance-scale me-2"></i>Responsabilité
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#modification">
                                    <i class="fas fa-edit me-2"></i>Modification
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a class="nav-link" href="#dispositions">
                                    <i class="fas fa-gavel me-2"></i>Dispositions Finales
                                </a>
                            </li>
                        </ul>
                        
                        <div class="mt-4 p-4" style="background: rgba(255,255,255,0.1); border-radius: 10px;">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations Importantes
                            </h6>
                            <div class="mb-3">
                                <small class="opacity-75">Taux épargne</small>
                                <div class="fw-bold">3% annuel brut</div>
                            </div>
                            <div class="mb-3">
                                <small class="opacity-75">Taux crédit</small>
                                <div class="fw-bold">5%</div>
                            </div>
                            <div class="mb-3">
                                <small class="opacity-75">Frais de dossier</small>
                                <div class="fw-bold">5 000 FCFA</div>
                            </div>
                            <div class="mb-3">
                                <small class="opacity-75">Pénalité retard</small>
                                <div class="fw-bold">25%</div>
                            </div>
                            <div>
                                <small class="opacity-75">Capital épargne</small>
                                <div class="fw-bold">Garanti à l'échéance</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3" style="background: rgba(255,255,255,0.1); border-radius: 10px;">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-phone me-2"></i>Besoin d'aide ?
                            </h6>
                            <p class="small mb-3 opacity-75">Contactez notre équipe support</p>
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('contact')); ?>" class="btn btn-light btn-sm">
                                    <i class="fas fa-envelope me-1"></i>Nous contacter
                                </a>
                                <a href="tel:+22625456364" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-phone me-1"></i>+226 25 45 63 64
                                </a>
                                <a href="tel:+22676182726" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-phone me-1"></i>76 18 27 26
                                </a>
                                <a href="tel:+22604370203" class="btn btn-outline-light btn-sm">
                                    <i class="fas fa-phone me-1"></i>04 37 02 03
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
<?php /**PATH C:\Mes Sites Web\sif-project\resources\views/terms.blade.php ENDPATH**/ ?>