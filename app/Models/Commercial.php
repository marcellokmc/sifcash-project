<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commercial extends Model
{
    protected $fillable = [
        'nom',
        'prenoms',
        'telephone',
        'code_commercial',
        'actif',
        'notes'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function adherents(): HasMany
    {
        return $this->hasMany(Adherent::class);
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->nom} {$this->prenoms}";
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code_commercial', 'like', "%{$code}%");
    }
}
