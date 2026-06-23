<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.dashboard') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to dashboard</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            All <span style="color:#FF6B00">Subscriptions</span>
        </h2>
    </div>

    <form method="GET" action="{{ route('admin.reports.subscriptions') }}" style="margin-bottom:20px">
        <div style="position:relative">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Search by client name or phone..."
                class="bfh-input" style="padding-left:40px">
            <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#555;font-size:16px">🔍</span>
            @if($search ?? false)
                <a href="{{ route('admin.reports.subscriptions') }}" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#FF6B00;font-size:12px;text-decoration:none">Clear ✕</a>
            @endif
        </div>
    </form>

    @if($subscriptions->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No subscriptions yet.</p>
        </div>
    @else
        @foreach($subscriptions as $sub)
            <a href="{{ route('admin.clients.show', $sub->user->id) }}" style="text-decoration:none">
                <div class="bfh-card" style="margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:36px;height:36px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($sub->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p style="color:#fff;font-size:14px;font-weight:600">{{ $sub->user->name }}</p>
                                <p style="color:#555;font-size:11px">{{ $sub->user->phone }}</p>
                            </div>
                        </div>
                        <span class="bfh-badge {{ $sub->status }}">{{ $sub->status }}</span>
                    </div>
                    <div class="bfh-divider" style="margin:0 0 12px"></div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Package</p>
                            <p style="color:#aaa;font-size:13px;font-weight:600;margin-top:2px">{{ $sub->membership->name }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Total Due</p>
                            <p style="color:#fff;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->custom_price ?? $sub->membership->price) }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Paid</p>
                            <p style="color:#4caf50;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->amount_paid ?? 0) }}</p>
                        </div>
                        <div>
                            <p style="color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px">Balance</p>
                            <p style="color:#FF6B00;font-size:13px;font-weight:600;margin-top:2px">UGX {{ number_format($sub->payment->balance ?? $sub->membership->price) }}</p>
                        </div>
                    </div>
                    <div style="margin-top:10px;padding-top:10px;border-top:0.5px solid #222">
                        <p style="color:#555;font-size:11px">
                            Expires {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }} ·
                            Started {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach

        @if($subscriptions->hasPages())
            <div style="display:flex;justify-content:center;gap:8px;margin-top:16px;flex-wrap:wrap">
                @if($subscriptions->onFirstPage())
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">← Prev</span>
                @else
                    <a href="{{ $subscriptions->previousPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">← Prev</a>
                @endif
                <span style="padding:8px 14px;background:#FF6B00;color:#fff;border-radius:8px;font-size:12px">{{ $subscriptions->currentPage() }} / {{ $subscriptions->lastPage() }}</span>
                @if($subscriptions->hasMorePages())
                    <a href="{{ $subscriptions->nextPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">Next →</a>
                @else
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">Next →</span>
                @endif
            </div>
        @endif
    @endif

</x-becky-layout>