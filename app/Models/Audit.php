<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Table has only created_at
    public $timestamps = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'action_category',
        'model_type',
        'model_id',
        'target_user_id',
        'ancienne_valeur',
        'nouvelle_valeur',
        'ip',
        'url',
        'user_agent',
        'description',
        'created_at',
    ];

    protected $casts = [
        'ancienne_valeur' => 'array',
        'nouvelle_valeur' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function model()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Scopes pour filtrer par catégorie
     */
    public function scopePaiements($query)
    {
        return $query->where('action_category', 'paiement');
    }

    public function scopeRetraits($query)
    {
        return $query->where('action_category', 'retrait');
    }

    public function scopeAdhesions($query)
    {
        return $query->where('action_category', 'adhesion');
    }

    public function scopeAffectations($query)
    {
        return $query->where('action_category', 'affectation');
    }

    public function scopeValidations($query)
    {
        return $query->whereIn('action', ['validated', 'approved', 'rejected']);
    }
}
