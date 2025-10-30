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
        // Admin: list all notifications
        $items = Notification::query()->with('user')->latest()->paginate(20);
        if ($request->wantsJson()) {
            return response()->json($items);
        }
        return view('backoffice.notifications.index', compact('items'));
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
}
