<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - SIFcash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <!-- Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold">À propos de SIFcash-Burkina</h1>
                    <p class="lead">Découvrez notre mission et nos valeurs</p>
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
                        <h2 class="mb-4">Qui sommes-nous ?</h2>
                        <p class="lead">
                            SIFcash-Burkina est une institution de microfinance dédiée à l'épargne et au crédit, 
                            créée pour répondre aux besoins financiers des populations du Burkina Faso.
                        </p>

                        <h3 class="mt-5 mb-3">Notre Mission</h3>
                        <p>
                            Nous nous engageons à fournir des services financiers accessibles, sécurisés et adaptés 
                            aux besoins de nos adhérents. Notre objectif est de contribuer au développement économique 
                            et social en favorisant l'inclusion financière.
                        </p>

                        <h3 class="mt-5 mb-3">Nos Valeurs</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-shield-alt text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Sécurité</h5>
                                        <p>Protection de vos données et de vos fonds avec les plus hauts standards de sécurité.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-handshake text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Confiance</h5>
                                        <p>Transparence et intégrité dans toutes nos relations avec nos adhérents.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Proximité</h5>
                                        <p>Un accompagnement personnalisé et un service de qualité proche de vous.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-chart-line text-primary fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5>Innovation</h5>
                                        <p>Solutions modernes et digitales pour faciliter l'accès aux services financiers.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-5 mb-3">Nos Services</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Comptes d'épargne avec taux d'intérêt compétitifs</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Crédits personnels et professionnels</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Plans d'épargne flexibles</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Conseil et accompagnement financier</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Services numériques sécurisés</li>
                        </ul>

                        <div class="mt-5 p-4 bg-light rounded">
                            <h4>Contactez-nous</h4>
                            <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Burkina Faso</p>
                            <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i>
                                    <a href="tel:+22625456364" class="text-decoration-none">+226 25 45 63 64</a> /
                                    <a href="tel:+22676182726" class="text-decoration-none">76 18 27 26</a> /
                                    <a href="tel:+22604370203" class="text-decoration-none">04 37 02 03</a>
                            </p>
                            <p class="mb-0"><i class="fas fa-envelope text-primary me-2"></i>contact@sifcash-burkina.bf</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Rejoignez-nous</h5>
                        <p class="card-text">Devenez membre de SIFcash-Burkina et profitez de nos services financiers.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('register') }}" class="btn btn-primary">S'inscrire</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Se connecter</a>
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