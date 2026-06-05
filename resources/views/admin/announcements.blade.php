<x-becky-layout>

    <div style="margin-bottom:24px">
        <h2 style="color:#fff;font-size:22px;font-weight:800">Send <span style="color:#FF6B00">Announcement</span></h2>
        <p style="color:#777;font-size:13px;margin-top:4px">Push a message to all clients instantly</p>
    </div>

    <div class="bfh-card">
        <form method="POST" action="{{ route('admin.announcements.send') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    placeholder="e.g. Weekend Special Offer"
                    class="bfh-input">
                @error('title')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-form-group" style="margin-bottom:0">
                <label class="bfh-form-label">Message</label>
                <textarea name="message" rows="5"
                    placeholder="Type your announcement here..."
                    class="bfh-input" style="resize:none">{{ old('message') }}</textarea>
                @error('message')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <div class="bfh-divider"></div>
            <button type="submit" class="bfh-btn">📢 Send to All Clients</button>
        </form>
    </div>

</x-becky-layout>