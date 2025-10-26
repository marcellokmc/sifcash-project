<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\LogConnexion;

class FraudDetectionMiddleware
{
    // Seuils de détection
    private const MAX_REQUESTS_PER_MINUTE = 30;
    private const MAX_FAILED_ATTEMPTS = 5;
    private const MAX_DIFFERENT_IPS_PER_USER = 3;
    private const SUSPICIOUS_PATTERNS = [
        'bot', 'crawler', 'spider', 'scraper', 'automated',
        'python', 'curl', 'wget', 'postman'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$protectedActions): Response
    {
        $user = $request->user();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $action = $request->route()?->getName();

        // Vérifier si l'action est protégée
        if (!empty($protectedActions) && !in_array($action, $protectedActions)) {
            return $next($request);
        }

        $suspicionScore = 0;
        $warnings = [];

        // 1. Détection de rate limiting anormal
        $suspicionScore += $this->checkRateLimit($ip, $user?->id);

        // 2. Détection des tentatives de connexion échouées
        if ($user) {
            $failedAttempts = $this->getFailedLoginAttempts($user->id);
            if ($failedAttempts >= self::MAX_FAILED_ATTEMPTS) {
                $suspicionScore += 30;
                $warnings[] = 'Multiple failed login attempts';
            }
        }

        // 3. Détection des User-Agent suspects
        $suspicionScore += $this->checkSuspiciousUserAgent($userAgent, $warnings);

        // 4. Détection des changements d'IP fréquents
        if ($user) {
            $suspicionScore += $this->checkIPPatterns($user->id, $ip, $warnings);
        }

        // 5. Détection des actions sensibles hors heures normales
        if ($this->isAfterHours()) {
            $sensitiveActions = [
                'admin.credits.approve',
                'admin.paiements.validate',
                'admin.users.create',
                'admin.agences.create'
            ];
            
            if (in_array($action, $sensitiveActions)) {
                $suspicionScore += 15;
                $warnings[] = 'Sensitive action performed after hours';
            }
        }

        // 6. Détection des montants anormaux
        if ($this->hasSuspiciousAmount($request)) {
            $suspicionScore += 25;
            $warnings[] = 'Suspicious transaction amount';
        }

        // Décision basée sur le score de suspicion
        if ($suspicionScore >= 50) {
            return $this->handleHighRiskRequest($request, $suspicionScore, $warnings);
        } elseif ($suspicionScore >= 30) {
            $this->logSuspiciousActivity($request, $suspicionScore, $warnings);
        }

        // Enregistrer l'activité normale
        $this->trackUserActivity($user, $ip, $action);

        return $next($request);
    }

    /**
     * Vérifier le rate limiting
     */
    private function checkRateLimit(string $ip, ?int $userId): int
    {
        $key = "rate_limit:" . ($userId ?: $ip);
        $requests = Cache::get($key, 0);
        
        Cache::put($key, $requests + 1, 60); // 1 minute

        if ($requests > self::MAX_REQUESTS_PER_MINUTE) {
            return 40; // Score élevé pour rate limiting
        } elseif ($requests > (self::MAX_REQUESTS_PER_MINUTE * 0.7)) {
            return 15; // Score modéré
        }

        return 0;
    }

    /**
     * Obtenir les tentatives de connexion échouées
     */
    private function getFailedLoginAttempts(int $userId): int
    {
        return Cache::remember(
            "failed_attempts:{$userId}",
            3600, // 1 heure
            fn() => LogConnexion::where('user_id', $userId)
                ->where('action', 'failed_login')
                ->where('created_at', '>', now()->subHours(1))
                ->count()
        );
    }

    /**
     * Vérifier les User-Agent suspects
     */
    private function checkSuspiciousUserAgent(?string $userAgent, array &$warnings): int
    {
        if (!$userAgent) {
            $warnings[] = 'Missing User-Agent';
            return 20;
        }

        $lowerUserAgent = strtolower($userAgent);
        foreach (self::SUSPICIOUS_PATTERNS as $pattern) {
            if (str_contains($lowerUserAgent, $pattern)) {
                $warnings[] = "Suspicious User-Agent pattern: {$pattern}";
                return 35;
            }
        }

        // Vérifier les User-Agent trop courts ou trop longs
        if (strlen($userAgent) < 10) {
            $warnings[] = 'Unusually short User-Agent';
            return 15;
        } elseif (strlen($userAgent) > 500) {
            $warnings[] = 'Unusually long User-Agent';
            return 10;
        }

        return 0;
    }

    /**
     * Vérifier les patterns d'IP
     */
    private function checkIPPatterns(int $userId, string $currentIP, array &$warnings): int
    {
        $recentIPs = Cache::remember(
            "user_ips:{$userId}",
            3600,
            fn() => LogConnexion::where('user_id', $userId)
                ->where('created_at', '>', now()->subHours(24))
                ->distinct('ip_address')
                ->pluck('ip_address')
                ->toArray()
        );

        $recentIPs[] = $currentIP;
        $uniqueIPs = array_unique($recentIPs);

        if (count($uniqueIPs) > self::MAX_DIFFERENT_IPS_PER_USER) {
            $warnings[] = 'Multiple different IPs used recently';
            return 25;
        }

        return 0;
    }

    /**
     * Vérifier si c'est hors heures ouvrables
     */
    private function isAfterHours(): bool
    {
        $hour = now()->hour;
        return $hour < 7 || $hour > 19; // Hors 7h-19h
    }

    /**
     * Détecter les montants suspects
     */
    private function hasSuspiciousAmount(Request $request): bool
    {
        $amount = $request->input('montant') ?: 
                  $request->input('montant_demande') ?: 
                  $request->input('montant_souscrit');

        if (!$amount) return false;

        $amount = (float) $amount;

        // Montants ronds suspects (ex: 1000000, 5000000)
        if ($amount >= 100000 && $amount % 100000 === 0) {
            return true;
        }

        // Montants exceptionnellement élevés
        if ($amount > 10000000) { // 10M FCFA
            return true;
        }

        return false;
    }

    /**
     * Gérer les requêtes à haut risque
     */
    private function handleHighRiskRequest(Request $request, int $score, array $warnings): Response
    {
        $this->logHighRiskActivity($request, $score, $warnings);

        // Bloquer temporairement l'IP
        $ip = $request->ip();
        Cache::put("blocked_ip:{$ip}", true, 1800); // 30 minutes

        // Notifier les administrateurs
        $this->notifyAdminsOfSuspiciousActivity($request, $score, $warnings);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Suspicious activity detected. Access temporarily restricted.',
                'message' => 'Activité suspecte détectée. Accès temporairement restreint.',
                'retry_after' => 1800
            ], 429);
        }

        return response()->view('errors.suspicious-activity', [
            'message' => 'Activité suspecte détectée. Votre accès a été temporairement restreint.',
            'retry_after' => 30
        ], 429);
    }

    /**
     * Logger l'activité suspecte
     */
    private function logSuspiciousActivity(Request $request, int $score, array $warnings): void
    {
        Log::warning('Suspicious activity detected', [
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'suspicion_score' => $score,
            'warnings' => $warnings,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Logger l'activité à haut risque
     */
    private function logHighRiskActivity(Request $request, int $score, array $warnings): void
    {
        Log::critical('High-risk activity blocked', [
            'user_id' => $request->user()?->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'post_data' => $request->except(['password', 'password_confirmation', '_token']),
            'suspicion_score' => $score,
            'warnings' => $warnings,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Suivre l'activité utilisateur normale
     */
    private function trackUserActivity(?object $user, string $ip, ?string $action): void
    {
        if (!$user || !$action) return;

        // Mettre à jour le cache des IPs récentes
        $cacheKey = "user_ips:{$user->id}";
        $recentIPs = Cache::get($cacheKey, []);
        $recentIPs[] = $ip;
        $recentIPs = array_unique(array_slice($recentIPs, -10)); // Garder les 10 dernières
        Cache::put($cacheKey, $recentIPs, 3600);
    }

    /**
     * Notifier les admins d'activité suspecte
     */
    private function notifyAdminsOfSuspiciousActivity(Request $request, int $score, array $warnings): void
    {
        try {
            $notificationService = app(\App\Services\NotificationService::class);
            $admins = \App\Models\User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                $notificationService->sendSystemNotification(
                    $admin,
                    'security_alert',
                    'Activité suspecte détectée',
                    "Activité suspecte bloquée. Score: {$score}. IP: {$request->ip()}. Vérification requise.",
                    [
                        'ip' => $request->ip(),
                        'user_id' => $request->user()?->id,
                        'score' => $score,
                        'warnings' => $warnings,
                        'url' => $request->fullUrl()
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify admins of suspicious activity', [
                'error' => $e->getMessage()
            ]);
        }
    }
}