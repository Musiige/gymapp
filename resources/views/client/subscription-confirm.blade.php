<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Change <span style="color:#FF6B00">Package?</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Please review before switching</p>
    </div>

    {{-- Current package --}}
    <div class="bfh-section-title">Your current package</div>
    <div class="bfh-card" style="margin-bottom:16px;opacity:0.7">
        <div class="bfh-row">
            <div>
                <p style="color:#fff;font-size:15px;font-weight:600">{{ $activeSubscription->membership->name }}</p>
                <p style="color:#777;font-size:13px;margin-top:4px">UGX {{ number_format($activeSubscription->membership->price) }}</p>
                <p style="color:#555;font-size:11px;margin-top:2px">Expires {{ \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M Y') }}</p>
            </div>
            <span class="bfh-badge {{ $activeSubscription->status }}">{{ $activeSubscription->status }}</span>
        </div>
    </div>

    <div style="text-align:center;margin:8px 0 16px">
        <span style="color:#FF6B00;font-size:20px">↓</span>
        <p style="color:#666;font-size:12px;margin-top:4px">Will be cancelled and replaced with</p>
    </div>

    {{-- New package --}}
    <div class="bfh-section-title">New package</div>
    <div class="bfh-card orange-border" style="margin-bottom:24px">
        <div class="bfh-row">
            <div>
                <p style="color:#fff;font-size:15px;font-weight:600">{{ $newMembership->name }}</p>
                <p style="color:#FF6B00;font-size:18px;font-weight:700;margin-top:4px">UGX {{ number_format($newMembership->price) }}</p>
                <p style="color:#555;font-size:11px;margin-top:2px">{{ $newMembership->duration_days }} day(s)</p>
                <p style="color:#555;font-size:11px;margin-top:2px">{{ $newMembership->description }}</p>
            </div>
        </div>
    </div>

    {{-- Price difference --}}
    @php
        $diff = $newMembership->price - $activeSubscription->membership->price;
    @endphp
    <div class="bfh-card" style="margin-bottom:24px;text-align:center;padding:20px">
        @if($diff > 0)
            <p style="color:#777;font-size:13px">Price difference</p>
            <p style="color:#FF6B00;font-size:22px;font-weight:700;margin-top:4px">+UGX {{ number_format($diff) }}</p>
            <p style="color:#555;font-size:12px;margin-top:4px">You will need to pay more for the new package</p>
        @elseif($diff < 0)
            <p style="color:#777;font-size:13px">Price difference</p>
            <p style="color:#4caf50;font-size:22px;font-weight:700;margin-top:4px">-UGX {{ number_format(abs($diff)) }}</p>
            <p style="color:#555;font-size:12px;margin-top:4px">The new package costs less</p>
        @else
            <p style="color:#777;font-size:13px">Same price as your current package</p>
        @endif
    </div>

    {{-- Warning --}}
    <div style="background:#3a1a0a;border:0.5px solid #FF6B00;border-radius:12px;padding:14px;margin-bottom:24px">
        <p style="color:#FF6B00;font-size:13px;font-weight:600;margin-bottom:4px">⚠️ Important</p>
        <p style="color:#aaa;font-size:12px;line-height:1.6">Your current {{ $activeSubscription->membership->name }} package will be immediately cancelled and you will need to make a new payment for the {{ $newMembership->name }} package.</p>
    </div>

    {{-- Action buttons --}}
    <form method="POST" action="{{ route('client.subscription.store') }}">
        @csrf
        <input type="hidden" name="membership_id" value="{{ $newMembership->id }}">
        <button type="submit" class="bfh-btn" style="margin-bottom:12px">
            Yes, switch to {{ $newMembership->name }}
        </button>
    </form>

    <a href="{{ route('client.subscription') }}" class="bfh-btn outline" style="display:block;text-align:center">
        Keep my current package
    </a>

</x-becky-layout>