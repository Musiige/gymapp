<x-becky-layout>

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('client.dashboard') }}" style="color:#555;font-size:20px;text-decoration:none">←</a>
        <div>
            <h2 style="color:#fff;font-size:22px;font-weight:800">{{ $assignment->workout->title }}</h2>
            <p style="color:#777;font-size:13px;margin-top:2px">Assigned by {{ $assignment->workout->trainer->name }}</p>
        </div>
    </div>

    <div class="bfh-card orange-border" style="margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
            <div class="bfh-icon-box" style="width:52px;height:52px;font-size:20px">
                {{ strtoupper(substr($assignment->workout->title, 0, 1)) }}
            </div>
            <div>
                <p style="color:#fff;font-size:16px;font-weight:700">{{ $assignment->workout->title }}</p>
                <p style="color:#555;font-size:12px;margin-top:2px">
                    Assigned {{ \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="bfh-divider"></div>

        <div class="bfh-section-title">Description</div>
        <p style="color:#ccc;font-size:14px;line-height:1.8">{{ $assignment->workout->description }}</p>
    </div>

    <div class="bfh-card">
        <div class="bfh-section-title">Trainer</div>
        <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;background:#2a2a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:15px;font-weight:700">
                {{ strtoupper(substr($assignment->workout->trainer->name, 0, 2)) }}
            </div>
            <div>
                <p style="color:#fff;font-size:14px;font-weight:600">{{ $assignment->workout->trainer->name }}</p>
                <p style="color:#555;font-size:12px;margin-top:2px">{{ $assignment->workout->trainer->phone }}</p>
            </div>
        </div>
    </div>

</x-becky-layout>