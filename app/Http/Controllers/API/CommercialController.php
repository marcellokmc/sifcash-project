<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commercial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommercialController extends Controller
{
    /**
     * Afficher la liste des commerciaux actifs
     */
    public function index(Request $request): JsonResponse
    {
        $query = Commercial::actifs();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('code_commercial', 'like', "%{$search}%");
            });
        }
        
        $commercials = $query->orderBy('nom')->get(['id', 'nom', 'prenoms', 'code_commercial', 'telephone']);
        
        return response()->json([
            'success' => true,
            'data' => $commercials,
            'count' => $commercials->count()
        ]);
    }
    
    /**
     * Rechercher des commerciaux par code
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|min:2'
        ]);
        
        $commercials = Commercial::actifs()
            ->byCode($request->code)
            ->limit(10)
            ->get(['id', 'nom', 'prenoms', 'code_commercial', 'telephone']);
        
        return response()->json([
            'success' => true,
            'data' => $commercials,
            'count' => $commercials->count()
        ]);
    }
    
    /**
     * Afficher un commercial spécifique
     */
    public function show(Commercial $commercial): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $commercial->loadCount('adherents')
        ]);
    }
}
