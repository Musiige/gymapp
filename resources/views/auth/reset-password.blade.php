<x-guest-layout>
    <div style="max-width:400px;margin:60px auto;padding:32px 24px;background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px">

        <div style="text-align:center;margin-bottom:24px">
            <p style="color:#FF6B00;font-size:18px;font-weight:800;letter-spacing:2px;text-transform:uppercase">Becky</p>
            <p style="color:#777;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin-top:2px">Fitness Hub</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="bfh-form-group">
                <label class="bfh-form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="bfh-input">
                @error('email')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">New Password</label>
                <input type="password" name="password" required autocomplete="new-password" class="bfh-input">
                @error('password')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password" class="bfh-input">
                @error('password_confirmation')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bfh-btn" style="margin-top:8px">Reset Password</button>
        </form>

    </div>
</x-guest-layout>