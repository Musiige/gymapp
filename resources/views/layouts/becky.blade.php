<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Becky Fitness Hub</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{background:#141414;color:#fff;font-family:'Figtree',sans-serif;min-height:100vh}
        .bfh-topbar{background:#0a0a0a;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;border-bottom:0.5px solid #2a2a2a;position:sticky;top:0;z-index:100}
        .bfh-logo-main{color:#FF6B00;font-size:15px;font-weight:800;letter-spacing:2px;text-transform:uppercase;line-height:1}
        .bfh-logo-sub{color:#777;font-size:8px;letter-spacing:3px;text-transform:uppercase;margin-top:2px}
        .bfh-avatar{width:36px;height:36px;background:#1e1e1e;border:0.5px solid #3a3a3a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:12px;font-weight:700;flex-shrink:0}
        .bfh-content{padding:24px 20px 110px;max-width:100%;margin:0 auto;width:100%}
        @media(min-width:640px){.bfh-content{max-width:600px;padding:32px 32px 110px}}
        @media(min-width:1024px){.bfh-content{max-width:780px;padding:40px 40px 110px}}
        @media(min-width:1280px){.bfh-content{max-width:960px;padding:40px 40px 110px}}
        .bfh-nav{position:fixed;bottom:0;left:0;right:0;width:100%;background:#0a0a0a;border-top:0.5px solid #1e1e1e;display:flex;justify-content:space-around;padding:10px 0 16px;z-index:100}
        .bfh-nav a{display:flex;flex-direction:column;align-items:center;gap:4px;color:#444;font-size:9px;text-transform:uppercase;letter-spacing:0.5px;text-decoration:none;transition:color 0.2s}
        .bfh-nav a.active{color:#FF6B00}
        .bfh-nav a svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round}
        .bfh-card{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:14px;padding:16px;margin-bottom:14px}
        .bfh-card.orange-border{border-color:#FF6B00}
        .bfh-card.grey-border{border-color:#3a3a3a}
        .bfh-section-title{color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:10px;margin-top:4px}
        .bfh-label{color:#666;font-size:11px;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:10px}
        .bfh-stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
        .bfh-stat{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:12px;padding:14px}
        .bfh-stat-label{color:#777;font-size:11px;text-transform:uppercase;letter-spacing:1px}
        .bfh-stat-value{color:#FF6B00;font-size:22px;font-weight:700;margin-top:4px}
        .bfh-stat-value.grey{color:#aaa}
        .bfh-stat-sub{color:#555;font-size:11px;margin-top:2px}
        .bfh-btn{background:#FF6B00;color:#fff;border:none;border-radius:12px;padding:14px;width:100%;font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase;cursor:pointer;display:block;text-align:center;text-decoration:none;transition:background 0.2s}
        .bfh-btn:hover{background:#e05f00;color:#fff}
        .bfh-btn.outline{background:transparent;border:0.5px solid #FF6B00;color:#FF6B00}
        .bfh-btn.outline:hover{background:#FF6B00;color:#fff}
        .bfh-btn.grey-btn{background:#1e1e1e;border:0.5px solid #2e2e2e;color:#888}
        .bfh-btn.danger{background:#3a1a1a;border:0.5px solid #5a2a2a;color:#ff4444}
        .bfh-btn.sm{padding:10px 14px;font-size:12px}
        .bfh-input{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:10px;padding:13px 14px;width:100%;color:#fff;font-size:14px;outline:none;transition:border-color 0.2s;font-family:'Figtree',sans-serif}
        .bfh-input:focus{border-color:#FF6B00}
        .bfh-input::placeholder{color:#555}
        .bfh-input:disabled{opacity:0.4;cursor:not-allowed}
        .bfh-select{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:10px;padding:13px 14px;width:100%;color:#fff;font-size:14px;outline:none;appearance:none;font-family:'Figtree',sans-serif}
        .bfh-select:focus{border-color:#FF6B00}
        .bfh-form-group{margin-bottom:16px}
        .bfh-form-label{color:#888;font-size:12px;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;display:block}
        .bfh-error{color:#ff4444;font-size:12px;margin-top:4px}
        .bfh-badge{display:inline-block;font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:1px}
        .bfh-badge.active{background:#FF6B00;color:#fff}
        .bfh-badge.pending{background:transparent;color:#FF6B00;border:0.5px solid #FF6B00}
        .bfh-badge.expired{background:#2a2a2a;color:#666}
        .bfh-badge.changed{background:#1a1a3a;color:#4a9eff}
        .bfh-badge.paid{background:#1a3a1a;color:#4caf50}
        .bfh-badge.half-paid{background:#3a2a0a;color:#FF6B00}
        .bfh-badge.unpaid{background:#3a1a1a;color:#ff4444}
        .bfh-badge.client{background:#1e1e1e;color:#aaa;border:0.5px solid #3a3a3a}
        .bfh-badge.trainer{background:#1a2a3a;color:#4a9eff}
        .bfh-badge.admin{background:#2a1a3a;color:#9a4aff}
        .bfh-progress-bar{background:#2a2a2a;border-radius:4px;height:4px;margin-top:10px}
        .bfh-progress-fill{background:#FF6B00;height:4px;border-radius:4px}
        .bfh-divider{height:0.5px;background:#222;margin:16px 0}
        .bfh-row{display:flex;justify-content:space-between;align-items:center}
        .bfh-row-start{display:flex;align-items:center;gap:12px}
        .bfh-alert-success{background:#1a3a1a;border:0.5px solid #2a5a2a;color:#4caf50;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px}
        .bfh-alert-error{background:#3a1a1a;border:0.5px solid #5a2a2a;color:#ff4444;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:16px}
        .bfh-table{width:100%;border-collapse:collapse;font-size:13px}
        .bfh-table th{color:#666;font-size:10px;text-transform:uppercase;letter-spacing:1px;padding:8px 0;border-bottom:0.5px solid #222;text-align:left;font-weight:600}
        .bfh-table td{padding:10px 0;border-bottom:0.5px solid #1e1e1e;color:#ccc;vertical-align:middle}
        .bfh-table tr:last-child td{border-bottom:none}
        .bfh-icon-box{width:40px;height:40px;background:#2a2a2a;border:0.5px solid #3a3a3a;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#FF6B00;font-size:15px;font-weight:700;flex-shrink:0}
        @media(min-width:768px){.bfh-topbar{padding:14px 40px}.bfh-content{padding:32px 24px 110px}}

        /* Light mode */
        body.light{background:#f5f5f5;color:#111}
        body.light .bfh-topbar{background:#fff;border-bottom:0.5px solid #e0e0e0}
        body.light .bfh-logo-sub{color:#999}
        body.light .bfh-avatar{background:#f0f0f0;border-color:#e0e0e0}
        body.light .bfh-content{background:#f5f5f5}
        body.light .bfh-card{background:#fff;border-color:#e8e8e8}
        body.light .bfh-card.orange-border{border-color:#FF6B00}
        body.light .bfh-stat{background:#fff;border-color:#e8e8e8}
        body.light .bfh-stat-label{color:#999}
        body.light .bfh-stat-sub{color:#aaa}
        body.light .bfh-section-title{color:#999}
        body.light .bfh-nav{background:#fff;border-top:0.5px solid #e8e8e8}
        body.light .bfh-nav a{color:#bbb}
        body.light .bfh-nav a.active{color:#FF6B00}
        body.light .bfh-input{background:#f8f8f8;border-color:#e0e0e0;color:#111}
        body.light .bfh-input::placeholder{color:#bbb}
        body.light .bfh-select{background:#f8f8f8;border-color:#e0e0e0;color:#111}
        body.light .bfh-form-label{color:#999}
        body.light .bfh-icon-box{background:#f0f0f0;border-color:#e0e0e0;color:#FF6B00}
        body.light .bfh-progress-bar{background:#e8e8e8}
        body.light .bfh-divider{background:#e8e8e8}
        body.light .bfh-table th{color:#999;border-color:#e8e8e8}
        body.light .bfh-table td{border-color:#f0f0f0;color:#444}
        body.light .bfh-alert-success{background:#f0fff0;border-color:#c3e6c3;color:#2e7d32}
        body.light .bfh-alert-error{background:#fff0f0;border-color:#e6c3c3;color:#c62828}
        body.light .bfh-badge.expired{background:#f0f0f0;color:#999}
        body.light .bfh-btn.grey-btn{background:#f0f0f0;border-color:#e0e0e0;color:#666}
        body.light .bfh-btn.danger{background:#fff0f0;border-color:#ffcccc;color:#c62828}
        body.light .bfh-btn.outline{border-color:#FF6B00;color:#FF6B00}
        body.light .bfh-stat-value{color:#FF6B00}
        body.light p[style*="color:#fff"]{color:#111 !important}
        body.light p[style*="color:#aaa"]{color:#666 !important}
        body.light p[style*="color:#555"]{color:#888 !important}
        body.light p[style*="color:#777"]{color:#999 !important}
        body.light h2[style*="color:#fff"]{color:#111 !important}
        body.light .bfh-nav a{color:#ccc}
        body.light div[style*="background:#2a2a2a"]{background:#f0f0f0 !important}
        body.light div[style*="background:#1e1e1e"]{background:#fff !important}
        body.light div[style*="background:#3a1a0a"]{background:#fff3e0 !important;border-color:#FF6B00}
        body.light span[style*="background:#2a2a2a"]{background:#f0f0f0 !important;color:#666 !important}
    </style>
</head>
<body>

@auth
<div class="bfh-topbar">
    <div>
        <div class="bfh-logo-main">Becky</div>
        <div class="bfh-logo-sub">Fitness Hub</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <button onclick="toggleTheme()" id="theme-btn" style="background:none;border:0.5px solid #333;border-radius:20px;padding:5px 12px;color:#888;font-size:11px;cursor:pointer;font-family:'Figtree',sans-serif;letter-spacing:1px">
            🌙 Dark
        </button>
        <div class="bfh-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:#555;font-size:11px;cursor:pointer;text-transform:uppercase;letter-spacing:1px;font-family:'Figtree',sans-serif">Logout</button>
        </form>
    </div>
</div>
@endauth

@guest
<div class="bfh-topbar">
    <div>
        <div class="bfh-logo-main">Becky</div>
        <div class="bfh-logo-sub">Fitness Hub</div>
    </div>
</div>
@endguest

<div class="bfh-content">
    @if(session('success'))
        <div class="bfh-alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bfh-alert-error">✕ {{ session('error') }}</div>
    @endif

    {{ $slot }}
</div>

@auth
@php $role = Auth::user()->role; @endphp

@if($role === 'client')
<nav class="bfh-nav">
    <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('client.subscription') }}" class="{{ request()->routeIs('client.subscription') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        <span>Plans</span>
    </a>
    @php
        $activeSubForNav = \App\Models\Subscription::where('user_id', Auth::id())
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->first();
    @endphp
    <a href="{{ $activeSubForNav ? route('client.payment', $activeSubForNav->id) : route('client.subscription') }}"
        class="{{ request()->routeIs('client.payment*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
        <span>Pay</span>
    </a>
    <a href="{{ route('client.profile') }}" class="{{ request()->routeIs('client.profile') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Profile</span>
    </a>
</nav>

@elseif($role === 'trainer')
<nav class="bfh-nav">
    <a href="{{ route('trainer.dashboard') }}" class="{{ request()->routeIs('trainer.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('trainer.clients') }}" class="{{ request()->routeIs('trainer.clients') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Clients</span>
    </a>
    <a href="{{ route('trainer.attendance') }}" class="{{ request()->routeIs('trainer.attendance') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        <span>Attend</span>
    </a>
    <a href="{{ route('trainer.workouts') }}" class="{{ request()->routeIs('trainer.workouts') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        <span>Workouts</span>
    </a>
</nav>

@elseif($role === 'admin')
<nav class="bfh-nav">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.clients') }}" class="{{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Clients</span>
    </a>
    <a href="{{ route('admin.announcements') }}" class="{{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
        <span>Announce</span>
    </a>
</nav>

@endif
@endauth

@auth
@if(Auth::user()->role === 'client' && !Auth::user()->fcm_token)
<button id="notif-test-btn" style="position:fixed;bottom:80px;right:16px;background:#1e1e1e;color:#FF6B00;border:0.5px solid #FF6B00;padding:10px 16px;border-radius:50px;font-size:12px;font-weight:700;z-index:200;cursor:pointer;letter-spacing:1px;font-family:'Figtree',sans-serif">
    🔔 Enable Notifications
</button>
<script>
    async function loadFirebaseAndRequest() {
        try {
            const { initializeApp } = await import('https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js');
            const { getMessaging, getToken, onMessage } = await import('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js');
            const app = initializeApp({
                apiKey: "{{ env('FIREBASE_API_KEY') }}",
                projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
                messagingSenderId: "{{ env('FCM_SENDER_ID') }}",
                appId: "{{ env('FIREBASE_APP_ID') }}"
            });
            let messaging;
            if ('serviceWorker' in navigator) {
                await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                await navigator.serviceWorker.ready;
                messaging = getMessaging(app);
            } else { return; }
            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                const token = await getToken(messaging, { vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}" });
                if (token) {
                    await fetch('/client/fcm-token', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ token })
                    });
                    document.getElementById('notif-test-btn').style.display = 'none';
                }
            }
            onMessage(messaging, (payload) => {
                const toast = document.createElement('div');
                toast.style.cssText = 'position:fixed;top:20px;right:16px;left:16px;background:#1e1e1e;border:0.5px solid #FF6B00;color:#fff;padding:16px;border-radius:12px;z-index:9999;font-family:Figtree,sans-serif;max-width:400px;margin:0 auto';
                toast.innerHTML = '<p style="font-weight:700;margin:0 0 4px;color:#FF6B00">🔔 ' + payload.notification.title + '</p><p style="font-size:13px;margin:0;color:#aaa">' + payload.notification.body + '</p>';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 6000);
            });
        } catch(e) { console.log(e); }
    }
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('notif-test-btn');
        if (btn) btn.addEventListener('click', loadFirebaseAndRequest);
    });
</script>
@endif
@endauth

<script>
    function toggleTheme() {
        const body = document.body;
        const btn = document.getElementById('theme-btn');
        if (body.classList.contains('light')) {
            body.classList.remove('light');
            localStorage.setItem('theme', 'dark');
            btn.textContent = '🌙 Dark';
            btn.style.borderColor = '#333';
            btn.style.color = '#888';
        } else {
            body.classList.add('light');
            localStorage.setItem('theme', 'light');
            btn.textContent = '☀️ Light';
            btn.style.borderColor = '#e0e0e0';
            btn.style.color = '#666';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const saved = localStorage.getItem('theme');
        const btn = document.getElementById('theme-btn');
        if (saved === 'light') {
            document.body.classList.add('light');
            if (btn) {
                btn.textContent = '☀️ Light';
                btn.style.borderColor = '#e0e0e0';
                btn.style.color = '#666';
            }
        }
    });
</script>

</body>
</html>