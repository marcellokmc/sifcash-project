<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Audit;
use App\Models\LogConnexion;
use App\Models\Adherent;
use App\Models\Agence;


class User extends Authenticatable
{
    use HasFactory, Notifiable ;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'matricule',
        'date_embauche', 'agence_id', 'active', 'suspended_at', 'suspension_reason', 'suspended_by'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_embauche' => 'date',
            'active' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class);
    }

    public function logsConnexions()
    {
        return $this->hasMany(LogConnexion::class);
    }

    public function adherent()
    {
        return $this->hasOne(Adherent::class);
    }

    /**
     * Adhérents gérés par cet agent (relation many-to-many)
     */
    public function adherentsGeres()
    {
        return $this->belongsToMany(Adherent::class, 'adherent_agent', 'agent_id', 'adherent_id')
            ->withPivot('is_principal', 'notes', 'affecte_le', 'affecte_par')
            ->withTimestamps();
    }

    /**
     * Adhérents dont cet agent est le principal responsable
     */
    public function adherentsPrincipaux()
    {
        return $this->belongsToMany(Adherent::class, 'adherent_agent', 'agent_id', 'adherent_id')
            ->wherePivot('is_principal', true)
            ->withPivot('is_principal', 'notes', 'affecte_le', 'affecte_par')
            ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isAdherent()
    {
        return $this->role === 'adherent';
    }
    
    public function isChefService()
    {
        return $this->role === 'chef_service';
    }

    public function isSuperviseur()
    {
        return $this->role === 'superviseur';
    }
    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur a l'un des rôles
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array)$roles);
    }
    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    public function targetedAudits()
    {
        return $this->hasMany(Audit::class, 'target_user_id');
    }
    public function logsConnexion()
    {
        return $this->hasMany(LogConnexion::class);
    }
    
    /**
     * Relation avec nos notifications personnalisées
     * (évite le conflit avec le trait Notifiable de Laravel)
     */
    public function customNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id');
    }

    /**
     * Relation avec l'utilisateur qui a suspendu ce compte
     */
    public function suspendedByUser()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    /**
     * Suspendre le compte utilisateur
     */
    public function suspend($reason = null, $suspendedBy = null)
    {
        $this->update([
            'suspended_at' => now(),
            'suspension_reason' => $reason,
            'suspended_by' => $suspendedBy,
            'active' => false,
        ]);
    }

    /**
     * Réactiver le compte utilisateur
     */
    public function activate()
    {
        $this->update([
            'suspended_at' => null,
            'suspension_reason' => null,
            'suspended_by' => null,
            'active' => true,
        ]);
    }

    /**
     * Vérifier si le compte est suspendu
     */
    public function isSuspended()
    {
        return !is_null($this->suspended_at);
    }

    /**
     * Vérifier si le compte est actif (non suspendu)
     */
    public function isActive()
    {
        return $this->active && is_null($this->suspended_at);
    }
}
