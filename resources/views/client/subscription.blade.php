<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Choose a <span style="color:#FF6B00">Package</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Select the plan that works best for you</p>
    </div>

    @if($activeSubscription)
        <div class="bfh-section-title">Current package</div>
        <div class="bfh-card orange-border" style="margin-bottom:24px">
            <div class="bfh-row">
                <div>
                    <p style="color:#fff;font-size:15px;font-weight:600">{{ $activeSubscription->membership->name }}</p>
                    <p style="color:#FF6B00;font-size:13px;margin-top:4px">UGX {{ number_format($activeSubscription->membership->price) }}</p>
                    <p style="color:#555;font-size:11px;margin-top:2px">Expires {{ \Carbon\Carbon::parse($activeSubscription->end_date)->format('d M Y') }}</p>
                </div>
                <span class="bfh-badge {{ $activeSubscription->status }}">{{ $activeSubscription->status }}</span>
            </div>
        </div>
    @endif

    <div class="bfh-section-title">All packages</div>

    @foreach($memberships as $membership)
        <form method="POST" action="{{ route('client.subscription.store') }}">
            @csrf
            <input type="hidden" name="membership_id" value="{{ $membership->id }}">
            <button type="submit"
                @if($activeSubscription && $activeSubscription->membership_id != $membership->id)
                    onclick="return confirm('You already have an active {{ addslashes($activeSubscription->membership->name) }} package. Are you sure you want to switch to {{ addslashes($membership->name) }}? Your current package will be cancelled.')"
                @endif
                style="width:100%;text-align:left;background:none;border:none;padding:0;cursor:pointer;margin-bottom:12px;display:block">
                <div class="bfh-card {{ $activeSubscription && $activeSubscription->membership_id == $membership->id ? 'orange-border' : '' }}"
                    style="margin-bottom:0;display:flex;justify-content:space-between;align-items:center;transition:border-color 0.2s">
                    <div style="flex:1;min-width:0">
                        <p style="color:#fff;font-size:15px;font-weight:600">{{ $membership->name }}</p>
                        <p style="color:#777;font-size:12px;margin-top:4px">{{ $membership->description }}</p>
                        <p style="color:#555;font-size:11px;margin-top:4px">{{ $membership->duration_days }} day(s)</p>
                    </div>
                    <div style="text-align:right;flex-shrink:0;margin-left:16px">
                        <p style="color:#FF6B00;font-size:18px;font-weight:700">UGX {{ number_format($membership->price) }}</p>
                        @if($activeSubscription && $activeSubscription->membership_id == $membership->id)
                            <span class="bfh-badge active" style="margin-top:6px;display:inline-block">Current</span>
                        @else
                            <span style="color:#555;font-size:12px;margin-top:6px;display:block">Tap to select →</span>
                        @endif
                    </div>
                </div>
            </button>
        </form>
    @endforeach

</x-becky-layout>