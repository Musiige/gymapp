<x-guest-layout>
    <div style="max-width:400px;margin:60px auto;padding:32px 24px;background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px">

        <div style="text-align:center;margin-bottom:24px">
            <p style="color:#FF6B00;font-size:18px;font-weight:800;letter-spacing:2px;text-transform:uppercase">Becky</p>
            <p style="color:#777;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin-top:2px">Fitness Hub</p>
        </div>

        <p style="color:#888;font-size:13px;line-height:1.6;margin-bottom:20px">
            Forgot your password? No problem. Enter your email and we'll send you a password reset link.
        </p>

        @if (session('status'))
            <div style="background:#0a1a0a;border:0.5px solid #1a3a1a;border-radius:10px;padding:12px 16px;margin-bottom:16px">
                <p style="color:#4ade80;font-size:13px">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="bfh-form-group">
                <label class="bfh-form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="bfh-input">
                @error('email')<p class="bfh-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="bfh-btn">Email Password Reset Link</button>
        </form>

    </div>
</x-guest-layout>