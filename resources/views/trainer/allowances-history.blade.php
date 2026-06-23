<x-becky-layout>

    <div style="margin-bottom:24px">
        <a href="{{ route('trainer.allowances') }}" style="color:#555;font-size:13px;text-decoration:none">← Back to allowances</a>
        <h2 style="color:#fff;font-size:22px;font-weight:800;margin-top:8px">
            Full <span style="color:#FF6B00">Ledger</span>
        </h2>
    </div>

    @if($grouped->isNotEmpty())
        <form method="POST" action="{{ route('trainer.allowances.destroy-all') }}"
            onsubmit="return confirm('Delete ALL ledger entries? This cannot be undone and will reset your balance to zero.')"
            style="margin-bottom:20px">
            @csrf
            @method('DELETE')
            <button type="submit" class="bfh-btn danger" style="width:100%">🗑️ Delete All Entries</button>
        </form>
    @endif

    @if($grouped->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No entries yet.</p>
        </div>
    @else
        @foreach($grouped as $month => $entries)
            <div class="bfh-section-title" style="margin-top:16px">{{ $month }}</div>
            @foreach($entries as $entry)
                <div class="bfh-card" style="margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
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
                    <form method="POST" action="{{ route('trainer.allowances.destroy', $entry->id) }}"
                        onsubmit="return confirm('Delete this entry?')" style="padding-top:8px;border-top:0.5px solid #222">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer">
                            Delete
                        </button>
                    </form>
                </div>
            @endforeach
        @endforeach
    @endif

</x-becky-layout>