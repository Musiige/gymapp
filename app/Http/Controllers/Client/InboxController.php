<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index()
    {
        $messages = Announcement::where(function ($q) {
            $q->where('recipient_type', 'all')
              ->orWhere(function ($q) {
                  $q->where('recipient_type', 'specific')
                    ->whereJsonContains('recipient_ids', (string) Auth::id());
              });
        })
        ->latest()
        ->get();

        return view('client.inbox', compact('messages'));
    }
}