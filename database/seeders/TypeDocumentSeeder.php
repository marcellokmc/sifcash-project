<?php

namespace Database\Seeders;

use App\Models\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'nom' => 'CNIB',
                'description' => 'Carte Nationale d\'Identité Burkinabè (17 chiffres NIP)',
                'recto_requis' => true,
                'verso_requis' => true,
                'actif' => true
            ],
            [
                'nom' => 'Passeport',
                'description' => 'Passeport en cours de validité',
                'recto_requis' => true,
                'verso_requis' => false,
                'actif' => true
            ],
            [
                'nom' => 'Permis de conduire',
                'description' => 'Permis de conduire burkinabè',
                'recto_requis' => true,
                'verso_requis' => true,
                'actif' => true
            ],
            [
                'nom' => 'Attestation d\'identité',
                'description' => 'Attestation d\'identité délivrée par l\'autorité compétente',
                'recto_requis' => true,
                'verso_requis' => false,
                'actif' => true
            ]
        ];

        foreach ($types as $type) {
            TypeDocument::updateOrCreate(
                ['nom' => $type['nom']],
                $type
            );
        }
    }
}