<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Adherents: colonnes générées pour anniversaire + index statut
        Schema::table('adherents', function (Blueprint $table) {
            if (!Schema::hasColumn('adherents', 'birth_month')) {
                $table->unsignedTinyInteger('birth_month')->storedAs('MONTH(date_naissance)')->nullable();
            }
            if (!Schema::hasColumn('adherents', 'birth_day')) {
                $table->unsignedTinyInteger('birth_day')->storedAs('DAY(date_naissance)')->nullable();
            }
            if (!Schema::hasColumn('adherents', 'statut_compte')) {
                // colonne déjà existante selon le modèle; on ne la crée pas, on indexe si possible
            }
        });
        // Indexes séparés (MySQL exige ALTER INDEX après création colonnes)
        Schema::table('adherents', function (Blueprint $table) {
            if (Schema::hasColumn('adherents', 'birth_month') && !self::hasIndex('adherents', 'adherents_birth_month_index')) {
                $table->index('birth_month');
            }
            if (Schema::hasColumn('adherents', 'birth_day') && !self::hasIndex('adherents', 'adherents_birth_day_index')) {
                $table->index('birth_day');
            }
            if (Schema::hasColumn('adherents', 'statut_compte') && !self::hasIndex('adherents', 'adherents_statut_compte_index')) {
                $table->index('statut_compte');
            }
        });

        // Notifications: index created_at
        Schema::table('notifications', function (Blueprint $table) {
            if (!self::hasIndex('notifications', 'notifications_created_at_index')) {
                $table->index('created_at');
            }
        });

        // Index rapides sur colonnes statut usuelles si présentes
        foreach ([
            'credits' => 'statut',
            'epargnes' => 'statut',
            'demande_retraits' => 'statut',
            'paiements' => 'statut',
            'adhesions' => 'statut',
        ] as $tbl => $col) {
            if (Schema::hasTable($tbl) && Schema::hasColumn($tbl, $col) && !self::hasIndex($tbl, $tbl.'_'.$col.'_index')) {
                Schema::table($tbl, function (Blueprint $table) use ($col) {
                    $table->index($col);
                });
            }
        }
    }

    public function down(): void
    {
        // On ne supprime pas les colonnes générées par prudence (en prod).
        // Vous pouvez ajouter la suppression si nécessaire.
    }

    private static function hasIndex(string $table, string $indexName): bool
    {
        $dbName = DB::getDatabaseName();
        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', $dbName)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
        return $exists;
    }
};
