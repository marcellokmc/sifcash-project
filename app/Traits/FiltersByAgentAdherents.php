<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FiltersByAgentAdherents
{
    /**
     * Filtre une requête pour ne retourner que les ressources liées aux adhérents affectés à l'agent
     * 
     * @param Builder $query
     * @param string $adherentRelation Nom de la relation vers l'adhérent (ex: 'adherent')
     * @return Builder
     */
    protected function scopeForAgentAdherents(Builder $query, $adherentRelation = 'adherent')
    {
        $user = Auth::user();
        
        // Si l'utilisateur est un admin, pas de filtrage
        if (!$user || $user->role === 'admin') {
            return $query;
        }
        
        // Si l'utilisateur est un agent ou chef de service, filtrer par adhérents affectés
        if (in_array($user->role, ['agent', 'chef_service'])) {
            return $query->whereHas($adherentRelation . '.agents', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            });
        }
        
        return $query;
    }
    
    /**
     * Applique le filtre par agent sur une requête donnée
     * 
     * @param Builder $query
     * @param string|null $adherentRelation Nom de la relation vers l'adhérent. Si null, on suppose que la requête est sur Adherent directement
     * @return Builder
     */
    protected function applyAgentFilter(Builder $query, $adherentRelation = 'adherent')
    {
        $user = Auth::user();
        
        // Admin : pas de filtre
        if (!$user || $user->role === 'admin') {
            return $query;
        }
        
        // Agent/Chef de service : filtrer par adhérents affectés
        if (in_array($user->role, ['agent', 'chef_service'])) {
            // Si $adherentRelation est null, on suppose qu'on filtre directement sur Adherent
            if ($adherentRelation === null) {
                return $query->whereHas('agents', function($q) use ($user) {
                    $q->where('agent_id', $user->id);
                });
            }
            
            // Sinon, on filtre via la relation
            return $query->whereHas($adherentRelation . '.agents', function($q) use ($user) {
                $q->where('agent_id', $user->id);
            });
        }
        
        return $query;
    }
    
    /**
     * Récupère les IDs des adhérents affectés à l'agent connecté
     * 
     * @return array
     */
    protected function getAgentAdherentIds()
    {
        $user = Auth::user();
        
        // Si admin, retourner null (pas de filtrage)
        if (!$user || $user->role === 'admin') {
            return null;
        }
        
        // Si agent ou chef de service, retourner les IDs des adhérents affectés
        if (in_array($user->role, ['agent', 'chef_service'])) {
            return $user->adherentsGeres()->pluck('adherents.id')->toArray();
        }
        
        return [];
    }
    
    /**
     * Vérifie si un adhérent est affecté à l'agent connecté
     * 
     * @param int $adherentId
     * @return bool
     */
    protected function canAccessAdherent($adherentId)
    {
        $user = Auth::user();
        
        // Admin peut tout accéder
        if (!$user || $user->role === 'admin') {
            return true;
        }
        
        // Agent/Chef de service : vérifier l'affectation
        if (in_array($user->role, ['agent', 'chef_service'])) {
            return $user->adherentsGeres()->where('adherents.id', $adherentId)->exists();
        }
        
        return false;
    }
}
