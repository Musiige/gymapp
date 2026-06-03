<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements');
    }

    public function send(Request $request, NotificationService $notificationService)
    {
        $request->validate([
            'title'   => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        $tokens = User::where('role', 'client')
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) {
            return back()->with('error', 'No clients have enabled notifications yet.');
        }

        $notificationService->sendToMultiple($tokens, $request->title, $request->message);

        return back()->with('success', 'Announcement sent to ' . count($tokens) . ' clients.');
    }
}