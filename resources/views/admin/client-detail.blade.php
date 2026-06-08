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
            <div class="bfh-stat-sub">UGX all time</div>
        </div>
        <div class="bfh-stat">
            <div class="bfh-stat-label">Packages</div>
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

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px">
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total due</p>
                        <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                        <p style="color:#4caf50;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Balance</p>
                        <p style="color:#FF6B00;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->balance ?? $sub->membership->price) }}</p>
                    </div>
                    <div>
                        <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Method</p>
                        <p style="color:#aaa;font-size:13px;font-weight:600;margin-top:2px;text-transform:capitalize">
    {{ $sub->payment->payment_method ?? '—' }}
   @if($sub->payment && $sub->payment->marked_by_trainer_id)
    <span style="color:#FF6B00;font-size:11px;display:block;margin-top:2px">
        Recorded by {{ $sub->payment->markedByTrainer->name ?? 'Trainer' }}
    </span>
@elseif($sub->payment && $sub->payment->marked_paid_by_admin)
    <span style="color:#4a9eff;font-size:11px;display:block;margin-top:2px">
        Recorded by admin
    </span>
@endif
</p>
                    </div>
                </div>

                <div style="padding-top:10px;border-top:0.5px solid #222;margin-bottom:10px">
                    <p style="color:#555;font-size:11px">
                        {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }} →
                        {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                        @if($sub->payment && $sub->payment->marked_paid_by_admin)
                            · <span style="color:#4a9eff">Recorded by admin</span>
                        @endif
                    </p>
                </div>

                {{-- Custom price setter --}}
@if(in_array($sub->status, ['active', 'pending']))
    <div style="background:#0a0a0a;border-radius:10px;padding:12px;margin-bottom:8px">
        <p style="color:#4a9eff;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px">
            Set agreed price (discount)
        </p>
        <form method="POST" action="{{ route('admin.subscription.set-price', $sub->id) }}">
            @csrf
            <div style="display:flex;gap:8px">
                <input type="number" name="custom_price"
                    placeholder="Custom amount (UGX)"
                    value="{{ $sub->custom_price ?? $sub->membership->price }}"
                    min="0"
                    class="bfh-input" style="flex:1;padding:10px 12px">
                <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap;background:#1a2a3a;border:0.5px solid #4a9eff;color:#4a9eff">
                    Set
                </button>
            </div>
            <p style="color:#555;font-size:11px;margin-top:6px">
                Default: UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}
                @if($sub->custom_price)
                    · <span style="color:#4a9eff">Discounted to UGX {{ number_format($sub->custom_price) }}</span>
                @endif
            </p>
        </form>
    </div>
@endif

                {{-- Cash payment form for active/pending with outstanding balance --}}
                @if(in_array($sub->status, ['active', 'pending']) && (!$sub->payment || $sub->payment->status !== 'paid'))
                    <div style="background:#0a0a0a;border-radius:10px;padding:12px;margin-top:4px">
                        <p style="color:#FF6B00;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px">Record cash payment</p>
                        <form method="POST" action="{{ route('admin.payment.mark', $sub->id) }}">
                            @csrf
                            <div style="display:flex;gap:8px;flex-wrap:wrap">
                                <input type="number" name="amount_paid"
                                    placeholder="Amount (UGX)"
                                    min="1"
                                    value="{{ $sub->payment ? $sub->payment->balance : $sub->membership->price }}"
                                    class="bfh-input" style="flex:1;min-width:120px;padding:10px 12px">
                                <select name="payment_method" class="bfh-select" style="flex:1;min-width:100px;padding:10px 12px">
                                    <option value="cash">Cash</option>
                                    <option value="momo">MoMo</option>
                                    <option value="airtel">Airtel</option>
                                </select>
                                <button type="submit" class="bfh-btn sm" style="width:auto;padding:10px 16px;white-space:nowrap">
                                    Record
                                </button>
                            </div>
                        </form>
                        @if($sub->payment && $sub->payment->amount_paid > 0)
    <div style="display:flex;gap:8px;margin-top:10px">
        {{-- Edit payment --}}
        <form method="POST" action="{{ route('admin.payment.edit', $sub->id) }}" style="flex:1">
            @csrf
            <div style="display:flex;gap:6px">
                <input type="number" name="amount_paid"
                    value="{{ $sub->payment->amount_paid }}"
                    min="0"
                    class="bfh-input" style="flex:1;padding:8px 10px;font-size:12px">
                <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 12px;background:#1a2a3a;border:0.5px solid #4a9eff;color:#4a9eff;white-space:nowrap">
                    Edit
                </button>
            </div>
        </form>

        {{-- Void payment --}}
        <form method="POST" action="{{ route('admin.payment.void', $sub->id) }}"
            onsubmit="return confirm('Are you sure you want to void this payment? This cannot be undone.')">
            @csrf
            <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 14px;background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444;white-space:nowrap">
                Void
            </button>
        </form>
    </div>
@endif
                    </div>
                @endif
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
                        <p style="color:#4caf50;font-size:11px;margin-top:2px">✓ Self check-in by client</p>
                    @else
                        <p style="color:#FF6B00;font-size:11px;margin-top:2px">
                            ✓ Marked by {{ $record->trainer->name ?? 'Trainer' }}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

</x-becky-layout>