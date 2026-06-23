<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">My <span style="color:#FF6B00">Allowances</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Track money owed and received</p>
    </div>

    {{-- Balance --}}
    <div class="bfh-card" style="text-align:center;padding:24px;margin-bottom:20px;border-color:{{ $balance > 0 ? '#ff4444' : ($balance < 0 ? '#4caf50' : '#2e2e2e') }}">
        <p style="color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Current balance</p>
        <p style="font-size:32px;font-weight:800;color:{{ $balance > 0 ? '#ff4444' : ($balance < 0 ? '#4caf50' : '#888') }}">
            UGX {{ number_format(abs($balance)) }}
        </p>
        <p style="color:#555;font-size:12px;margin-top:6px">
            @if($balance > 0)
                Gym owes you
            @elseif($balance < 0)
                You have credit (overpaid)
            @else
                All settled
            @endif
        </p>
    </div>

    {{-- Add entry --}}
    <div class="bfh-section-title">Add entry</div>
    <div class="bfh-card" style="margin-bottom:24px">
        <form method="POST" action="{{ route('trainer.allowances.store') }}" id="entry-form">
            @csrf

            <div class="bfh-form-group">
                <label class="bfh-form-label">Date</label>
                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="bfh-input" max="{{ now()->format('Y-m-d') }}">
                @error('date')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">Type</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    <label style="cursor:pointer">
                        <input type="radio" name="type" value="demand" checked style="display:none" id="type-demand" onclick="toggleType('demand')">
                        <div id="card-demand" class="bfh-card" style="margin-bottom:0;text-align:center;padding:12px;border-color:#FF6B00">
                            <p style="font-size:18px;margin-bottom:4px">📤</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">I'm owed</p>
                        </div>
                    </label>
                    <label style="cursor:pointer">
                        <input type="radio" name="type" value="payment" style="display:none" id="type-payment" onclick="toggleType('payment')">
                        <div id="card-payment" class="bfh-card" style="margin-bottom:0;text-align:center;padding:12px;border-color:#2e2e2e">
                            <p style="font-size:18px;margin-bottom:4px">📥</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">I received</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bfh-form-group" id="reason-group">
                <label class="bfh-form-label">Reason</label>
                <input type="text" name="reason" value="{{ old('reason') }}" placeholder="e.g. Transport, Water" class="bfh-input">
                @error('reason')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Amount (UGX)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" min="1" placeholder="e.g. 5000" class="bfh-input">
                @error('amount')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Add Entry</button>
        </form>
    </div>

   {{-- Ledger --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
        <div class="bfh-section-title" style="margin-bottom:0">Recent entries ({{ $entries->count() }} total)</div>
        @if($entries->count() > 5)
            <a href="{{ route('trainer.allowances.history') }}" style="color:#FF6B00;font-size:11px;text-decoration:none;font-weight:600">View full ledger →</a>
        @endif
    </div>
    @if($entries->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No entries yet.</p>
        </div>
    @else
        @foreach($entries->take(5) as $entry)
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
                <div style="display:flex;gap:8px;padding-top:8px;border-top:0.5px solid #222">
                    <button type="button" onclick="document.getElementById('edit-form-{{ $entry->id }}').classList.toggle('hidden-form')"
                        style="background:#1a2a3a;border:0.5px solid #4a9eff;color:#4a9eff;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('trainer.allowances.destroy', $entry->id) }}"
                        onsubmit="return confirm('Delete this entry?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer">
                            Delete
                        </button>
                    </form>
                </div>

                {{-- Edit form (hidden by default) --}}
                <form id="edit-form-{{ $entry->id }}" method="POST" action="{{ route('trainer.allowances.update', $entry->id) }}" class="hidden-form" style="margin-top:12px;padding-top:12px;border-top:0.5px solid #222">
                    @csrf
                    @method('PUT')
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px">
                        <input type="date" name="date" value="{{ $entry->date }}" class="bfh-input" style="padding:8px 10px;font-size:12px">
                        <select name="type" class="bfh-select" style="padding:8px 10px;font-size:12px">
                            <option value="demand" {{ $entry->type === 'demand' ? 'selected' : '' }}>I'm owed</option>
                            <option value="payment" {{ $entry->type === 'payment' ? 'selected' : '' }}>I received</option>
                        </select>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px">
                        <input type="text" name="reason" value="{{ $entry->reason }}" placeholder="Reason" class="bfh-input" style="padding:8px 10px;font-size:12px">
                        <input type="number" name="amount" value="{{ $entry->amount }}" min="1" class="bfh-input" style="padding:8px 10px;font-size:12px">
                    </div>
                    <button type="submit" class="bfh-btn sm" style="width:auto;padding:8px 16px">Save Changes</button>
                </form>
            </div>
        @endforeach
    @endif

    <style>
        .hidden-form { display: none; }
    </style>

    <script>
        function toggleType(type) {
            document.getElementById('card-demand').style.borderColor = type === 'demand' ? '#FF6B00' : '#2e2e2e';
            document.getElementById('card-payment').style.borderColor = type === 'payment' ? '#FF6B00' : '#2e2e2e';
            document.getElementById('reason-group').style.display = type === 'demand' ? 'block' : 'none';
        }
    </script>

</x-becky-layout>