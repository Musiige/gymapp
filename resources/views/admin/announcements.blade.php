<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Send <span style="color:#FF6B00">Message</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Push to all clients or select specific ones</p>
    </div>

    <div class="bfh-card">
        <form method="POST" action="{{ route('admin.announcements.send') }}" id="announcement-form">
            @csrf

            {{-- Recipient selector --}}
            <div class="bfh-form-group">
                <label class="bfh-form-label">Send to</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:4px">
                    <label style="cursor:pointer" onclick="toggleRecipient('all')">
                        <input type="radio" name="recipient" value="all" checked style="display:none" id="radio-all">
                        <div id="card-all" class="bfh-card orange-border" style="margin-bottom:0;text-align:center;padding:12px">
                            <p style="font-size:20px;margin-bottom:4px">📢</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">Everyone</p>
                            <p style="color:#555;font-size:11px;margin-top:2px">{{ $clients->count() }} clients & trainers</p>
                        </div>
                    </label>
                    <label style="cursor:pointer" onclick="toggleRecipient('specific')">
                        <input type="radio" name="recipient" value="specific" style="display:none" id="radio-specific">
                        <div id="card-specific" class="bfh-card" style="margin-bottom:0;text-align:center;padding:12px;border-color:#2e2e2e">
                            <p style="font-size:20px;margin-bottom:4px">👥</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">Specific People</p>
                            <p style="color:#555;font-size:11px;margin-top:2px">Choose one or more</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Client multi-select with search --}}
            <div id="client-selector" style="display:none" class="bfh-form-group">
                <label class="bfh-form-label">Select clients</label>

                {{-- Search box --}}
                <input type="text" id="client-search"
                    placeholder="Search clients..."
                    class="bfh-input" style="margin-bottom:10px"
                    oninput="filterClients(this.value)">

                {{-- Client checkboxes --}}
               <div id="client-list" style="background:#0a0a0a;border-radius:10px;padding:12px;max-height:300px;overflow-y:auto">
                    @foreach($clients as $client)
                        <label id="client-item-{{ $client->id }}"
                            style="display:flex;align-items:center;gap:10px;padding:8px;border-radius:8px;cursor:pointer;margin-bottom:4px">
                            <input type="checkbox" name="client_ids[]" value="{{ $client->id }}"
                                style="width:16px;height:16px;accent-color:#FF6B00">
                            <div style="flex:1">
                                <p style="color:#fff;font-size:13px;font-weight:500">{{ $client->name }}</p>
                                <p style="color:#555;font-size:11px">{{ $client->phone }}</p>
                            </div>
                            <span class="bfh-badge {{ $client->role === 'trainer' ? 'trainer' : 'client' }}" style="font-size:9px;padding:2px 8px">
                                {{ $client->role }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="button" onclick="selectAll()" style="background:none;border:0.5px solid #FF6B00;color:#FF6B00;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer">
                        Select all
                    </button>
                    <button type="button" onclick="clearAll()" style="background:none;border:0.5px solid #555;color:#555;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer">
                        Clear
                    </button>
                </div>
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    placeholder="e.g. Payment Reminder"
                    class="bfh-input">
                @error('title')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Message</label>
                <textarea name="message" rows="4"
                    placeholder="Type your message here..."
                    class="bfh-input" style="resize:none">{{ old('message') }}</textarea>
                @error('message')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">📨 Send Message</button>
        </form>
    </div>

    {{-- Sent announcements --}}
    <div class="bfh-section-title" style="margin-top:24px">Sent announcements ({{ $sentAnnouncements->total() }})</div>
    @if($sentAnnouncements->isEmpty())
        <div class="bfh-card" style="text-align:center;padding:20px">
            <p style="color:#555;font-size:13px">No announcements sent yet.</p>
        </div>
    @else
       @foreach($sentAnnouncements as $announcement)
            <div class="bfh-card" style="margin-bottom:10px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px">
                    <div style="flex:1">
                        <p style="color:#fff;font-size:14px;font-weight:600">{{ $announcement->title }}</p>
                        <p style="color:#888;font-size:12px;margin-top:4px;line-height:1.5">{{ $announcement->message }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $announcement->id) }}"
                        onsubmit="return confirm('Delete this announcement? It will be removed from all client inboxes.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#3a1a1a;border:0.5px solid #ff4444;color:#ff4444;padding:6px 10px;border-radius:8px;font-size:11px;cursor:pointer;white-space:nowrap">
                            Delete
                        </button>
                    </form>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:0.5px solid #222">
                    <span class="bfh-badge {{ $announcement->recipient_type === 'all' ? 'active' : 'changed' }}">
                        {{ $announcement->recipient_type === 'all' ? 'All clients' : count($announcement->recipient_ids ?? []) . ' specific' }}
                    </span>
                    <p style="color:#555;font-size:11px">{{ $announcement->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        @endforeach

        @if($sentAnnouncements->hasPages())
            <div style="display:flex;justify-content:center;gap:8px;margin-top:16px;flex-wrap:wrap">
                @if($sentAnnouncements->onFirstPage())
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">← Prev</span>
                @else
                    <a href="{{ $sentAnnouncements->previousPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">← Prev</a>
                @endif
                <span style="padding:8px 14px;background:#FF6B00;color:#fff;border-radius:8px;font-size:12px">{{ $sentAnnouncements->currentPage() }} / {{ $sentAnnouncements->lastPage() }}</span>
                @if($sentAnnouncements->hasMorePages())
                    <a href="{{ $sentAnnouncements->nextPageUrl() }}" style="padding:8px 14px;background:#1e1e1e;color:#888;border-radius:8px;font-size:12px;text-decoration:none">Next →</a>
                @else
                    <span style="padding:8px 14px;background:#1e1e1e;color:#444;border-radius:8px;font-size:12px">Next →</span>
                @endif
            </div>
        @endif
    @endif

    <script>
        function toggleRecipient(value) {
            document.getElementById('radio-' + value).checked = true;
            const selector = document.getElementById('client-selector');
            const cardAll = document.getElementById('card-all');
            const cardSpecific = document.getElementById('card-specific');
            if (value === 'specific') {
                selector.style.display = 'block';
                cardAll.style.borderColor = '#2e2e2e';
                cardSpecific.style.borderColor = '#FF6B00';
            } else {
                selector.style.display = 'none';
                cardAll.style.borderColor = '#FF6B00';
                cardSpecific.style.borderColor = '#2e2e2e';
            }
        }

        function filterClients(query) {
            const items = document.querySelectorAll('[id^="client-item-"]');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query.toLowerCase()) ? 'flex' : 'none';
            });
        }

        function selectAll() {
            document.querySelectorAll('input[name="client_ids[]"]').forEach(cb => cb.checked = true);
        }

        function clearAll() {
            document.querySelectorAll('input[name="client_ids[]"]').forEach(cb => cb.checked = false);
        }
    </script>

</x-becky-layout>