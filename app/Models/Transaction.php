<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'type_transaction',
        'reference',
        'montant',
        'user_id',
        'adherent_id',
        'adhesion_id',
        'credit_id',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function adhesion()
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
