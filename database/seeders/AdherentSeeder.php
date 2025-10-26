<?php

namespace Database\Seeders;

use App\Models\Adherent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdherentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer quelques adhérents de test avec les nouveaux champs
        $adherentsData = [
            [
                'user' => [
                    'name' => 'Amadou Traoré',
                    'email' => 'amadou.traore@example.com',
                    'phone' => '70123456',
                    'password' => Hash::make('password'),
                    'role' => 'adherent'
                ],
                'adherent' => [
                    'nom' => 'Traoré',
                    'prenom' => 'Amadou',
                    'date_naissance' => '1985-05-15',
                    'lieu_naissance' => 'Ouagadougou',
                    'adresse' => 'Zone du Bois, Rue 12.34',
                    'telephone' => '70123456',
                    'email' => 'amadou.traore@example.com',
                    'contact_urgence_nom' => 'Fatimata Traoré',
                    'contact_urgence_lien' => 'conjoint',
                    'contact_urgence_telephone' => '76987654',
                    'residence' => 'Ouagadougou',
                    'secteur_numero' => 'Secteur 15',
                    'profession_exercee' => 'Enseignant',
                    'situation_famille' => 'marié',
                    'profession' => 'Professeur de mathématiques',
                    'statut_compte' => 'actif',
                    'date_activation' => now()
                ]
            ],
            [
                'user' => [
                    'name' => 'Mariam Ouédraogo',
                    'email' => 'mariam.ouedraogo@example.com',
                    'phone' => '76543210',
                    'password' => Hash::make('password'),
                    'role' => 'adherent'
                ],
                'adherent' => [
                    'nom' => 'Ouédraogo',
                    'prenom' => 'Mariam',
                    'date_naissance' => '1990-08-22',
                    'lieu_naissance' => 'Bobo-Dioulasso',
                    'adresse' => 'Koulouba, Rue 25.67',
                    'telephone' => '76543210',
                    'email' => 'mariam.ouedraogo@example.com',
                    'contact_urgence_nom' => 'Ibrahim Ouédraogo',
                    'contact_urgence_lien' => 'frere',
                    'contact_urgence_telephone' => '70111222',
                    'residence' => 'Bobo-Dioulasso',
                    'secteur_numero' => 'Zone 3',
                    'profession_exercee' => 'Commerçante',
                    'situation_famille' => 'celibataire',
                    'profession' => 'Vendeuse de tissus',
                    'statut_compte' => 'actif',
                    'date_activation' => now()
                ]
            ],
            [
                'user' => [
                    'name' => 'Boureima Sawadogo',
                    'email' => 'boureima.sawadogo@example.com',
                    'phone' => '78901234',
                    'password' => Hash::make('password'),
                    'role' => 'adherent'
                ],
                'adherent' => [
                    'nom' => 'Sawadogo',
                    'prenom' => 'Boureima',
                    'date_naissance' => '1982-12-03',
                    'lieu_naissance' => 'Koudougou',
                    'adresse' => 'Patte d\'Oie, Rue 45.89',
                    'telephone' => '78901234',
                    'email' => 'boureima.sawadogo@example.com',
                    'contact_urgence_nom' => 'Salimata Sawadogo',
                    'contact_urgence_lien' => 'conjoint',
                    'contact_urgence_telephone' => '77333444',
                    'residence' => 'Ouagadougou',
                    'secteur_numero' => 'Secteur 22',
                    'profession_exercee' => 'Mécanicien',
                    'situation_famille' => 'marié',
                    'profession' => 'Réparateur automobile',
                    'statut_compte' => 'en_attente_de_verification'
                ]
            ]
        ];

        foreach ($adherentsData as $data) {
            $user = User::create($data['user']);
            $data['adherent']['user_id'] = $user->id;
            Adherent::create($data['adherent']);
        }
    }
}
