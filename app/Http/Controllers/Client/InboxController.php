<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index()
    {
      $userCreatedAt = Auth::user()->created_at;

        $messages = Announcement::where(function ($q) use ($userCreatedAt) {
            $q->where(function ($q) use ($userCreatedAt) {
                $q->where('recipient_type', 'all')
                  ->where('created_at', '>=', $userCreatedAt);
            })
            ->orWhere(function ($q) {
                $q->where('recipient_type', 'specific')
                  ->whereJsonContains('recipient_ids', (string) Auth::id());
            });
        })
        ->with(['reads' => function ($q) {
            $q->where('user_id', Auth::id());
        }])
        ->latest()
        ->get();

        // Mark all as read
        foreach ($messages as $message) {
            if (!$message->reads->count()) {
                AnnouncementRead::create([
                    'announcement_id' => $message->id,
                    'user_id'         => Auth::id(),
                    'read_at'         => now(),
                ]);
            }
        }

        return view('client.inbox', compact('messages'));
    }

  public static function unreadCount()
    {
        $userId = Auth::id();
        $userCreatedAt = Auth::user()->created_at;

        return Announcement::where(function ($q) use ($userId, $userCreatedAt) {
            $q->where(function ($q) use ($userCreatedAt) {
                $q->where('recipient_type', 'all')
                  ->where('created_at', '>=', $userCreatedAt);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('recipient_type', 'specific')
                  ->whereJsonContains('recipient_ids', (string) $userId);
            });
        })
        ->whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->count();
    }
}