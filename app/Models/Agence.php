<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'nom', 'province', 'departement', 'adresse', 
        'contact', 'active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function adherents()
    {
        return $this->hasMany(Adherent::class);
    }
}
