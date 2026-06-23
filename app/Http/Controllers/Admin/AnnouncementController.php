<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
public function index()
    {
        $clients = User::whereIn('role', ['client', 'trainer'])->get();
        $sentAnnouncements = Announcement::latest()->get();

        return view('admin.announcements', compact('clients', 'sentAnnouncements'));
    }

   public function send(Request $request, NotificationService $notificationService)
{
    $request->validate([
        'title'     => ['required', 'string', 'max:100'],
        'message'   => ['required', 'string', 'max:500'],
        'recipient' => ['required', 'in:all,specific'],
    ]);

    if ($request->recipient === 'specific') {
        $clientIds = $request->input('client_ids', []);

        if (empty($clientIds)) {
            return back()->with('error', 'Please select at least one recipient.');
        }

        \App\Models\Announcement::create([
            'admin_id'       => Auth::id(),
            'title'          => $request->title,
            'message'        => $request->message,
            'recipient_type' => 'specific',
            'recipient_ids'  => $clientIds,
        ]);

        $recipients = User::whereIn('id', $clientIds)
            ->whereNotNull('fcm_token')
            ->get();

        foreach ($recipients as $recipient) {
            $notificationService->sendToToken($recipient->fcm_token, $request->title, $request->message);
        }

        return back()->with('success', 'Message sent to ' . count($clientIds) . ' recipient(s).');
    }

    \App\Models\Announcement::create([
        'admin_id'       => Auth::id(),
        'title'          => $request->title,
        'message'        => $request->message,
        'recipient_type' => 'all',
        'recipient_ids'  => null,
    ]);

    $tokens = User::whereIn('role', ['client', 'trainer'])
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->toArray();

    $totalRecipients = User::whereIn('role', ['client', 'trainer'])->count();

    if (empty($tokens)) {
        return back()->with('success', 'Announcement saved. Sent to ' . $totalRecipients . ' recipient(s), though none have notifications enabled yet.');
    }

   $notificationService->sendToMultiple($tokens, $request->title, $request->message);
    return back()->with('success', 'Announcement sent to ' . $totalRecipients . ' recipient(s).');
}

public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }
}