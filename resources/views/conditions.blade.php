<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales du Service d'Épargne - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <!-- Hero Section -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Conditions Générales du Service d'Épargne</h1>
                    <p class="lead">Modalités et conditions de notre service d'épargne personnalisée</p>
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
                        <p class="text-muted mb-4">Dernière mise à jour : {{ date('d/m/Y') }}</p>
                        
                        <!-- Informations Légales -->
                        <div class="alert alert-info mb-4">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-building me-2"></i>Informations Légales
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>RCCM :</strong> 00235418M</p>
                                    <p class="mb-1"><strong>Compte Coris :</strong> N°10003 – 01348224001 – 86</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Adresse :</strong> 01 BP 5368 Ouagadougou</p>
                                    <p class="mb-0"><strong>Contact :</strong> contact@sifcash-burkina.bf</p>
                                </div>
                            </div>
                        </div>

                        <!-- Montants de Cotisation -->
                        <h2 class="mb-4">1. Montants de Cotisation</h2>
                        <p>Les montants minimum de cotisation sont fixés comme suit :</p>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h5 class="text-primary">Option Mensuelle</h5>
                                        <h3 class="fw-bold">5,000 FCFA</h3>
                                        <small class="text-muted">Minimum par mois</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="text-success">Option Hebdomadaire</h5>
                                        <h3 class="fw-bold">7,500 FCFA</h3>
                                        <small class="text-muted">Minimum par semaine</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h5 class="text-warning">Option Journalière</h5>
                                        <h3 class="fw-bold">1,000 FCFA</h3>
                                        <small class="text-muted">Minimum par jour</small>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modalités Financières -->
                        <h2 class="mt-5 mb-4">2. Modalités Financières</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Taux d'Intérêt</h6>
                                        <p class="mb-0">
                                            <span class="h4 text-primary fw-bold">3%</span> annuel brut
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Calcul des Intérêts</h6>
                                        <p class="mb-0">Au prorata temporis sur la durée effective</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-success">
                            <i class="fas fa-shield-check me-2"></i>
                            <strong>Important :</strong> Le capital n'est payable qu'à la date d'échéance. 
                            Les fonds épargnés et les intérêts sont versés à l'échéance, sauf en cas de remboursement anticipé.
                        </div>

                        <!-- Adhésion -->
                        <h2 class="mt-5 mb-4">3. Adhésion et Prise d'Effet</h2>
                        <ul>
                            <li>L'adhésion prend effet à compter de la première prime encaissée</li>
                            <li>La durée est déterminée selon le choix de l'adhérent</li>
                            <li>L'adhérent déclare avoir pris connaissance des présentes conditions générales</li>
                            <li>L'adhérent s'engage à respecter les modalités convenues</li>
                        </ul>

                        <!-- Réclamations -->
                        <h2 class="mt-5 mb-4">4. Réclamations et Remboursements</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="fw-bold">Procédure de Réclamation</h5>
                                <ul>
                                    <li>Toute demande doit être formulée par écrit</li>
                                    <li>Accompagnée d'un justificatif d'identité</li>
                                    <li>Envoyée à l'adresse officielle</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold">Remboursement Anticipé</h5>
                                <ul>
                                    <li>Possible selon les conditions</li>
                                    <li>Restitution selon la périodicité initiale</li>
                                    <li>Quotidienne, hebdomadaire ou mensuelle</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Engagement de l'adhérent -->
                        <h2 class="mt-5 mb-4">5. Engagement de l'Adhérent</h2>
                        <div class="alert alert-primary">
                            <h5 class="fw-bold mb-3">Déclaration d'Engagement</h5>
                            <p class="mb-0">
                                <em>"L'adhérent déclare avoir pris connaissance des conditions générales du service d'épargne 
                                à court terme, y adhérer pleinement, et s'engage à respecter les modalités convenues."</em>
                            </p>
                        </div>

                        <!-- Contact -->
                        <h2 class="mt-5 mb-4">6. Contact et Support</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Téléphones</h6>
                                <p>
                                    <a href="tel:+22625456364" class="text-decoration-none">+226 25 45 63 64</a> /
                                    <a href="tel:+22676182726" class="text-decoration-none">76 18 27 26</a> /
                                    <a href="tel:+22604370203" class="text-decoration-none">04 37 02 03</a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Email</h6>
                                <p><a href="mailto:contact@sifcash-burkina.bf" class="text-decoration-none">contact@sifcash-burkina.bf</a></p>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="text-center mt-4">
                            <a href="https://api.whatsapp.com/send?phone=22671337005" target="_blank" class="btn btn-success btn-lg">
                                <i class="fab fa-whatsapp me-2"></i>
                                Contactez-nous sur WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Sommaire</h5>
                            <nav class="nav nav-pills flex-column small">
                                <a class="nav-link py-1" href="#montants">1. Montants de Cotisation</a>
                                <a class="nav-link py-1" href="#modalites">2. Modalités Financières</a>
                                <a class="nav-link py-1" href="#adhesion">3. Adhésion</a>
                                <a class="nav-link py-1" href="#reclamations">4. Réclamations</a>
                                <a class="nav-link py-1" href="#engagement">5. Engagement</a>
                                <a class="nav-link py-1" href="#contact">6. Contact</a>
                            </nav>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Besoin d'aide ?</h5>
                            <p class="card-text">Notre équipe est disponible pour répondre à vos questions.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('contact') }}" class="btn btn-primary">
                                    <i class="fas fa-phone me-2"></i>Nous contacter
                                </a>
                                <a href="{{ route('services') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle me-2"></i>Nos services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>