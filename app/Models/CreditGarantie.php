<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditGarantie extends Model
{
    protected $fillable = [
        'credit_id',
        'type',
        'description',
        'valeur_estimee',
        'document_preuve_path',
    ];

    protected $casts = [
        'valeur_estimee' => 'decimal:2',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }
}
