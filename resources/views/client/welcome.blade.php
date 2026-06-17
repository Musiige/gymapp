<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome — Becky Fitness Hub</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .container {
            max-width: 420px;
            width: 100%;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-main {
            color: #FF6B00;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .logo-sub {
            color: #555;
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-text h1 {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.3;
        }

        .welcome-text h1 span {
            color: #FF6B00;
        }

        .welcome-text p {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
            line-height: 1.6;
        }

        /* Steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 40px;
            position: relative;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 0;
            position: relative;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 19px;
            top: 52px;
            width: 2px;
            height: calc(100% - 32px);
            background: #222;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #1e1e1e;
            border: 2px solid #FF6B00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FF6B00;
            font-size: 16px;
            font-weight: 800;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .step-content {
            padding-top: 8px;
            flex: 1;
        }

        .step-content h3 {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }

        .step-content p {
            color: #666;
            font-size: 13px;
            margin-top: 4px;
            line-height: 1.5;
        }

        .step-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        /* Progress dots */
        .dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 32px;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #222;
            cursor: pointer;
            transition: background 0.2s;
        }

        .dot.active {
            background: #FF6B00;
            width: 24px;
            border-radius: 4px;
        }

        /* Slides */
        .slide { display: none; }
        .slide.active { display: block; }

        /* Button */
        .btn {
            background: #FF6B00;
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 16px;
            width: 100%;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
            font-family: 'Figtree', sans-serif;
        }

        .btn:hover { background: #e05f00; }

        .btn-skip {
            background: none;
            border: none;
            color: #444;
            font-size: 13px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            margin-top: 14px;
            font-family: 'Figtree', sans-serif;
        }

        .btn-skip:hover { color: #666; }

        .greeting {
            color: #FF6B00;
            font-size: 13px;
            text-align: center;
            margin-bottom: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="logo">
        <div class="logo-main">Becky</div>
        <div class="logo-sub">Fitness Hub</div>
    </div>

    {{-- Slide 1 — Welcome --}}
    <div class="slide active" id="slide-1">
        <div class="welcome-text">
            <p class="greeting">👋 Hey {{ explode(' ', Auth::user()->name)[0] }}</p>
            <h1>Welcome to <span>Becky Fitness Hub</span></h1>
            <p>Your personal fitness journey starts here. Let us show you around in 3 quick steps.</p>
        </div>

        <div style="text-align:center;font-size:80px;margin-bottom:32px">🏋️</div>

        <div class="dots">
            <div class="dot active" id="dot-1"></div>
            <div class="dot" id="dot-2"></div>
            <div class="dot" id="dot-3"></div>
        </div>

        <button class="btn" onclick="goToSlide(2)">Get Started →</button>
        <form method="POST" action="{{ route('client.welcome.done') }}">
            @csrf
            <button type="submit" class="btn-skip">Skip intro</button>
        </form>
    </div>

    {{-- Slide 2 — How it works --}}
    <div class="slide" id="slide-2">
        <div class="welcome-text">
            <h1>Here is <span>how it works</span></h1>
            <p>Everything you need is right in this app.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <div class="step-icon">📦</div>
                    <h3>Choose a package</h3>
                    <p>Pick the membership plan that fits your schedule — daily, weekly, or monthly.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <div class="step-icon">💳</div>
                    <h3>Make your payment</h3>
                    <p>Pay via MTN MoMo, Airtel Money, or cash at the front desk.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <div class="step-icon">✊</div>
                    <h3>Check in daily</h3>
                    <p>Scan the QR code or tap Check In on your dashboard when you arrive.</p>
                </div>
            </div>
        </div>

        <div class="dots">
            <div class="dot" id="dot-1b"></div>
            <div class="dot active" id="dot-2b"></div>
            <div class="dot" id="dot-3b"></div>
        </div>

        <button class="btn" onclick="goToSlide(3)">Next →</button>
        <form method="POST" action="{{ route('client.welcome.done') }}">
            @csrf
            <button type="submit" class="btn-skip">Skip intro</button>
        </form>
    </div>

    {{-- Slide 3 — Ready --}}
    <div class="slide" id="slide-3">
        <div class="welcome-text">
            <h1>You are all <span>set! 🎉</span></h1>
            <p>Your dashboard is ready. Enable notifications so you never miss a payment reminder or announcement from us.</p>
        </div>

        <div style="background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:16px;padding:24px;margin-bottom:32px">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                <span style="font-size:28px">🔔</span>
                <div>
                    <p style="color:#fff;font-size:14px;font-weight:700">Enable notifications</p>
                    <p style="color:#666;font-size:12px;margin-top:2px">Get payment reminders and gym updates</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px">
                <span style="font-size:28px">💪</span>
                <div>
                    <p style="color:#fff;font-size:14px;font-weight:700">View your workouts</p>
                    <p style="color:#666;font-size:12px;margin-top:2px">Your trainer will assign workouts to you</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:14px">
                <span style="font-size:28px">📊</span>
                <div>
                    <p style="color:#fff;font-size:14px;font-weight:700">Track your progress</p>
                    <p style="color:#666;font-size:12px;margin-top:2px">See your attendance and membership status</p>
                </div>
            </div>
        </div>

        <div class="dots">
            <div class="dot" id="dot-1c"></div>
            <div class="dot" id="dot-2c"></div>
            <div class="dot active" id="dot-3c"></div>
        </div>

        <form method="POST" action="{{ route('client.welcome.done') }}">
            @csrf
            <button type="submit" class="btn">Go to my Dashboard 🏋️</button>
        </form>
    </div>

</div>

<script>
    function goToSlide(num) {
        document.querySelectorAll('.slide').forEach(s => s.classList.remove('active'));
        document.getElementById('slide-' + num).classList.add('active');
        window.scrollTo(0, 0);
    }
</script>

</body>
</html>