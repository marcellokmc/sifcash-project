@extends('layouts.error')

@section('title', 'Erreur Serveur - 500')

@push('styles')
<style>
    .error-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        position: relative;
        overflow: hidden;
    }
    
    .error-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1.5" fill="%23ffffff" fill-opacity="0.1"/></svg>') repeat;
        animation: float 15s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: rotate(0deg) translateY(0px); }
        50% { transform: rotate(180deg) translateY(-15px); }
    }
    
    .error-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 25px;
        box-shadow: 0 30px 60px rgba(231, 76, 60, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 1;
    }
    
    .error-number {
        font-size: 8rem;
        font-weight: 900;
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    .error-icon {
        font-size: 4rem;
        color: #e74c3c;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .btn-modern {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }
    
    .btn-danger-modern {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }
    
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e74c3c;
        animation: blink 1.5s infinite;
        margin-right: 8px;
    }
    
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }
    
    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .breadcrumb-item.active {
        color: white;
    }
    
    @media (max-width: 768px) {
        .error-number {
            font-size: 5rem;
        }
        
        .error-icon {
            font-size: 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="error-container d-flex align-items-center">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="breadcrumb-item active">Erreur Serveur</li>
            </ol>
        </nav>
        
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="error-card p-4 p-md-5 text-center">
                    <!-- Statut du système -->
                    <div class="mb-3">
                        <span class="status-indicator"></span>
                        <small class="text-danger fw-bold">SYSTÈME INDISPONIBLE</small>
                    </div>
                    
                    <!-- Numéro d'erreur -->
                    <div class="error-number mb-3">500</div>
                    
                    <!-- Icône -->
                    <div class="error-icon mb-4">
                        <i class="fas fa-server"></i>
                    </div>
                    
                    <!-- Message principal -->
                    <h2 class="h3 fw-bold text-dark mb-3">Erreur Interne du Serveur</h2>
                    <p class="lead text-muted mb-4">
                        Oups ! Une erreur inattendue s'est produite sur nos serveurs.
                    </p>
                    
                    <!-- Message technique -->
                    <div class="alert alert-danger border-0 mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Que s'est-il passé ?</strong><br>
                        <small>Nos équipes techniques ont été automatiquement notifiées et travaillent activement à résoudre ce problème.</small>
                    </div>
                    
                    <!-- Informations de debug (si en mode debug et admin) -->
                    @if(config('app.debug') && auth()->check() && auth()->user()->role === 'admin')
                    <div class="alert alert-warning border-0 mb-4">
                        <small>
                            <i class="fas fa-bug me-2"></i><strong>Debug Info (Admin uniquement) :</strong><br>
                            Timestamp: {{ now()->format('d/m/Y H:i:s') }}<br>
                            URL: {{ request()->fullUrl() }}<br>
                            Method: {{ request()->method() }}
                        </small>
                    </div>
                    @endif
                    
                    <!-- Actions recommandées -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Que pouvez-vous faire ?</h6>
                        <div class="row g-2 text-start">
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-redo text-info me-3"></i>
                                    <small>Actualisez la page dans quelques minutes</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-arrow-left text-info me-3"></i>
                                    <small>Retournez à la page précédente</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-info me-3"></i>
                                    <small>Contactez notre support si le problème persiste</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-4">
                        <button onclick="location.reload()" class="btn btn-danger-modern btn-modern">
                            <i class="fas fa-redo me-2"></i>Actualiser
                        </button>
                        <button onclick="history.back()" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </button>
                    </div>
                    
                    <!-- Support -->
                    <div class="pt-4 border-top">
                        <h6 class="text-muted mb-3">Besoin d'aide ?</h6>
                        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                            <a href="mailto:support@sif-burkina.bf" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-envelope me-2"></i>Contacter le Support
                            </a>
                            <a href="tel:+22625123456" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-phone me-2"></i>Assistance Téléphonique
                            </a>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            <i class="fas fa-clock me-1"></i>
                            Support disponible : Lundi-Vendredi 8h00-17h00
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer d'erreur -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="text-white-50 small mb-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    SIF Burkina - Système Intégré de Finance {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Animation d'entrée et gestion des interactions
document.addEventListener('DOMContentLoaded', function() {
    const card = document.querySelector('.error-card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(50px) scale(0.9)';
    
    setTimeout(() => {
        card.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0) scale(1)';
    }, 300);
    
    // Animation des boutons
    document.querySelectorAll('.btn-modern').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
            this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.2)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Auto-refresh après 30 secondes (optionnel)
    let countdown = 30;
    const refreshBtn = document.querySelector('button[onclick="location.reload()"]');
    const originalText = refreshBtn.innerHTML;
    
    const countdownInterval = setInterval(() => {
        refreshBtn.innerHTML = `<i class="fas fa-redo me-2"></i>Auto-actualisation (${countdown}s)`;
        countdown--;
        
        if (countdown < 0) {
            clearInterval(countdownInterval);
            location.reload();
        }
    }, 1000);
    
    // Annuler l'auto-refresh si l'utilisateur interagit
    document.addEventListener('click', () => {
        clearInterval(countdownInterval);
        refreshBtn.innerHTML = originalText;
    });
});
</script>
@endpush
