<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Send <span style="color:#FF6B00">Message</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Push to all clients or a specific person</p>
    </div>

    <div class="bfh-card">
        <form method="POST" action="{{ route('admin.announcements.send') }}" id="announcement-form">
            @csrf

            {{-- Recipient selector --}}
            <div class="bfh-form-group">
                <label class="bfh-form-label">Send to</label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:4px">
                    <label style="cursor:pointer">
                        <input type="radio" name="recipient" value="all" checked
                            onchange="toggleRecipient(this.value)" style="display:none" id="radio-all">
                        <div id="card-all" class="bfh-card orange-border" style="margin-bottom:0;text-align:center;padding:12px;transition:border-color 0.2s">
                            <p style="font-size:20px;margin-bottom:4px">📢</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">All Clients</p>
                            <p style="color:#555;font-size:11px;margin-top:2px">{{ $clients->count() }} with notifications</p>
                        </div>
                    </label>
                    <label style="cursor:pointer">
                        <input type="radio" name="recipient" value="specific"
                            onchange="toggleRecipient(this.value)" style="display:none" id="radio-specific">
                        <div id="card-specific" class="bfh-card" style="margin-bottom:0;text-align:center;padding:12px;border-color:#2e2e2e;transition:border-color 0.2s">
                            <p style="font-size:20px;margin-bottom:4px">👤</p>
                            <p style="color:#fff;font-size:13px;font-weight:600">Specific Client</p>
                            <p style="color:#555;font-size:11px;margin-top:2px">Choose one person</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Client selector (hidden by default) --}}
            <div id="client-selector" style="display:none" class="bfh-form-group">
                <label class="bfh-form-label">Select client</label>
                <select name="client_id" class="bfh-select">
                    <option value="">Choose a client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }} — {{ $client->phone }}</option>
                    @endforeach
                </select>
                @error('client_id')<p class="bfh-error">{{ $message }}</p>@enderror
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

    <script>
        function toggleRecipient(value) {
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
    </script>

</x-becky-layout>