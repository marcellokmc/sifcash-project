<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description', 'recto_requis', 'verso_requis', 'actif', 'obligatoire'
    ];

    protected $casts = [
        'recto_requis' => 'boolean',
        'verso_requis' => 'boolean',
        'actif' => 'boolean',
        'obligatoire' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function isActif()
    {
        return $this->actif;
    }

    public function getChampsRequisAttribute()
    {
        $champs = [];
        if ($this->recto_requis) $champs[] = 'recto';
        if ($this->verso_requis) $champs[] = 'verso';
        return $champs;
    }
}