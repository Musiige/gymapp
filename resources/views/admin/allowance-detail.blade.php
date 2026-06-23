<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('admin.allowances') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to allowances</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">{{ $trainer->name }}</h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ $trainer->phone }}</p>
    </div>

    {{-- Balance --}}
    <div class="bfh-card" style="text-align:center;padding:24px;margin-bottom:20px;border-color:{{ $balance > 0 ? '#ff4444' : ($balance < 0 ? '#4caf50' : '#2e2e2e') }}">
        <p style="color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Current balance</p>
        <p style="font-size:32px;font-weight:800;color:{{ $balance > 0 ? '#ff4444' : ($balance < 0 ? '#4caf50' : '#888') }}">
            UGX {{ number_format(abs($balance)) }}
        </p>
        <p style="color:#555;font-size:12px;margin-top:6px">
            @if($balance > 0)
                Gym owes this trainer
            @elseif($balance < 0)
                Trainer has credit (overpaid)
            @else
                All settled
            @endif
        </p>
    </div>

    @if($grouped->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No entries yet.</p>
        </div>
    @else
        @foreach($grouped as $month => $entries)
            <div class="bfh-section-title" style="margin-top:16px">{{ $month }}</div>
            @foreach($entries as $entry)
                <div class="bfh-card" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                    <div>
                        <p style="color:#fff;font-size:14px;font-weight:600">
                            {{ $entry->type === 'demand' ? '📤 ' . ($entry->reason ?? 'Demand') : '📥 Payment received' }}
                        </p>
                        <p style="color:#555;font-size:11px;margin-top:2px">{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</p>
                    </div>
                    <p style="font-size:15px;font-weight:700;color:{{ $entry->type === 'demand' ? '#ff4444' : '#4caf50' }}">
                        {{ $entry->type === 'demand' ? '+' : '-' }}UGX {{ number_format($entry->amount) }}
                    </p>
                </div>
            @endforeach
        @endforeach
    @endif

</x-becky-layout>