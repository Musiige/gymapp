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
        $clients = User::where('role', 'client')->get();
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
            return back()->with('error', 'Please select at least one client.');
        }

        \App\Models\Announcement::create([
            'admin_id'       => Auth::id(),
            'title'          => $request->title,
            'message'        => $request->message,
            'recipient_type' => 'specific',
            'recipient_ids'  => $clientIds,
        ]);

        $clients = User::whereIn('id', $clientIds)
            ->whereNotNull('fcm_token')
            ->get();

        foreach ($clients as $client) {
            $notificationService->sendToToken($client->fcm_token, $request->title, $request->message);
        }

        return back()->with('success', 'Message sent to ' . count($clientIds) . ' client(s).');
    }

    \App\Models\Announcement::create([
        'admin_id'       => Auth::id(),
        'title'          => $request->title,
        'message'        => $request->message,
        'recipient_type' => 'all',
        'recipient_ids'  => null,
    ]);

    $tokens = User::where('role', 'client')
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->toArray();

    if (empty($tokens)) {
        return back()->with('error', 'No clients have enabled notifications yet.');
    }

    $notificationService->sendToMultiple($tokens, $request->title, $request->message);
    $totalClients = User::where('role', 'client')->count();
return back()->with('success', 'Announcement sent to ' . $totalClients . ' clients.');
}
public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }
}