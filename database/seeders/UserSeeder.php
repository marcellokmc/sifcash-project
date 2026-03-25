<?php

namespace Database\Seeders;

use App\Models\Agence;
use App\Models\User;
use App\Models\Adherent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin principal
        User::updateOrCreate([
            'email' => 'admin@sif.bf'
        ], [
            'name' => 'Administrateur SIF',
            'phone' => '+226 70 00 00 00',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'agence_id' => null
        ]);

        // Agent exemple
        $agence = Agence::where('code', 'OUAGA_01')->first();
        
        User::updateOrCreate([
            'email' => 'agent.ouaga@sif.bf'
        ], [
            'name' => 'Agent Ouaga',
            'phone' => '+226 70 12 34 56',
            'password' => Hash::make('12345678'),
            'role' => 'agent',
            'matricule' => 'AGT001',
            'date_embauche' => '2024-01-15',
            'agence_id' => $agence->id
        ]);

        // Agents supplémentaires pour atteindre au moins 5 utilisateurs
        $agenceKdg = Agence::where('code', 'KOUDOUGOU_01')->first();
        $agenceOhg = Agence::where('code', 'OUAHIGOUYA_01')->first();
        $agenceFada = Agence::where('code', 'FADA_01')->first();

        User::updateOrCreate([
            'email' => 'agent.kdg@sif.bf'
        ], [
            'name' => 'Agent Koudougou',
            'phone' => '+226 70 11 22 33',
            'password' => Hash::make('12345678'),
            'role' => 'agent',
            'matricule' => 'AGT002',
            'date_embauche' => '2024-02-10',
            'agence_id' => optional($agenceKdg)->id
        ]);

        User::updateOrCreate([
            'email' => 'chef.ohg@sif.bf'
        ], [
            'name' => 'Chef Service Ouahigouya',
            'phone' => '+226 70 22 33 44',
            'password' => Hash::make('12345678'),
            'role' => 'chef_service',
            'matricule' => 'CS001',
            'date_embauche' => '2023-11-01',
            'agence_id' => optional($agenceOhg)->id
        ]);


        // Utilisateur adhérent de test
        $adherentUser = User::updateOrCreate([
            'email' => 'adherent@sif.bf'
        ], [
            'name' => 'Adhérent Test',
            'phone' => '+226 70 55 66 77',
            'password' => Hash::make('12345678'),
            'role' => 'adherent',
            'agence_id' => $agence->id
        ]);

        // Créer le profil adhérent associé
        Adherent::updateOrCreate([
            'user_id' => $adherentUser->id
        ], [
            'membre_id' => 'SIFBF-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'nom' => 'Test',
            'prenom' => 'Adhérent',
            'date_naissance' => '1990-01-01',
            'lieu_naissance' => 'Ouagadougou',
            'adresse' => '123 Rue de la République, Ouagadougou',
            'telephone' => '+226 70 55 66 77',
            'email' => 'adherent@sif.bf',
            'contact_urgence_nom' => 'Contact',
            'contact_urgence_prenoms' => 'Urgence',
            'contact_urgence_lien_parente' => 'Frère',
            'contact_urgence_telephone' => '+226 70 88 99 00',
            'residence' => 'Ouagadougou',
            'situation_famille' => 'marié',
            'profession' => 'Employé',
            'statut_compte' => 'actif',
            'date_activation' => now(),
        ]);
    }
}
