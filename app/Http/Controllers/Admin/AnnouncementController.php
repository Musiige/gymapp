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
        $clients = User::where('role', 'client')
            ->whereNotNull('fcm_token')
            ->get();

        return view('admin.announcements', compact('clients'));
    }

    public function send(Request $request, NotificationService $notificationService)
    {
        $request->validate([
            'title'      => ['required', 'string', 'max:100'],
            'message'    => ['required', 'string', 'max:500'],
            'recipient'  => ['required', 'in:all,specific'],
            'client_id'  => ['required_if:recipient,specific', 'exists:users,id'],
        ]);

        if ($request->recipient === 'specific') {
            $client = User::findOrFail($request->client_id);
            if (!$client->fcm_token) {
                return back()->with('error', 'This client has not enabled notifications.');
            }
            $notificationService->sendToToken($client->fcm_token, $request->title, $request->message);
            return back()->with('success', 'Message sent to ' . $client->name . '.');
        }

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