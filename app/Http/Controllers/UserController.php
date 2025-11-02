<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agence;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['agence', 'suspendedByUser'])->latest()->paginate(20);
        return view('backoffice.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $agences = Agence::where('active', true)->get();
        $roles = Role::all();
        return view('backoffice.users.create', compact('agences', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => 'required|exists:roles,id', // ← CHANGEMENT ICI
            'matricule' => 'nullable|string|max:50|unique:users',
            'date_embauche' => 'nullable|date',
            'agence_id' => 'nullable|exists:agences,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupérer le rôle pour avoir son nom
        $role = Role::findOrFail($request->role_id);

        $userData = $request->except('password_confirmation', 'role_id'); // ← EXCLURE role_id
        $userData['password'] = Hash::make($request->password);
        $userData['role'] = $role->name; // ← UTILISER LE NOM DU RÔLE

        // Si c'est un admin, on ignore l'agence
        if ($role->name === 'admin') {
            $userData['agence_id'] = null;
        }

        User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['agence', 'logsConnexions' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('backoffice.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $agences = Agence::where('active', true)->get();
        $roles = Role::all();
        
        // Trouver le rôle actuel de l'utilisateur
        $currentRole = Role::where('name', $user->role)->first();
        
        return view('backoffice.users.edit', compact('user', 'agences', 'roles', 'currentRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:8',
            'role_id' => 'required|exists:roles,id', // ← CHANGEMENT ICI
            'matricule' => 'nullable|string|max:50|unique:users,matricule,' . $user->id,
            'date_embauche' => 'nullable|date',
            'agence_id' => 'nullable|exists:agences,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Récupérer le rôle pour avoir son nom
        $role = Role::findOrFail($request->role_id);

        $userData = $request->except('password_confirmation', 'role_id'); // ← EXCLURE role_id

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']);
        }

        // Ajouter le nom du rôle
        $userData['role'] = $role->name;

        // Si c'est un admin, on ignore l'agence
        if ($role->name === 'admin') {
            $userData['agence_id'] = null;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Activer/Désactiver un utilisateur
     */
    public function toggleStatus(User $user)
    {
        // Ne pas permettre de se désactiver soi-même
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['active' => !$user->active]);

        $status = $user->active ? 'activé' : 'désactivé';
        return redirect()->back()
            ->with('success', "Utilisateur {$status} avec succès.");
    }

    /**
     * Afficher les logs de connexion d'un utilisateur
     */
    public function logs(User $user)
    {
        $logs = $user->logsConnexions()->latest()->paginate(20);
        return view('backoffice.users.logs', compact('user', 'logs'));
    }
}