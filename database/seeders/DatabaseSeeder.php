<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AgenceSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,  // Assigner les permissions aux rôles
            UserSeeder::class,
            TypeDocumentSeeder::class,
            PlanSeeder::class,
            // Module Crédit & Règles
            ConditionEligibiliteCreditSeeder::class,
            PenaliteRetraitAnticipeSeeder::class,
            CreditSeeder::class,
        ]);
    }
}