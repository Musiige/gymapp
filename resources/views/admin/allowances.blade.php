<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Trainer <span style="color:#FF6B00">Allowances</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">View-only — trainers manage their own entries</p>
    </div>

    @if($trainerBalances->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:32px">
            <p style="font-size:32px;margin-bottom:12px">💰</p>
            <p style="color:#555;font-size:14px">No trainers registered yet.</p>
        </div>
    @else
        @foreach($trainerBalances as $row)
            <a href="{{ route('admin.allowances.show', $row['trainer']->id) }}" style="text-decoration:none;display:block;margin-bottom:12px">
                <div class="bfh-card">
                    <div style="display:flex;align-items:center;gap:14px">
                        <div style="width:44px;height:44px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:15px;font-weight:700;flex-shrink:0">
                            {{ strtoupper(substr($row['trainer']->name, 0, 2)) }}
                        </div>
                        <div style="flex:1">
                            <p style="color:#fff;font-size:14px;font-weight:600">{{ $row['trainer']->name }}</p>
                            <p style="color:#555;font-size:12px;margin-top:2px">{{ $row['entry_count'] }} entries</p>
                        </div>
                        <div style="text-align:right">
                            <p style="font-size:16px;font-weight:700;color:{{ $row['balance'] > 0 ? '#ff4444' : ($row['balance'] < 0 ? '#4caf50' : '#888') }}">
                                UGX {{ number_format(abs($row['balance'])) }}
                            </p>
                            <p style="color:#555;font-size:10px;margin-top:2px">
                                {{ $row['balance'] > 0 ? 'Owed' : ($row['balance'] < 0 ? 'Credit' : 'Settled') }}
                            </p>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    @endif

</x-becky-layout>