<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Adherent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AccountManagementController extends Controller
{
    /**
     * Réinitialiser le mot de passe d'un utilisateur
     */
    public function resetUserPassword(Request $request, User $user)
    {
        $request->validate([
            'send_email' => 'boolean',
        ]);

        // Générer un nouveau mot de passe aléatoire
        $newPassword = Str::random(12);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Envoyer l'email si demandé
        if ($request->boolean('send_email') && $user->email) {
            try {
                // TODO: Implémenter l'envoi d'email
                // Mail::to($user->email)->send(new PasswordResetMail($newPassword));
            } catch (\Exception $e) {
                // Ignorer les erreurs d'envoi d'email
            }
        }

        return redirect()->back()->with([
            'success' => 'Mot de passe réinitialisé avec succès.',
            'new_password' => $newPassword,
            'user_email' => $user->email,
        ]);
    }

    /**
     * Réinitialiser le mot de passe d'un adhérent
     */
    public function resetAdherentPassword(Request $request, Adherent $adherent)
    {
        if (!$adherent->user) {
            return redirect()->back()->with('error', 'Cet adhérent n\'a pas de compte utilisateur.');
        }

        $request->validate([
            'send_email' => 'boolean',
        ]);

        // Générer un nouveau mot de passe aléatoire
        $newPassword = Str::random(12);

        $adherent->user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Envoyer l'email si demandé
        if ($request->boolean('send_email') && $adherent->email) {
            try {
                // TODO: Implémenter l'envoi d'email
                // Mail::to($adherent->email)->send(new PasswordResetMail($newPassword));
            } catch (\Exception $e) {
                // Ignorer les erreurs d'envoi d'email
            }
        }

        return redirect()->back()->with([
            'success' => 'Mot de passe réinitialisé avec succès.',
            'new_password' => $newPassword,
            'user_email' => $adherent->email,
        ]);
    }

    /**
     * Suspendre un utilisateur
     */
    public function suspendUser(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas suspendre un administrateur.');
        }

        $user->suspend($request->reason, Auth::id());

        return redirect()->back()->with('success', 'Utilisateur suspendu avec succès.');
    }

    /**
     * Réactiver un utilisateur
     */
    public function activateUser(User $user)
    {
        $user->activate();

        return redirect()->back()->with('success', 'Utilisateur réactivé avec succès.');
    }

    /**
     * Suspendre un adhérent
     */
    public function suspendAdherent(Request $request, Adherent $adherent)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $adherent->suspend($request->reason, Auth::id());

        return redirect()->back()->with('success', 'Adhérent suspendu avec succès.');
    }

    /**
     * Réactiver un adhérent
     */
    public function activateAdherent(Adherent $adherent)
    {
        $adherent->activate();

        return redirect()->back()->with('success', 'Adhérent réactivé avec succès.');
    }
}
