<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'adherent_id', 'type_document_id', 'fichier_recto', 'fichier_verso',
        'statut', 'commentaire', 'version'
    ];

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function typeDocument()
    {
        return $this->belongsTo(TypeDocument::class);
    }

    public function isValide()
    {
        return $this->statut === 'validé';
    }

    public function isRejete()
    {
        return $this->statut === 'rejeté';
    }

    public function isSoumis()
    {
        return $this->statut === 'soumis';
    }

    public function getCheminFichierRectoAttribute()
    {
        return storage_path('app/documents/' . $this->fichier_recto);
    }

    public function getCheminFichierVersoAttribute()
    {
        return $this->fichier_verso ? storage_path('app/documents/' . $this->fichier_verso) : null;
    }
}