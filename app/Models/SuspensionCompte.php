<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuspensionCompte extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'adherent_id',
        'raison',
        'date_debut',
        'date_fin',
        'created_by_agent_id',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function createdByAgent()
    {
        return $this->belongsTo(User::class, 'created_by_agent_id');
    }
}
