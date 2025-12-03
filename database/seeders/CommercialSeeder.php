<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commercial;

class CommercialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commercials = [
            [
                'nom' => 'Martin',
                'prenoms' => 'Jean Claude',
                'telephone' => '+2250707070701',
                'code_commercial' => 'COMM001',
                'actif' => true,
                'notes' => 'Commercial senior, zone Abidjan Nord'
            ],
            [
                'nom' => 'Kouadio',
                'prenoms' => 'Marie Sophie',
                'telephone' => '+2250707070702',
                'code_commercial' => 'COMM002',
                'actif' => true,
                'notes' => 'Commercial expérimenté, zone Abidjan Sud'
            ],
            [
                'nom' => 'Koné',
                'prenoms' => 'Brahima',
                'telephone' => '+2250707070703',
                'code_commercial' => 'COMM003',
                'actif' => true,
                'notes' => 'Commercial junior, zone Yopougon'
            ],
            [
                'nom' => 'Traoré',
                'prenoms' => 'Aminata',
                'telephone' => '+2250707070704',
                'code_commercial' => 'COMM004',
                'actif' => true,
                'notes' => 'Commercial confirmé, zone Cocody'
            ],
            [
                'nom' => 'Bamba',
                'prenoms' => 'Yves',
                'telephone' => '+2250707070705',
                'code_commercial' => 'COMM005',
                'actif' => false,
                'notes' => 'Commercial en congé'
            ]
        ];

        foreach ($commercials as $commercial) {
            Commercial::create($commercial);
        }
    }
}
