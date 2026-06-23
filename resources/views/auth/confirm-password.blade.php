<x-guest-layout>
    <div style="max-width:400px;margin:60px auto;padding:32px 24px;background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px">

        <div style="text-align:center;margin-bottom:24px">
            <p style="color:#FF6B00;font-size:18px;font-weight:800;letter-spacing:2px;text-transform:uppercase">Becky</p>
            <p style="color:#777;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin-top:2px">Fitness Hub</p>
        </div>

        <p style="color:#888;font-size:13px;line-height:1.6;margin-bottom:20px">
            This is a secure area. Please confirm your password before continuing.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Password</label>
                <input type="password" name="password" required autocomplete="current-password" class="bfh-input">
                @error('password')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="bfh-btn">Confirm</button>
        </form>

    </div>
</x-guest-layout>