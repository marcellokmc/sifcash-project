// Removed in simplified credits module

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EcheanceCredit extends Model
{
    protected $fillable = [
        'credit_id',
        'numero_echeance',
        'date_echeance',
        'montant_attendu',
        'montant_paye',
        'penalite_appliquee',
        'statut',
        'date_paiement',
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'montant_attendu' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'penalite_appliquee' => 'decimal:2',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementCredit::class, 'echeance_credit_id');
    }
}
