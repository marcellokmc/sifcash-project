<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogConnexion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion interne
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Connexion des utilisateurs internes
     */
public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $user = Auth::user();

        // Log de connexion
        LogConnexion::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'login',
            'created_at' => now(),
        ]);

        // Redirection selon le rôle - CORRIGÉ
        // REDIRECTION SIMPLIFIÉE
            if ($user->role === 'adherent') {
                return redirect()->intended('/adherent/dashboard');
            } else {
                // Tous les autres rôles vont vers l'espace admin
                return redirect()->intended('/admin/dashboard');
            }
    }

    // Log tentative échouée
    $user = User::where('email', $request->email)->first();
    if ($user) {
        LogConnexion::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'failed_login',
            'created_at' => now(),
        ]);
    }

    return redirect()->back()
        ->with('error', 'Identifiants incorrects.')
        ->withInput();
}
    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        // Log de déconnexion
        if (Auth::check()) {
            LogConnexion::create([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'logout',
                'created_at' => now(), // ← AJOUTÉ
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Afficher le formulaire d'inscription adhérent
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Inscription d'un nouvel adhérent
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted'
        ], [
            'terms.required' => 'Vous devez accepter les conditions générales.',
            'terms.accepted' => 'Vous devez accepter les conditions générales.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email, // Peut être null
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'adherent',
            'active' => true // Activer le compte par défaut
        ]);

        Auth::login($user);

        // Log de création de compte
        LogConnexion::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'login',
            'created_at' => now(), // ← AJOUTÉ
        ]);

        return redirect()->route('adherent.dashboard')
            ->with('success', 'Compte créé avec succès ! Bienvenue sur SIFCash-Burkina.');
    }

    /**
     * Afficher le formulaire de connexion adhérent (email ou téléphone)
     */
    public function showAdherentLoginForm()
    {
        return view('auth.adherent-login');
    }

    /**
     * Connexion adhérent avec email ou téléphone
     */
    public function adherentLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $login = $request->login;
        $password = $request->password;
        $remember = $request->has('remember');

        // Tentative de connexion avec email ou téléphone
        $user = User::where('email', $login)
                    ->orWhere('phone', $login)
                    ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Vérifier si le compte est actif
            if (!$user->active) {
                return redirect()->back()
                    ->with('error', 'Votre compte est désactivé. Veuillez contacter l\'administration.')
                    ->withInput();
            }

            Auth::login($user, $remember);

            // Log de connexion
            LogConnexion::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'login',
                'created_at' => now(), // ← AJOUTÉ
            ]);

            return redirect()->intended('/adherent/dashboard')
                ->with('success', 'Connexion réussie !');
        }

        // Log tentative échouée
        if ($user) {
            LogConnexion::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'action' => 'failed_login',
                'created_at' => now(), // ← AJOUTÉ
            ]);
        }

        return redirect()->back()
            ->with('error', 'Identifiants incorrects.')
            ->withInput();
    }

    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Traiter la demande de mot de passe oublié
     */
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Aucun compte trouvé avec cette adresse email.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ici vous implémenteriez l'envoi d'email de réinitialisation
        // Pour l'instant, on retourne un message de succès

        return redirect()->back()
            ->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        // Enregistrer l'échec de connexion
        LogConnexion::create([
            'user_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'action' => 'failed_login',
            'created_at' => now()
        ]);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}