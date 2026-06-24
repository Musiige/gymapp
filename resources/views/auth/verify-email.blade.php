<x-guest-layout>
    <div style="max-width:400px;margin:60px auto;padding:32px 24px;background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px">

        <div style="text-align:center;margin-bottom:24px">
            <p style="color:#FF6B00;font-size:18px;font-weight:800;letter-spacing:2px;text-transform:uppercase">Becky</p>
            <p style="color:#777;font-size:10px;letter-spacing:3px;text-transform:uppercase;margin-top:2px">Fitness Hub</p>
        </div>

        <p style="color:#888;font-size:13px;line-height:1.6;margin-bottom:20px">
            Thanks for signing up! Please verify your email address by clicking the link we sent you. If you didn't receive it, we can send another.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div style="background:#0a1a0a;border:0.5px solid #1a3a1a;border-radius:10px;padding:12px 16px;margin-bottom:16px">
                <p style="color:#4ade80;font-size:13px">A new verification link has been sent to your email.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:16px">
            @csrf
            <button type="submit" class="bfh-btn">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#555;font-size:12px;cursor:pointer;text-decoration:underline;width:100%;text-align:center">
                Log Out
            </button>
        </form>

    </div>
</x-guest-layout>