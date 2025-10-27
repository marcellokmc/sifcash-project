<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protection des Données - SIFcash-Burkina</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('img/SIF logo .jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <!-- Header -->
    <div class="bg-success text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="display-5 fw-bold">Protection des Données</h1>
                    <p class="lead">Nos mesures de sécurité pour protéger vos informations personnelles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-shield-alt me-3 fa-2x"></i>
                            <div>
                                <strong>Engagement de sécurité</strong><br>
                                La protection de vos données est notre priorité absolue. Nous mettons en œuvre les meilleures pratiques de sécurité de l'industrie.
                            </div>
                        </div>

                        <h2 class="mb-4">Mesures de Sécurité Techniques</h2>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-lock text-success fa-3x mb-3"></i>
                                        <h5>Chiffrement SSL/TLS</h5>
                                        <p>Toutes les communications sont chiffrées avec les protocoles SSL/TLS les plus récents.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-database text-success fa-3x mb-3"></i>
                                        <h5>Chiffrement des Données</h5>
                                        <p>Vos données sensibles sont chiffrées en base de données avec AES-256.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-shield text-success fa-3x mb-3"></i>
                                        <h5>Authentification Forte</h5>
                                        <p>Contrôle d'accès strict avec authentification à deux facteurs.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-server text-success fa-3x mb-3"></i>
                                        <h5>Infrastructure Sécurisée</h5>
                                        <p>Hébergement dans des centres de données certifiés et sécurisés.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="mt-5 mb-4">Mesures de Sécurité Organisationnelles</h2>

                        <div class="accordion" id="securityAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                        <i class="fas fa-users me-2"></i>Formation du Personnel
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#securityAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Formation régulière sur la sécurité des données</li>
                                            <li>Sensibilisation aux techniques de phishing et d'ingénierie sociale</li>
                                            <li>Procédures strictes de gestion des accès</li>
                                            <li>Signature d'accords de confidentialité</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                        <i class="fas fa-eye me-2"></i>Surveillance et Monitoring
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Surveillance 24h/24 des systèmes</li>
                                            <li>Détection automatique des anomalies</li>
                                            <li>Journalisation complète des accès</li>
                                            <li>Alertes en temps réel</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                        <i class="fas fa-clipboard-check me-2"></i>Audits et Conformité
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Audits de sécurité réguliers par des tiers</li>
                                            <li>Tests d'intrusion périodiques</li>
                                            <li>Conformité aux standards internationaux</li>
                                            <li>Revue régulière des politiques de sécurité</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                        <i class="fas fa-history me-2"></i>Sauvegarde et Continuité
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#securityAccordion">
                                    <div class="accordion-body">
                                        <ul>
                                            <li>Sauvegardes automatiques chiffrées</li>
                                            <li>Plan de reprise d'activité testé</li>
                                            <li>Réplication des données sécurisée</li>
                                            <li>Procédures de récupération d'urgence</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 class="mt-5 mb-4">Contrôle d'Accès</h2>
                        <p>Nous appliquons le principe du moindre privilège :</p>
                        <ul>
                            <li><strong>Accès basé sur les rôles</strong> : chaque utilisateur n'a accès qu'aux données nécessaires à ses fonctions</li>
                            <li><strong>Authentification multi-facteurs</strong> pour tous les comptes privilégiés</li>
                            <li><strong>Révocation immédiate</strong> des accès en cas de départ ou changement de poste</li>
                            <li><strong>Revue périodique</strong> des droits d'accès</li>
                        </ul>

                        <h2 class="mt-5 mb-4">Incident de Sécurité</h2>
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle me-2"></i>Procédure d'Incident</h5>
                            <p class="mb-2">En cas de suspicion d'incident de sécurité affectant vos données :</p>
                            <ol class="mb-0">
                                <li>Évaluation immédiate de l'incident</li>
                                <li>Mesures de confinement et de correction</li>
                                <li>Notification aux autorités compétentes si nécessaire</li>
                                <li>Information des clients concernés dans les 72 heures</li>
                                <li>Rapport d'incident et mesures préventives</li>
                            </ol>
                        </div>

                        <h2 class="mt-5 mb-4">Vos Responsabilités</h2>
                        <p>Pour garantir la sécurité de votre compte, nous vous recommandons de :</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>Utiliser un mot de passe fort et unique</li>
                                    <li>Ne jamais partager vos identifiants</li>
                                    <li>Vous déconnecter après chaque session</li>
                                    <li>Vérifier régulièrement vos relevés</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>Signaler toute activité suspecte</li>
                                    <li>Maintenir vos appareils à jour</li>
                                    <li>Éviter les réseaux WiFi publics</li>
                                    <li>Être vigilant face au phishing</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-5 p-4 bg-light rounded">
                            <h4><i class="fas fa-phone text-primary me-2"></i>Signaler un Incident</h4>
                            <p>En cas de suspicion d'incident de sécurité, contactez-nous immédiatement :</p>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <a href="mailto:security@sifcash-burkina.bf" class="text-decoration-none">security@sifcash-burkina.bf</a>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <a href="tel:+22625456364" class="text-decoration-none">+226 25 45 63 64</a> / 
                                <a href="tel:+22676182726" class="text-decoration-none">76 18 27 26</a> / 
                                <a href="tel:+22604370203" class="text-decoration-none">04 37 02 03</a>
                                <span class="badge bg-danger ms-2">Ligne d'urgence 24h/24</span>
                            </p>
                            <p class="mb-0"><small class="text-muted">Temps de réponse garanti : moins de 2 heures</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sticky-top">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-certificate text-success me-2"></i>Certifications</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>ISO 27001 (en cours)</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PCI DSS Compliant</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>SOC 2 Type II</li>
                                <li class="mb-0"><i class="fas fa-check text-success me-2"></i>RGPD Compliant</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Bonnes Pratiques</h5>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                    <i class="fas fa-key me-2"></i>Créer un mot de passe fort
                                </button>
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#phishingModal">
                                    <i class="fas fa-fishing me-2"></i>Éviter le phishing
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un mot de passe fort</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Un bon mot de passe doit :</h6>
                    <ul>
                        <li>Contenir au moins 12 caractères</li>
                        <li>Mélanger majuscules, minuscules, chiffres et symboles</li>
                        <li>Ne pas contenir d'informations personnelles</li>
                        <li>Être unique pour chaque service</li>
                    </ul>
                    <p><strong>Conseil :</strong> Utilisez une phrase de passe ou un gestionnaire de mots de passe.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="phishingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Éviter le phishing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Signaux d'alerte :</h6>
                    <ul>
                        <li>Emails demandant vos identifiants</li>
                        <li>URL suspectes ou raccourcies</li>
                        <li>Urgence artificielle</li>
                        <li>Fautes d'orthographe</li>
                    </ul>
                    <p><strong>Règle d'or :</strong> Ne cliquez jamais sur des liens suspects et vérifiez toujours l'URL.</p>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>