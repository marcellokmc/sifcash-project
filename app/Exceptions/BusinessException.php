<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessException extends Exception
{
    protected array $context = [];
    protected string $userMessage;
    protected int $httpStatus;

    public function __construct(
        string $message,
        string $userMessage = null,
        int $httpStatus = 422,
        array $context = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        
        $this->userMessage = $userMessage ?? $message;
        $this->httpStatus = $httpStatus;
        $this->context = $context;
    }

    /**
     * Report the exception.
     */
    public function report(): bool
    {
        // Log de l'exception avec contexte
        \Log::warning('Business rule violation', [
            'exception' => get_class($this),
            'message' => $this->getMessage(),
            'user_message' => $this->userMessage,
            'context' => $this->context,
            'trace' => $this->getTraceAsString()
        ]);

        return false; // Ne pas reporter à un service externe
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->userMessage,
                'error_code' => get_class($this),
                'context' => $this->context,
            ], $this->httpStatus);
        }

        return back()
            ->withErrors(['business_error' => $this->userMessage])
            ->withInput();
    }
}

// Exceptions spécifiques

class CreditEligibilityException extends BusinessException
{
    public function __construct(array $errors, array $context = [])
    {
        $message = 'Credit eligibility failed: ' . implode(', ', $errors);
        $userMessage = 'Votre demande de crédit ne peut être traitée: ' . implode(', ', $errors);
        
        parent::__construct($message, $userMessage, 422, array_merge($context, ['errors' => $errors]));
    }
}

class InsufficientFundsException extends BusinessException
{
    public function __construct(float $requested, float $available)
    {
        $message = "Insufficient funds: requested {$requested}, available {$available}";
        $userMessage = sprintf(
            'Solde insuffisant. Demandé: %s FCFA, Disponible: %s FCFA',
            number_format($requested, 0, ',', ' '),
            number_format($available, 0, ',', ' ')
        );
        
        parent::__construct($message, $userMessage, 422, [
            'requested' => $requested,
            'available' => $available
        ]);
    }
}

class DuplicateAdhesionException extends BusinessException
{
    public function __construct(string $planName, array $context = [])
    {
        $message = "Duplicate adhesion attempt for plan: {$planName}";
        $userMessage = "Vous avez déjà une adhésion active sur le plan '{$planName}'.";
        
        parent::__construct($message, $userMessage, 409, array_merge($context, ['plan' => $planName]));
    }
}

class DocumentValidationException extends BusinessException
{
    public function __construct(array $missingDocuments)
    {
        $message = 'Missing required documents: ' . implode(', ', $missingDocuments);
        $userMessage = 'Documents requis manquants: ' . implode(', ', $missingDocuments);
        
        parent::__construct($message, $userMessage, 422, ['missing_documents' => $missingDocuments]);
    }
}

class PlanUnavailableException extends BusinessException
{
    public function __construct(string $planName, string $reason = 'Plan désactivé')
    {
        $message = "Plan unavailable: {$planName} - {$reason}";
        $userMessage = "Le plan '{$planName}' n'est plus disponible. Raison: {$reason}";
        
        parent::__construct($message, $userMessage, 410, [
            'plan' => $planName,
            'reason' => $reason
        ]);
    }
}

class MaximumLimitExceededException extends BusinessException
{
    public function __construct(string $limitType, int $limit, int $current)
    {
        $message = "Maximum limit exceeded for {$limitType}: {$current}/{$limit}";
        $userMessage = "Limite maximale atteinte pour {$limitType}: {$current} sur {$limit} autorisé(s).";
        
        parent::__construct($message, $userMessage, 422, [
            'limit_type' => $limitType,
            'limit' => $limit,
            'current' => $current
        ]);
    }
}