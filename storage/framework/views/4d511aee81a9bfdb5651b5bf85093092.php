<div id="preloader" class="preloader-wrapper">
    <div class="preloader-content">
        <!-- Animations de pièces qui tombent -->
        <div class="coins-animation">
            <div class="coin">💰</div>
            <div class="coin">💵</div>
            <div class="coin">💳</div>
            <div class="coin">🏦</div>
            <div class="coin">💎</div>
            <div class="coin">💰</div>
            <div class="coin">💵</div>
            <div class="coin">💳</div>
        </div>
        
        <!-- Logo avec animation pulse -->
        <div class="preloader-logo">
            <img src="<?php echo e(asset('img/SIF logo .jpg')); ?>" alt="SIFCash-Burkina">
            <div class="logo-ring"></div>
            <div class="logo-ring ring-2"></div>
        </div>
        
        <!-- Titre animé -->
        <h1 class="preloader-title">
            <span>S</span><span>I</span><span>F</span><span>C</span><span>a</span><span>s</span><span>h</span>
        </h1>
        
        <!-- Slogan animé -->
        <div class="preloader-slogan">
            <p class="slogan-line slogan-line-1">✨ Votre Partenaire Financier de Confiance ✨</p>
            <p class="slogan-line slogan-line-2" id="dynamicSlogan">💎 Épargnez Aujourd'hui, Profitez Demain 💎</p>
        </div>
        
        <!-- Barre de progression -->
        <div class="preloader-progress">
            <div class="preloader-progress-bar"></div>
            <span class="progress-percentage">0%</span>
        </div>
        
        <!-- Bouton d'entrée -->
        <button id="enterButton" class="enter-button" style="display: none;">
            <span class="button-content">
                <i class="fas fa-door-open"></i>
                <span class="button-text">ENTRER</span>
                <i class="fas fa-arrow-right"></i>
            </span>
            <div class="button-shine"></div>
        </button>
        
        <!-- Texte de chargement -->
        <p class="preloader-text" id="loadingText">Préparation de votre espace...</p>
    </div>
</div>

<style>
    .preloader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.8s ease, visibility 0.8s ease;
        overflow: hidden;
    }
    
    .preloader-wrapper.hide {
        opacity: 0;
        visibility: hidden;
    }
    
    .preloader-content {
        text-align: center;
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0 1rem;
    }
    
    /* Animation des pièces qui tombent */
    .coins-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }
    
    .coin {
        position: absolute;
        font-size: 2rem;
        animation: coinFall 4s linear infinite;
        opacity: 0.6;
    }
    
    .coin:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 5s; }
    .coin:nth-child(2) { left: 20%; animation-delay: 1s; animation-duration: 4.5s; }
    .coin:nth-child(3) { left: 30%; animation-delay: 0.5s; animation-duration: 5.5s; }
    .coin:nth-child(4) { left: 50%; animation-delay: 1.5s; animation-duration: 4s; }
    .coin:nth-child(5) { left: 65%; animation-delay: 0.3s; animation-duration: 5.2s; }
    .coin:nth-child(6) { left: 75%; animation-delay: 2s; animation-duration: 4.8s; }
    .coin:nth-child(7) { left: 85%; animation-delay: 0.8s; animation-duration: 5s; }
    .coin:nth-child(8) { left: 92%; animation-delay: 1.2s; animation-duration: 4.5s; }
    
    @keyframes coinFall {
        0% {
            top: -10%;
            transform: rotate(0deg) scale(0.8);
            opacity: 0;
        }
        10% {
            opacity: 0.8;
        }
        90% {
            opacity: 0.4;
        }
        100% {
            top: 110%;
            transform: rotate(360deg) scale(1.2);
            opacity: 0;
        }
    }
    
    /* Logo animation - Mobile First */
    .preloader-logo {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        position: relative;
        animation: float 3s ease-in-out infinite;
    }
    
    .preloader-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 25px;
        box-shadow: 0 15px 60px rgba(59, 130, 246, 0.6);
        animation: pulse 2s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }
    
    .logo-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border: 3px solid rgba(59, 130, 246, 0.3);
        border-radius: 50%;
        animation: ringPulse 2s ease-in-out infinite;
    }
    
    .logo-ring.ring-2 {
        width: 120%;
        height: 120%;
        border: 2px solid rgba(16, 185, 129, 0.3);
        animation-delay: 0.5s;
    }
    
    @keyframes ringPulse {
        0%, 100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
        50% {
            transform: translate(-50%, -50%) scale(1.3);
            opacity: 0;
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 10px 60px rgba(16, 185, 129, 0.7);
        }
    }
    
    /* Titre animé - Mobile First */
    .preloader-title {
        color: white;
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: 0.15rem;
        text-shadow: 0 3px 15px rgba(59, 130, 246, 0.5);
        text-align: center;
        width: 100%;
    }
    
    .preloader-title span {
        display: inline-block;
        animation: wave 1.5s ease-in-out infinite;
        background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .preloader-title span:nth-child(1) { animation-delay: 0s; }
    .preloader-title span:nth-child(2) { animation-delay: 0.1s; }
    .preloader-title span:nth-child(3) { animation-delay: 0.2s; }
    .preloader-title span:nth-child(4) { animation-delay: 0.3s; }
    .preloader-title span:nth-child(5) { animation-delay: 0.4s; }
    .preloader-title span:nth-child(6) { animation-delay: 0.5s; }
    .preloader-title span:nth-child(7) { animation-delay: 0.6s; }
    
    /* Slogan animé - Mobile First */
    .preloader-slogan {
        margin-bottom: 1.5rem;
        padding: 0 1rem;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .slogan-line {
        color: white;
        font-size: 0.9rem;
        font-weight: 500;
        margin: 0.3rem 0;
        opacity: 0;
        animation: fadeInUp 1s ease forwards;
        letter-spacing: 0.03rem;
        text-align: center;
        width: 100%;
        max-width: 100%;
    }
    
    .slogan-line-1 {
        animation-delay: 0.5s;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .slogan-line-2 {
        animation-delay: 0.8s;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes wave {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    
    /* Barre de progression - Mobile First */
    .preloader-progress {
        width: 90%;
        max-width: 300px;
        height: 6px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        margin: 0 auto 1.5rem;
        overflow: hidden;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.3);
        position: relative;
        display: block;
    }
    
    .preloader-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6 0%, #10b981 50%, #fbbf24 100%);
        border-radius: 20px;
        width: 0%;
        transition: width 0.3s ease;
        box-shadow: 0 0 25px rgba(59, 130, 246, 1);
        position: relative;
        overflow: hidden;
    }
    
    .preloader-progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }
    
    .progress-percentage {
        position: absolute;
        top: -25px;
        right: 0;
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    /* Bouton d'entrée - Mobile First */
    .enter-button {
        background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
        border: none;
        padding: 0.9rem 2rem;
        border-radius: 50px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.1rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
        transition: all 0.3s ease;
        margin-top: 0.5rem;
        animation: buttonPulse 2s ease-in-out infinite;
    }
    
    .enter-button:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 15px 50px rgba(59, 130, 246, 0.7);
    }
    
    .enter-button:active {
        transform: translateY(-2px) scale(1.02);
    }
    
    .button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }
    
    .button-content i {
        font-size: 1rem;
        animation: iconBounce 1s ease-in-out infinite;
    }
    
    .button-content .fa-arrow-right {
        animation-delay: 0.2s;
    }
    
    .button-shine {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shine 3s infinite;
    }
    
    @keyframes buttonPulse {
        0%, 100% {
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.5);
        }
        50% {
            box-shadow: 0 10px 50px rgba(16, 185, 129, 0.7);
        }
    }
    
    @keyframes iconBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }
    
    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }
    
    /* Texte de chargement - Mobile First */
    .preloader-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.85rem;
        font-weight: 400;
        letter-spacing: 0.05rem;
        animation: blink 1.5s ease-in-out infinite;
        margin-top: 0.5rem;
        text-align: center;
        width: 100%;
    }
    
    @keyframes blink {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    /* Tablette (>= 768px) */
    @media (min-width: 768px) {
        .preloader-logo {
            width: 130px;
            height: 130px;
            margin: 0 auto 2rem;
        }
        
        .preloader-title {
            font-size: 3rem;
            letter-spacing: 0.2rem;
            margin-bottom: 1.2rem;
        }
        
        .slogan-line {
            font-size: 1.1rem;
            margin: 0.4rem 0;
        }
        
        .preloader-slogan {
            margin-bottom: 2rem;
        }
        
        .preloader-progress {
            max-width: 350px;
            height: 7px;
            margin: 0 auto 1.8rem;
        }
        
        .preloader-text {
            font-size: 1rem;
        }
        
        .enter-button {
            padding: 1.1rem 2.5rem;
            font-size: 1.3rem;
            letter-spacing: 0.15rem;
        }
        
        .button-content i {
            font-size: 1.2rem;
        }
        
        .coin {
            font-size: 1.8rem;
        }
    }
    
    /* Desktop 15 pouces (>= 992px) */
    @media (min-width: 992px) {
        .preloader-logo {
            width: 150px;
            height: 150px;
            margin: 0 auto 2.5rem;
        }
        
        .preloader-title {
            font-size: 3.5rem;
            letter-spacing: 0.25rem;
            margin-bottom: 1.5rem;
        }
        
        .slogan-line {
            font-size: 1.25rem;
            margin: 0.5rem 0;
            letter-spacing: 0.05rem;
        }
        
        .preloader-slogan {
            margin-bottom: 2.5rem;
            padding: 0;
        }
        
        .preloader-progress {
            max-width: 400px;
            height: 8px;
            margin: 0 auto 2rem;
        }
        
        .progress-percentage {
            font-size: 1rem;
        }
        
        .preloader-text {
            font-size: 1.1rem;
            margin-top: 1rem;
        }
        
        .enter-button {
            padding: 1.2rem 3rem;
            font-size: 1.5rem;
            letter-spacing: 0.2rem;
            margin-top: 1rem;
        }
        
        .button-content i {
            font-size: 1.3rem;
        }
        
        .coin {
            font-size: 2rem;
        }
    }
    
    /* Large Desktop (>= 1400px) */
    @media (min-width: 1400px) {
        .preloader-logo {
            width: 170px;
            height: 170px;
        }
        
        .preloader-title {
            font-size: 4rem;
            letter-spacing: 0.3rem;
        }
        
        .slogan-line {
            font-size: 1.4rem;
        }
        
        .preloader-progress {
            max-width: 450px;
        }
        
        .enter-button {
            padding: 1.3rem 3.5rem;
            font-size: 1.6rem;
        }
    }
</style>

<script>
    // Gestion de la progression et du preloader
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('preloader');
        const progressBar = document.querySelector('.preloader-progress-bar');
        const progressPercentage = document.querySelector('.progress-percentage');
        const enterButton = document.getElementById('enterButton');
        const loadingText = document.getElementById('loadingText');
        const dynamicSlogan = document.getElementById('dynamicSlogan');
        
        // Slogans rotatifs sur l'épargne et le crédit
        const slogans = [
            '💎 Épargnez Aujourd’hui, Profitez Demain 💎',
            '💰 Votre Épargne, Notre Priorité 💰',
            '🏛️ Construisez Votre Avenir Financier 🏛️',
            '🚀 Des Crédits Rapides Pour Vos Projets 🚀',
            '🏠 Réalisez Vos Rêves, Nous Vous Accompagnons 🏠',
            '💳 Crédit Facile, Remboursement Flexible 💳',
            '📈 Faites Fructifier Votre Argent 📈',
            '✨ De l’Épargne au Crédit, Tout Est Possible ✨',
            '👍 Un Partenaire Sûr Pour Vos Finances 👍',
            '🌟 Investissez Dans Votre Futur 🌟',
            '💪 Ensemble, Bâtissons Votre Succès 💪',
            '🎯 Vos Objectifs, Notre Mission 🎯',
            '🔑 La Clé de Votre Indépendance Financière 🔑',
            '🌈 Des Solutions Adaptées à Chaque Besoin 🌈',
            '💡 Épargner Malin, Emprunter Serein 💡'
        ];
        
        // Sélectionner un slogan aléatoire
        const randomSlogan = slogans[Math.floor(Math.random() * slogans.length)];
        dynamicSlogan.textContent = randomSlogan;
        
        let progress = 0;
        const duration = 3000; // 3 secondes
        const interval = 30;
        const increment = 100 / (duration / interval);
        
        // Messages de chargement
        const messages = [
            'Préparation de votre espace...',
            'Chargement des données...',
            'Initialisation du système...',
            'Presque prêt !'
        ];
        let messageIndex = 0;
        
        // Animation de la barre de progression
        const progressInterval = setInterval(function() {
            progress += increment;
            
            if (progress >= 100) {
                progress = 100;
                clearInterval(progressInterval);
                
                // Afficher le bouton d'entrée
                setTimeout(function() {
                    loadingText.style.opacity = '0';
                    setTimeout(function() {
                        loadingText.style.display = 'none';
                        enterButton.style.display = 'block';
                        enterButton.style.animation = 'fadeInUp 0.5s ease forwards';
                    }, 300);
                }, 500);
            }
            
            // Mettre à jour la progression
            progressBar.style.width = progress + '%';
            progressPercentage.textContent = Math.floor(progress) + '%';
            
            // Changer le message tous les 25%
            const newMessageIndex = Math.floor(progress / 25);
            if (newMessageIndex !== messageIndex && newMessageIndex < messages.length) {
                messageIndex = newMessageIndex;
                loadingText.textContent = messages[messageIndex];
            }
        }, interval);
        
        // Clic sur le bouton d'entrée
        enterButton.addEventListener('click', function() {
            enterButton.style.transform = 'scale(0.95)';
            
            setTimeout(function() {
                preloader.classList.add('hide');
                
                // Retirer l'élément du DOM après la transition
                setTimeout(function() {
                    preloader.remove();
                }, 800);
            }, 200);
        });
        
        // Option : auto-entrée après 10 secondes si l'utilisateur ne clique pas
        setTimeout(function() {
            if (preloader && !preloader.classList.contains('hide')) {
                enterButton.click();
            }
        }, 10000);
    });
</script>
<?php /**PATH C:\Mes Sites Web\sif-project\resources\views/components/preloader.blade.php ENDPATH**/ ?>