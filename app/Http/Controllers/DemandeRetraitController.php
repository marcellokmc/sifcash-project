<?php

namespace App\Http\Controllers;

use App\Models\DemandeRetrait;
use App\Models\Adhesion;
use App\Models\Notification;
use App\Models\HistoriqueRetrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DemandeRetraitController extends Controller
{
    /**
     * Display a listing of the resource (Backoffice).
     */
    public function index(Request $request)
    {
        $retraits = DemandeRetrait::with(['adherent', 'adhesion.plan', 'validatedByAgent', 'historiques'])
            ->latest()
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($retraits);
        }

        return view('backoffice.retraits.index', compact('retraits'));
    }

    /**
     * Display the specified resource (Backoffice).
     */
    public function show(Request $request, DemandeRetrait $retrait)
    {
        $retrait->load(['adherent', 'adhesion.plan', 'validatedByAgent', 'historiques']);

        if ($request->wantsJson()) {
            return response()->json($retrait);
        }

        return view('backoffice.retraits.show', compact('retrait'));
    }

    /**
     * Validate a withdrawal request (Backoffice).
     */
    public function validateRetrait(Request $request, DemandeRetrait $retrait)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $retrait->update([
            'statut' => 'validé',
            'date_validation' => now(),
            'validated_by_agent_id' => Auth::id(),
        ]);

        // Créer un historique
        HistoriqueRetrait::create([
            'demande_retrait_id' => $retrait->id,
            'action' => 'Validation',
            'description' => 'Demande de retrait validée par ' . Auth::user()->nom . ' ' . Auth::user()->prenom,
            'user_id' => Auth::id(),
        ]);

        // Créer une notification pour l'adhérent
        Notification::create([
            'user_id' => $retrait->adherent->user_id,
            'titre' => 'Demande de retrait validée',
            'message' => 'Votre demande de retrait de ' . number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA a été validée.',
            'lu' => false,
            'type' => 'success',
        ]);

        return redirect()->back()
            ->with('success', 'Demande de retrait validée avec succès.');
    }

    /**
     * Reject a withdrawal request (Backoffice).
     */
    public function reject(Request $request, DemandeRetrait $retrait)
    {
        $validator = Validator::make($request->all(), [
            'motif_rejet' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $retrait->update([
            'statut' => 'rejeté',
            'motif_rejet' => $request->motif_rejet,
            'date_validation' => now(),
            'validated_by_agent_id' => Auth::id(),
        ]);

        // Créer un historique
        HistoriqueRetrait::create([
            'demande_retrait_id' => $retrait->id,
            'action' => 'Rejet',
            'description' => 'Demande de retrait rejetée par ' . Auth::user()->nom . ' ' . Auth::user()->prenom . '. Motif: ' . $request->motif_rejet,
            'user_id' => Auth::id(),
        ]);

        // Créer une notification pour l'adhérent
        Notification::create([
            'user_id' => $retrait->adherent->user_id,
            'titre' => 'Demande de retrait rejetée',
            'message' => 'Votre demande de retrait de ' . number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA a été rejetée. Motif: ' . $request->motif_rejet,
            'lu' => false,
            'type' => 'error',
        ]);

        return redirect()->back()
            ->with('success', 'Demande de retrait rejetée avec succès.');
    }

    /**
     * Process a withdrawal request (Backoffice).
     */
    public function process(Request $request, DemandeRetrait $retrait)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($retrait->statut !== 'validé') {
            return redirect()->back()
                ->with('error', 'La demande doit être validée avant d\'être traitée.');
        }

        $retrait->update([
            'statut' => 'traité',
        ]);

        // Mettre à jour le solde de l'adhésion
        $adhesion = $retrait->adhesion;
        $adhesion->update([
            'montant_retrait' => $adhesion->montant_retrait + $retrait->montant_demande,
            'solde_actuel' => $adhesion->solde_actuel - $retrait->montant_demande,
        ]);

        // Créer un historique
        HistoriqueRetrait::create([
            'demande_retrait_id' => $retrait->id,
            'action' => 'Traitement',
            'description' => 'Demande de retrait traitée par ' . Auth::user()->nom . ' ' . Auth::user()->prenom . '. Montant retiré: ' . number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA',
            'user_id' => Auth::id(),
        ]);

        // Créer une notification pour l'adhérent
        Notification::create([
            'user_id' => $retrait->adherent->user_id,
            'titre' => 'Retrait traité',
            'message' => 'Votre demande de retrait de ' . number_format($retrait->montant_demande, 0, ',', ' ') . ' FCFA a été traitée avec succès.',
            'lu' => false,
            'type' => 'success',
        ]);

        return redirect()->back()
            ->with('success', 'Demande de retrait traitée avec succès.');
    }

    // ==================== ESPACE ADHÉRENT ====================

    /**
     * Display a listing of the resource (Adhérent).
     */
    public function indexForAdherent(Request $request)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $retraits = DemandeRetrait::where('adherent_id', $adherent->id)
            ->with(['adhesion.plan', 'historiques'])
            ->latest()
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json($retraits);
        }

        return view('adherent.retraits.index', compact('retraits'));
    }

    /**
     * Show the form for creating a new resource (Adhérent).
     */
    public function createForAdherent()
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $adhesions = Adhesion::where('adherent_id', $adherent->id)
            ->where('statut', 'actif')
            ->whereRaw('solde_actuel - montant_retrait > 0')
            ->with('plan')
            ->get();

        return view('adherent.retraits.create', compact('adhesions'));
    }

    /**
     * Store a newly created resource in storage (Adhérent).
     */
    public function storeForAdherent(Request $request)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent) {
            return redirect()->route('adherent.inscription')
                ->with('error', 'Profil adhérent non trouvé.');
        }

        $validator = Validator::make($request->all(), [
            'adhesion_id' => 'required|exists:adhesions,id',
            'type_retrait' => 'required|in:a_terme,anticipe',
            'montant_demande' => 'required|numeric|min:0.01',
            'mode_retrait' => 'required|in:mobile_money,virement,cheque,especes',
            'informations_retrait' => 'nullable|array',
            'motif' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que l'adhésion appartient à l'adhérent
        $adhesion = Adhesion::where('id', $request->adhesion_id)
            ->where('adherent_id', $adherent->id)
            ->first();

        if (!$adhesion) {
            return redirect()->back()
                ->with('error', 'Adhésion non trouvée.')
                ->withInput();
        }

        // Vérifier le solde disponible
        $soldeDisponible = $adhesion->solde_actuel - $adhesion->montant_retrait;
        if ($request->montant_demande > $soldeDisponible) {
            return redirect()->back()
                ->with('error', 'Le montant demandé dépasse le solde disponible (' . number_format($soldeDisponible, 0, ',', ' ') . ' FCFA).')
                ->withInput();
        }

        $retrait = DemandeRetrait::create([
            'adherent_id' => $adherent->id,
            'adhesion_id' => $request->adhesion_id,
            'type_retrait' => $request->type_retrait,
            'montant_demande' => $request->montant_demande,
            'mode_retrait' => $request->mode_retrait,
            'informations_retrait' => $request->informations_retrait,
            'statut' => 'soumis',
            'date_demande' => now(),
        ]);

        // Créer un historique
        HistoriqueRetrait::create([
            'demande_retrait_id' => $retrait->id,
            'adherent_id' => $adherent->id,
            'adhesion_id' => $request->adhesion_id,
            'montant_retire' => 0, // Pas encore retiré
            'mode_retrait' => $request->mode_retrait,
            'informations_retrait' => $request->informations_retrait,
            'date_retrait' => now(), // Date de soumission
        ]);

        return redirect()->route('adherent.retraits.show', $retrait)
            ->with('success', 'Demande de retrait soumise avec succès. Elle sera traitée dans les 24-48h.');
    }

    /**
     * Display the specified resource (Adhérent).
     */
    public function showForAdherent(Request $request, DemandeRetrait $retrait)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $retrait->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        $retrait->load(['adhesion.plan', 'validatedByAgent', 'historiques']);

        if ($request->wantsJson()) {
            return response()->json($retrait);
        }

        return view('adherent.retraits.show', compact('retrait'));
    }

    /**
     * Remove the specified resource from storage (Adhérent).
     */
    public function destroyForAdherent(DemandeRetrait $retrait)
    {
        $adherent = Auth::user()?->adherent;

        if (!$adherent || $retrait->adherent_id !== $adherent->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($retrait->statut !== 'soumis') {
            return redirect()->route('adherent.retraits.show', $retrait)
                ->with('error', 'Cette demande ne peut pas être annulée.');
        }

        // Créer un historique d'annulation
        HistoriqueRetrait::create([
            'demande_retrait_id' => $retrait->id,
            'adherent_id' => $adherent->id,
            'adhesion_id' => $retrait->adhesion_id,
            'montant_retire' => 0, // Annulé
            'mode_retrait' => $retrait->mode_retrait,
            'informations_retrait' => null,
            'date_retrait' => now(), // Date d'annulation
        ]);

        $retrait->delete();

        return redirect()->route('adherent.retraits.index')
            ->with('success', 'Demande de retrait annulée avec succès.');
    }
}
