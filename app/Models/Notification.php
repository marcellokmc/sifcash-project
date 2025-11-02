<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'lu',
        'type',
        'action_by_user_id',
        'action',
        'entity_type',
        'entity_id',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actionByUser()
    {
        return $this->belongsTo(User::class, 'action_by_user_id');
    }
}
