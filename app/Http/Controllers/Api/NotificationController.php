<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /** GET /api/notifications */
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return NotificationResource::collection($notifications);
    }

    /** PATCH /api/notifications/{id}/read */
    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $notification->update(['read_at' => now()]);
        return new NotificationResource($notification);
    }

    /** PATCH /api/notifications/read-all */
    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->noContent();
    }

    /** DELETE /api/notifications/clear */
    public function clear(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->delete();
        return response()->noContent();
    }
}
