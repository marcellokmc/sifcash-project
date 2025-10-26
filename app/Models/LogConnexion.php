<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogConnexion extends Model
{
    use HasFactory;

    protected $table = 'logs_connexions';

    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'action', 'created_at'];

    public $timestamps = false; // Désactiver les timestamps Eloquent

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}