<x-becky-layout>

    {{-- Header --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('admin.clients') }}" style="color:#555;font-size:20px;text-decoration:none">←</a>
        <div>
            <h2 style="color:#fff;font-size:22px;font-weight:800">{{ $client->name }}</h2>
            <p style="color:#777;font-size:13px;margin-top:2px">{{ $client->phone }} · {{ $client->email }}</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="bfh-stat-grid">
        <div class="bfh-stat">
            <div class="bfh-stat-label">Sessions</div>
            <div class="bfh-stat-value">{{ $totalSessions }}</div>
            <div class="bfh-stat-sub">All time</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Total paid</div>
            <div class="bfh-stat-value" style="font-size:16px">{{ number_format($totalPaid) }}</div>
            <div class="bfh-stat-sub">UGX</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Subscriptions</div>
            <div class="bfh-stat-value">{{ $client->subscriptions->count() }}</div>
            <div class="bfh-stat-sub">All time</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Changes</div>
            <div class="bfh-stat-value">{{ $changes->count() }}</div>
            <div class="bfh-stat-sub">Package switches</div>
        </div>
    </div>

    {{-- Subscription history --}}
    <div class="bfh-section-title">Subscription history</div>
    @if($client->subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px;margin-bottom:16px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @foreach($client->subscriptions->sortByDesc('created_at') as $sub)
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->membership->name }}</p>
                    <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total due</p>
                        <p style="color:#fff;font-size:12px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                        <p style="color:#4caf50;font-size:12px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Balance</p>
                        <p style="color:#FF6B00;font-size:12px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->balance ?? $sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Payment method</p>
                        <p style="color:#aaa;font-size:12px;font-weight:600;margin-top:2px;text-transform:capitalize">{{ $sub->payment->payment_method ?? '—' }}</p>
                    </div>
                </div>
                <div style="margin-top:10px;padding-top:10px;border-top:0.5px solid #222">
                    <p style="color:#555;font-size:11px">
                        {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }} →
                        {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Package change log --}}
    @if($changes->isNotEmpty())
        <div class="bfh-section-title">Package change log</div>
        @foreach($changes as $change)
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span style="background:#2a2a2a;color:#888;font-size:12px;padding:4px 10px;border-radius:20px">
                        {{ $change->oldMembership->name }}
                    </span>
                    <span style="color:#FF6B00;font-size:14px">→</span>
                    <span style="background:#FF6B00;color:#fff;font-size:12px;padding:4px 10px;border-radius:20px">
                        {{ $change->newMembership->name }}
                    </span>
                </div>
                <div style="margin-top:8px;display:flex;justify-content:space-between;align-items:center">
                    <p style="color:#555;font-size:11px">Changed by: <span style="color:#aaa;text-transform:capitalize">{{ $change->changed_by }}</span></p>
                    <p style="color:#555;font-size:11px">{{ \Carbon\Carbon::parse($change->changed_at)->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        @endforeach
    @endif

    {{-- Attendance history --}}
    <div class="bfh-section-title">Attendance history ({{ $attendance->count() }})</div>
    @if($attendance->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No attendance recorded yet.</p>
        </div>
    @else
        @foreach($attendance as $record)
            <div class="bfh-card" style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
                    {{ $record->session_slot === 'morning' ? '🌅' : ($record->session_slot === 'midday' ? '☀️' : '🌙') }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:13px;font-weight:500;text-transform:capitalize">{{ $record->session_slot }} session</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">
                        {{ \Carbon\Carbon::parse($record->attended_at)->format('d M Y, h:i A') }}
                    </p>
                    @if($record->marked_by === 'client')
                        <p style="color:#4caf50;font-size:11px;margin-top:2px">✓ Self check-in</p>
                    @else
                        <p style="color:#FF6B00;font-size:11px;margin-top:2px">✓ Marked by {{ $record->trainer->name ?? 'Trainer' }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>