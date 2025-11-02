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
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
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

    /**
     * Obtenir l'objet Role associé à cet utilisateur
     */
    public function roleObject()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasPermissionTo($permission)
    {
        // Admin a toutes les permissions
        if ($this->isAdmin()) {
            return true;
        }

        // Récupérer le rôle de l'utilisateur
        $role = Role::where('name', $this->role)->first();
        
        if (!$role) {
            return false;
        }

        // Vérifier si le rôle a la permission
        return $role->permissions()->where('name', $permission)->exists();
    }

    /**
     * Vérifier si l'utilisateur a l'une des permissions
     */
    public function hasAnyPermission($permissions)
    {
        // Admin a toutes les permissions
        if ($this->isAdmin()) {
            return true;
        }

        foreach ((array)$permissions as $permission) {
            if ($this->hasPermissionTo($permission)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Vérifier si l'utilisateur a toutes les permissions
     */
    public function hasAllPermissions($permissions)
    {
        // Admin a toutes les permissions
        if ($this->isAdmin()) {
            return true;
        }

        foreach ((array)$permissions as $permission) {
            if (!$this->hasPermissionTo($permission)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obtenir toutes les permissions de l'utilisateur
     */
    public function getAllPermissions()
    {
        // Admin a toutes les permissions
        if ($this->isAdmin()) {
            return Permission::all();
        }

        $role = Role::where('name', $this->role)->first();
        
        if (!$role) {
            return collect([]);
        }

        return $role->permissions;
    }

    /**
     * Vérifier si l'utilisateur peut accéder aux données d'une agence
     */
    public function canAccessAgence($agenceId)
    {
        // Admin a accès à toutes les agences
        if ($this->isAdmin()) {
            return true;
        }

        // L'utilisateur a accès uniquement à son agence
        return $this->agence_id == $agenceId;
    }

    /**
     * Vérifier si l'utilisateur peut gérer un autre utilisateur
     */
    public function canManageUser($targetUser)
    {
        // Admin peut gérer tout le monde
        if ($this->isAdmin()) {
            return true;
        }

        // Chef de service et superviseur peuvent gérer les utilisateurs de leur agence
        if ($this->isChefService() || $this->isSuperviseur()) {
            return $this->agence_id === $targetUser->agence_id;
        }

        return false;
    }

    /**
     * Vérifier si l'utilisateur peut gérer un adhérent
     */
    public function canManageAdherent($adherent)
    {
        // Admin peut gérer tous les adhérents
        if ($this->isAdmin()) {
            return true;
        }

        // Chef de service et superviseur peuvent gérer les adhérents de leur agence
        if ($this->isChefService() || $this->isSuperviseur()) {
            return $this->agence_id === $adherent->agence_id;
        }

        // Agent peut gérer uniquement les adhérents qui lui sont affectés
        if ($this->isAgent()) {
            return $adherent->agents()->where('agent_id', $this->id)->exists();
        }

        return false;
    }
}
