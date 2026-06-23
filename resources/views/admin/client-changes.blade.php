<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.clients.show', $client->id) }}" style="color:#555;font-size:13px;text-decoration:none">← Back to {{ $client->name }}</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Package Change <span style="color:#FF6B00">Log</span>
        </h2>
    </div>

    @if($changes->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No package changes recorded.</p>
        </div>
    @else
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

</x-becky-layout>