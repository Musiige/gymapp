<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Mark <span style="color:#FF6B00">Attendance</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">{{ now()->format('l, d M Y') }}</p>
    </div>

    <div class="bfh-section-title">Record attendance</div>
    <div class="bfh-card" style="margin-bottom:20px">
        <form method="POST" action="{{ route('trainer.attendance.store') }}" id="attendance-form">
            @csrf
            <div class="bfh-form-group" style="position:relative">
                <label class="bfh-form-label">Client</label>
                <input type="text" id="client-search-input"
                    placeholder="Type client name or phone..."
                    class="bfh-input" autocomplete="off">
                <input type="hidden" name="client_id" id="selected-client-id">

                <div id="client-results" style="display:none;position:absolute;top:100%;left:0;right:0;background:#1e1e1e;border:0.5px solid #333;border-radius:10px;margin-top:4px;max-height:240px;overflow-y:auto;z-index:50">
                    @foreach($clients as $client)
                        <div class="client-option" data-id="{{ $client->id }}" data-name="{{ $client->name }} — {{ $client->phone }}"
                            data-search="{{ strtolower($client->name . ' ' . $client->phone) }}"
                            style="padding:10px 14px;cursor:pointer;border-bottom:0.5px solid #2a2a2a">
                            <p style="color:#fff;font-size:13px">{{ $client->name }}</p>
                            <p style="color:#666;font-size:11px;margin-top:2px">{{ $client->phone }}</p>
                        </div>
                    @endforeach
                </div>
                @error('client_id')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Session</label>
                <select name="session_slot" class="bfh-select">
                    <option value="">Select session</option>
                    <option value="morning">🌅 Morning (5:30am – 8:00am)</option>
                    <option value="midday">☀️ Midday (8:00am – 3:30pm)</option>
                    <option value="evening">🌙 Evening (3:30pm – 9:00pm)</option>
                </select>
                @error('session_slot')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">Mark Attendance</button>
        </form>
    </div>

    <div class="bfh-section-title">Today's attendance ({{ $todayAttendance->count() }})</div>

    @if($todayAttendance->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:24px">
            <p style="color:#555;font-size:13px">No attendance recorded today yet.</p>
        </div>
    @else
        @foreach($todayAttendance as $record)
            <div class="bfh-card" style="display:flex;align-items:center;gap:14px;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:13px;font-weight:700;flex-shrink:0">
                    {{ strtoupper(substr($record->client->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <p style="color:#fff;font-size:14px;font-weight:500">{{ $record->client->name }}</p>
                    <p style="color:#555;font-size:12px;margin-top:2px">{{ \Carbon\Carbon::parse($record->attended_at)->format('h:i A') }}</p>
                </div>
                <div style="text-align:right">
                    <span class="bfh-badge active" style="text-transform:capitalize">{{ $record->session_slot }}</span>
                    <p style="font-size:10px;margin-top:4px;{{ $record->marked_by === 'client' ? 'color:#4caf50' : 'color:#FF6B00' }}">
                        {{ $record->marked_by === 'client' ? '✓ Self check-in' : '✓ Trainer marked' }}
                    </p>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        const searchInput = document.getElementById('client-search-input');
        const resultsBox = document.getElementById('client-results');
        const hiddenInput = document.getElementById('selected-client-id');
        const options = document.querySelectorAll('.client-option');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            hiddenInput.value = '';

            if (query.length === 0) {
                resultsBox.style.display = 'none';
                return;
            }

            let anyVisible = false;
            options.forEach(opt => {
                if (opt.dataset.search.includes(query)) {
                    opt.style.display = 'block';
                    anyVisible = true;
                } else {
                    opt.style.display = 'none';
                }
            });

            resultsBox.style.display = anyVisible ? 'block' : 'none';
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) resultsBox.style.display = 'block';
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#client-results') && e.target !== searchInput) {
                resultsBox.style.display = 'none';
            }
        });

        options.forEach(opt => {
            opt.addEventListener('click', function() {
                searchInput.value = this.dataset.name;
                hiddenInput.value = this.dataset.id;
                resultsBox.style.display = 'none';
            });
        });
    </script>
</x-becky-layout>