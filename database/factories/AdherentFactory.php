<?php

namespace Database\Factories;

use App\Models\Adherent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdherentFactory extends Factory
{
    protected $model = Adherent::class;

    public function definition(): array
    {
        $user = User::factory()->create([
            'role' => 'adherent',
            'email' => fake()->unique()->safeEmail(),
        ]);

        return [
            'user_id' => $user->id,
            'membre_id' => 'SIFBF-'.str_pad((string)fake()->unique()->numberBetween(1, 999999), 6, '0', STR_PAD_LEFT),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date('Y-m-d', '-20 years'),
            'lieu_naissance' => fake()->city(),
            'adresse' => fake()->address(),
            'telephone' => fake()->phoneNumber(),
            'telephone_secondaire' => null,
            'email' => $user->email,
            'contact_urgence_nom' => fake()->lastName(),
            'contact_urgence_prenoms' => fake()->firstName(),
            'contact_urgence_lien_parente' => 'parent',
            'contact_urgence_telephone' => fake()->phoneNumber(),
            'contact_urgence_secondaire_nom' => null,
            'contact_urgence_secondaire_prenoms' => null,
            'contact_urgence_secondaire_lien_parente' => null,
            'contact_urgence_secondaire_telephone' => null,
            'residence' => fake()->city(),
            'secteur_numero' => fake()->randomElement(['Secteur ' . fake()->numberBetween(1, 30), 'Zone ' . fake()->numberBetween(1, 15), null]),
            'profession_exercee' => fake()->randomElement(['Enseignant', 'Commerçant', 'Agriculteur', 'Artisan', 'Fonctionnaire', 'Chauffeur', 'Mécanicien', null]),
            'situation_famille' => 'celibataire',
            'profession' => fake()->jobTitle(),
            'statut_compte' => 'actif',
            'date_activation' => now(),
        ];
    }
}
