<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - SIFCash-Burkina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .password-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6c23e 0%, #e74a3b 100%);
        }
        .password-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-warning {
            background: #f6c23e;
            border: none;
        }
    </style>
</head>
<body>
    <div class="password-container d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card password-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h3 class="card-title">
                                    <i class="fas fa-lock text-warning me-2"></i>
                                    SIFCash-Burkina
                                </h3>
                                <p class="text-muted">Réinitialisation du mot de passe</p>
                            </div>

                            <div class="alert alert-warning d-flex" role="alert">
                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                <div>
                                    La réinitialisation automatique du mot de passe est désactivée. Veuillez contacter l’administrateur ou le chef de service de votre agence pour réinitialiser votre mot de passe.
                                </div>
                            </div>
                            <div class="text-muted small mb-3">
                                Pour accélérer le traitement, préparez votre numéro d’adhérent et vos informations d’identité.
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i>Retour à la connexion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>