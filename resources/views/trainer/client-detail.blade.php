<x-becky-layout>

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('trainer.clients') }}" style="color:#555;font-size:20px;text-decoration:none">←</a>
        <div>
            <h2 style="color:#fff;font-size:22px;font-weight:800">{{ $client->name }}</h2>
            <p style="color:#777;font-size:13px;margin-top:2px">{{ $client->phone }} · {{ $client->email }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Sessions</div>
            <div class="bfh-stat-value">{{ $attendance->count() }}</div>
            <div class="bfh-stat-sub">All time</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Packages</div>
            <div class="bfh-stat-value">{{ $client->subscriptions->count() }}</div>
            <div class="bfh-stat-sub">All time</div>
        </div>
    </div>

   {{-- Current subscription --}}
    <div class="bfh-section-title">Current subscription</div>
    @php
        $sortedSubs = $client->subscriptions->sortByDesc('created_at');
        $currentSub = $sortedSubs->first();
        $olderSubs = $sortedSubs->skip(1);
    @endphp
    @if($client->subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px;margin-bottom:16px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @php $sub = $currentSub; @endphp
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->membership->name }}</p>
                    <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px">
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total due</p>
                        <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                        <p style="color:#4caf50;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Balance</p>
                        <p style="color:#FF6B00;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->balance ?? ($sub->custom_price ?? $sub->membership->price)) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Expires</p>
                        <p style="color:#aaa;font-size:13px;font-weight:600;margin-top:2px">{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Access toggle --}}
                @if(in_array($sub->status, ['active', 'pending']))
                    <div style="display:flex;justify-content:space-between;align-items:center;background:#0a0a0a;border-radius:10px;padding:12px;margin-bottom:8px">
                        <div>
                            <p style="color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1px">Gym access</p>
                            <p style="font-size:13px;font-weight:600;margin-top:2px;color:{{ $sub->access_granted ? '#4caf50' : '#ff4444' }}">
                                {{ $sub->access_granted ? '✓ Granted' : '✕ Not granted' }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('trainer.subscription.toggle-access', $sub->id) }}">
                            @csrf
                            <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 16px;
                                {{ $sub->access_granted ? 'background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444' : 'background:#1a3a1a;border:0.5px solid #4caf50;color:#4caf50' }}">
                                {{ $sub->access_granted ? 'Revoke Access' : 'Grant Access' }}
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Cash payment form --}}
                @if(in_array($sub->status, ['active', 'pending']) && (!$sub->payment || $sub->payment->status !== 'paid'))
                    <div style="background:#0a0a0a;border-radius:10px;padding:12px">
                        <p style="color:#FF6B00;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px">Record cash payment</p>
                        <form method="POST" action="{{ route('trainer.payment.mark', $sub->id) }}">
                            @csrf
                            <div style="display:flex;gap:8px">
                                <input type="number" name="amount_paid"
                                    placeholder="Amount (UGX)"
                                    value="{{ $sub->payment ? $sub->payment->balance : ($sub->custom_price ?? $sub->membership->price) }}"
                                    min="1"
                                    class="bfh-input" style="flex:1;padding:10px 12px">
                                <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">
                                    Record
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif($sub->payment && $sub->payment->status === 'paid')
                    <div style="background:#1a3a1a;border-radius:10px;padding:10px;text-align:center">
                        <p style="color:#4caf50;font-size:12px;font-weight:600">✓ Fully paid — UGX {{ number_format($sub->payment->amount_paid) }}</p>
                    </div>
                @endif
           </div>
    @endif

    {{-- Assign / change package --}}
    <div style="background:#0a0a0a;border-radius:10px;padding:12px;margin-bottom:10px">
        <p style="color:#4a9eff;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px">
            {{ $client->subscriptions->isEmpty() ? 'Assign package' : 'Change package' }}
        </p>
        <form method="POST" action="{{ route('trainer.clients.assign-package', $client->id) }}"
            onsubmit="return confirm('This will replace the client\'s current package. Continue?')">
            @csrf
            <div style="display:flex;gap:8px">
                <select name="membership_id" class="bfh-select" style="flex:1;padding:10px 12px">
                    <option value="">Select package</option>
                    @foreach($memberships as $membership)
                        <option value="{{ $membership->id }}">
                            {{ $membership->name }} — UGX {{ number_format($membership->price) }}
                            ({{ $membership->duration_days }} day{{ $membership->duration_days == 1 ? '' : 's' }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">
                    Assign
                </button>
            </div>
        </form>
    </div>

   {{-- Previous subscriptions link --}}
    @if($olderSubs->isNotEmpty())
        <a href="{{ route('trainer.clients.subscriptions', $client->id) }}" class="bfh-card" style="display:flex;justify-content:space-between;align-items:center;text-decoration:none;margin-top:8px">
            <p style="color:#aaa;font-size:13px;font-weight:600">Previous subscriptions ({{ $olderSubs->count() }})</p>
            <span style="color:#444;font-size:18px">›</span>
        </a>
    @endif

   {{-- Assigned workouts --}}
    <div class="bfh-section-title" style="margin-top:8px">Assigned workouts ({{ $client->workoutAssignments->count() }})</div>
    @if($client->workoutAssignments->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No workouts assigned yet.</p>
        </div>
    @else
       @foreach($client->workoutAssignments as $assignment)
           <a href="{{ route('trainer.workouts.show', $assignment->workout->id) }}" style="text-decoration:none">
                <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                    <div class="bfh-icon-box">{{ strtoupper(substr($assignment->workout->title, 0, 1)) }}</div>
                    <div style="flex:1">
                        <p style="color:#fff;font-size:13px;font-weight:600">{{ $assignment->workout->title }}</p>
                        <p style="color:#555;font-size:11px;margin-top:2px">by {{ $assignment->workout->trainer->name }}</p>
                    </div>
                    <span style="color:#444;font-size:18px">›</span>
                </div>
            </a>
        @endforeach
    @endif

   {{-- Attendance history (recent 5) --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <div class="bfh-section-title" style="margin-bottom:0">Recent attendance ({{ $attendance->count() }} total)</div>
        @if($attendance->count() > 5)
            <a href="{{ route('trainer.clients.attendance', $client->id) }}" style="color:#FF6B00;font-size:11px;text-decoration:none;font-weight:600">View all →</a>
        @endif
    </div>
    @if($attendance->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded yet.</p>
        </div>
    @else
        @foreach($attendance->take(5) as $record)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
                    {{ $record->session_slot === 'morning' ? '🌅' : ($record->session_slot === 'midday' ? '☀️' : '🌙') }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500;text-transform:capitalize">{{ $record->session_slot }} session</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>