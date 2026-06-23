<x-guest-layout>
    <div style="max-width:400px;margin:60px auto;padding:32px 24px;background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px">

        <div style="text-align:center;margin-bottom:28px">
            <p style="color:#FF6B00;font-size:18px;font-weight:800;letter-spacing:2px;text-transform:uppercase">Becky</p>
            <p style="color:#777;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin-top:2px">Fitness Hub</p>
        </div>

        @if (session('status'))
            <div style="background:#0a1a0a;border:0.5px solid #1a3a1a;border-radius:10px;padding:12px 16px;margin-bottom:16px">
                <p style="color:#4ade80;font-size:13px">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="bfh-form-group">
                <label class="bfh-form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="bfh-input">
                @error('email')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div class="bfh-form-group">
                <label class="bfh-form-label">Password</label>
                <input type="password" name="password" required autocomplete="current-password" class="bfh-input">
                @error('password')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" name="remember" style="width:14px;height:14px;accent-color:#FF6B00">
                    <span style="color:#888;font-size:12px">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color:#FF6B00;font-size:12px;text-decoration:none">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="bfh-btn">Log In</button>
        </form>

        @if (Route::has('register'))
            <p style="text-align:center;color:#555;font-size:12px;margin-top:20px">
                New here?
                <a href="{{ route('register') }}" style="color:#FF6B00;text-decoration:none">Create an account</a>
            </p>
        @endif

    </div>
</x-guest-layout>