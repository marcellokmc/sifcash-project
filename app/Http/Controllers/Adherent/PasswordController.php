<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function edit()
    {
        return view('adherent.password.edit');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function update(Request $request)
    {
        $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'new_password.required' => 'Le nouveau mot de passe est requis.',
            'new_password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        // Mettre à jour le mot de passe
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}
