<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Admin: list all notifications with filters
        $query = Notification::query()->with(['user', 'actionByUser']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('lu')) {
            $query->where('lu', $request->lu === '1');
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(20)->appends($request->query());
        
        // Stats pour la vue
        $stats = [
            'total' => Notification::count(),
            'non_lues' => Notification::where('lu', false)->count(),
            'par_type' => Notification::selectRaw('type, COUNT(*) as count')->groupBy('type')->pluck('count', 'type'),
        ];

        if ($request->wantsJson()) {
            return response()->json($items);
        }
        return view('backoffice.notifications.index', compact('items', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Non applicable'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Création non autorisée'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        return response()->json($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        return response()->json(['message' => 'Non applicable'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        return response()->json(['message' => 'Mise à jour non autorisée'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        return response()->json(['message' => 'Suppression non autorisée'], 405);
    }

    // Admin: mark any notification as read (backoffice)
    public function adminMarkRead(Request $request, Notification $notification)
    {
        $notification->lu = true;
        $notification->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification marquée comme lue']);
        }
        return back()->with('success', 'Notification marquée comme lue');
    }

    // Adherent: list own notifications
    public function indexForAdherent(Request $request)
    {
        $user = $request->user();
        $items = Notification::where('user_id', $user->id)->latest()->paginate(20);
        if ($request->wantsJson()) {
            return response()->json($items);
        }
        return view('adherent.notifications.index', compact('items'));
    }

    // Adherent: mark notification as read
    public function markReadForAdherent(Request $request, Notification $notification)
    {
        $user = $request->user();
        if ($notification->user_id !== $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }
            abort(403, 'Non autorisé');
        }
        $notification->lu = true;
        $notification->save();
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Notification marquée comme lue']);
        }
        
        return redirect()->route('adherent.notifications.index')
                        ->with('success', 'Notification marquée comme lue');
    }

    // API: unread count + latest notifications
    public function unreadCount(Request $request)
    {
        $userId = $request->user()->id;
        $count = Notification::where('user_id', $userId)->where('lu', false)->count();
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get(['id','titre as title','message','type','lu as read_at','created_at']);

        return response()->json([
            'count' => $count,
            'notifications' => $notifications,
        ]);
    }

    // API: recent notifications list (latest 10)
    public function recent(Request $request)
    {
        $userId = $request->user()->id;
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->limit(10)
            ->get(['id','titre as title','message','type','lu as read_at','created_at']);

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    // API: mark one as read
    public function markRead(Request $request, Notification $notification)
    {
        $user = $request->user();
        abort_unless($notification->user_id === $user->id, 403, 'Non autorisé');
        if (!$notification->lu) {
            $notification->lu = true;
            $notification->save();
        }
        return response()->json(['message' => 'OK']);
    }

    // API: mark all as read for current user
    public function markAllRead(Request $request)
    {
        $userId = $request->user()->id;
        Notification::where('user_id', $userId)->where('lu', false)->update(['lu' => true]);
        return response()->json(['message' => 'OK']);
    }

    // Admin: mark all notifications as read (toutes les notifications du système)
    public function markAllReadAdmin(Request $request)
    {
        Notification::where('lu', false)->update(['lu' => true]);
        
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Toutes les notifications ont été marquées comme lues']);
        }
        
        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
}
