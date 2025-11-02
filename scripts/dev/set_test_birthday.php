<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

use App\Models\Adherent;
use Carbon\Carbon;

$adherent = Adherent::query()->whereNotNull('user_id')->where('statut_compte','actif')->first();
if (!$adherent) {
    echo "No active adherent with user found\n";
    exit(0);
}
$adherent->update(['date_naissance' => Carbon::now()->subYears(30)->startOfDay()]);
echo "Updated adherent #{$adherent->id} to birthday today (turning 30)\n";
