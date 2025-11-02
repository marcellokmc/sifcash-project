<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditDocument extends Model
{
    protected $fillable = [
        'credit_id',
        'nom',
        'chemin',
        'type',
        'taille',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }
}
