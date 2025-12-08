<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())->with('user');

        if ($request->has('is_read')) {
            $query->where('is_read', $request->is_read === 'true' || $request->is_read === '1');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $notifications = $query->paginate($request->get('per_page', 15));
        return NotificationResource::collection($notifications);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['required', 'in:INFO,SUCCESS,WARNING,ERROR,SYSTEM'],
            'related_type' => ['nullable', 'string', 'max:255'],
            'related_id' => ['nullable', 'integer'],
        ]);

        $notification = Notification::create($request->all());
        return new NotificationResource($notification->load('user'));
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        return new NotificationResource($notification->load('user'));
    }

    public function update(Request $request, Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate(['is_read' => ['sometimes', 'boolean']]);
        $notification->update($request->only(['is_read']));

        if ($request->is_read && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return new NotificationResource($notification->load('user'));
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $notification->update(['is_read' => true, 'read_at' => now()]);
        return new NotificationResource($notification->load('user'));
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function deleteAll()
    {
        Notification::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'All notifications deleted']);
    }
}
