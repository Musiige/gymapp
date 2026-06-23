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
        input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(1);cursor:pointer;opacity:0.8}
input[type="date"]::-webkit-calendar-picker-indicator:hover{opacity:1}
body.light input[type="date"]::-webkit-calendar-picker-indicator{filter:invert(0);opacity:0.6}
body.light input[type="date"]::-webkit-calendar-picker-indicator:hover{opacity:1}
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
body.light{
    --bfh-bg: #f0f0f0;
    --bfh-surface: #ffffff;
    --bfh-card: #ffffff;
    --bfh-card-border: #e0e0e0;
    --bfh-text-primary: #111111;
    --bfh-text-secondary: #555555;
    --bfh-text-muted: #888888;
    --bfh-topbar: #ffffff;
    --bfh-nav: #ffffff;
    --bfh-input-bg: #f8f8f8;
    --bfh-input-border: #d0d0d0;
    --bfh-divider: #e0e0e0;
    background:#f0f0f0 !important;
    color:#111 !important;
}
body.light .bfh-topbar{background:#fff;border-bottom:1px solid #e0e0e0}
body.light .bfh-nav{background:#fff;border-top:1px solid #e0e0e0}
body.light .bfh-nav a{color:#aaa}
body.light .bfh-nav a.active{color:#FF6B00}
body.light .bfh-nav a span{color:inherit}
body.light .bfh-card{background:#fff;border-color:#e0e0e0}
body.light .bfh-card.orange-border{border-color:#FF6B00}
body.light .bfh-stat{background:#fff;border-color:#e0e0e0}
body.light .bfh-stat-label{color:#888}
body.light .bfh-stat-value{color:#FF6B00}
body.light .bfh-stat-sub{color:#999}
body.light .bfh-stat-value.grey{color:#666}
body.light .bfh-section-title{color:#888;font-weight:700}
body.light .bfh-input{background:#fff;border-color:#d0d0d0;color:#111}
body.light .bfh-input::placeholder{color:#bbb}
body.light .bfh-input:focus{border-color:#FF6B00}
body.light .bfh-select{background:#fff;border-color:#d0d0d0;color:#111}
body.light .bfh-form-label{color:#666;font-weight:700}
body.light .bfh-icon-box{background:#f0f0f0;border-color:#e0e0e0;color:#FF6B00}
body.light .bfh-progress-bar{background:#e0e0e0}
body.light .bfh-divider{background:#e0e0e0}
body.light .bfh-table th{color:#666;border-color:#e0e0e0;font-weight:700}
body.light .bfh-table td{border-color:#f0f0f0;color:#333}
body.light .bfh-alert-success{background:#f0fff4;border-color:#b7ebc8;color:#1a6b35}
body.light .bfh-alert-error{background:#fff0f0;border-color:#f5c6c6;color:#b91c1c}
body.light .bfh-badge.expired{background:#f0f0f0;color:#888}
body.light .bfh-badge.changed{background:#e8f0ff;color:#2563eb}
body.light .bfh-btn.grey-btn{background:#f0f0f0;border-color:#d0d0d0;color:#555}
body.light .bfh-btn.danger{background:#fff0f0;border-color:#fca5a5;color:#b91c1c}
body.light .bfh-btn.outline{border-color:#FF6B00;color:#FF6B00}

/* Light mode — override all inline dark colors */
body.light *[style*="color:#fff"]{color:#111 !important}
body.light *[style*="color:#ffffff"]{color:#111 !important}
body.light *[style*="color:#aaa"]{color:#555 !important}
body.light *[style*="color:#888"]{color:#666 !important}
body.light *[style*="color:#777"]{color:#666 !important}
body.light *[style*="color:#666"]{color:#555 !important}
body.light *[style*="color:#555"]{color:#444 !important}
body.light *[style*="color:#444"]{color:#333 !important}
body.light *[style*="color:#333"]{color:#222 !important}
body.light *[style*="background:#1e1e1e"]{background:#fff !important}
body.light *[style*="background:#2a2a2a"]{background:#f0f0f0 !important}
body.light *[style*="background:#0a0a0a"]{background:#f8f8f8 !important}
body.light *[style*="background:#141414"]{background:#f0f0f0 !important}
body.light *[style*="background:#111"]{background:#f5f5f5 !important}
body.light *[style*="background:#222"]{background:#e8e8e8 !important}
body.light *[style*="border-color:#222"]{border-color:#e0e0e0 !important}
body.light *[style*="border-top:0.5px solid #222"]{border-top:0.5px solid #e0e0e0 !important}
body.light *[style*="border-bottom:0.5px solid #222"]{border-bottom:0.5px solid #e0e0e0 !important}
body.light *[style*="color:#4caf50"]{color:#1a6b35 !important}
body.light *[style*="color:#ff4444"]{color:#b91c1c !important}
body.light *[style*="color:#4a9eff"]{color:#1d4ed8 !important}
body.light *[style*="background:#3a1a1a"]{background:#fff0f0 !important}
body.light *[style*="background:#1a3a1a"]{background:#f0fff4 !important}
body.light *[style*="background:#1a2a3a"]{background:#eff6ff !important}
body.light *[style*="background:#3a2a0a"]{background:#fffbeb !important}
body.light *[style*="background:#3a1a0a"]{background:#fff7ed !important}
body.light *[style*="background:#2a2a2a;border-radius:50%"]{background:#e8e8e8 !important}
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
@if(!Auth::user()->is_corporate)
<a href="{{ $activeSubForNav ? route('client.payment', $activeSubForNav->id) : route('client.subscription') }}"
    class="{{ request()->routeIs('client.payment*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
    <span>Pay</span>
</a>
@endif
   @php
    $unreadCount = Auth::check() && Auth::user()->role === 'client'
        ? \App\Http\Controllers\Client\InboxController::unreadCount()
        : 0;
@endphp
<a href="{{ route('client.inbox') }}" class="{{ request()->routeIs('client.inbox') ? 'active' : '' }}"
    style="position:relative">
    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    @if($unreadCount > 0)
        <span style="position:absolute;top:-4px;right:-2px;background:#FF6B00;color:#fff;font-size:9px;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
    @endif
    <span>Inbox</span>
</a>
    <a href="{{ route('client.profile') }}" class="{{ request()->routeIs('client.profile') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Profile</span>
    </a>
</nav>

@elseif($role === 'trainer')
@php
    $trainerUnreadCount = \App\Http\Controllers\Trainer\InboxController::unreadCount();
@endphp
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
    <a href="{{ route('trainer.inbox') }}" class="{{ request()->routeIs('trainer.inbox') ? 'active' : '' }}" style="position:relative">
        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        @if($trainerUnreadCount > 0)
            <span style="position:absolute;top:-4px;right:-2px;background:#FF6B00;color:#fff;font-size:9px;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1">
                {{ $trainerUnreadCount > 9 ? '9+' : $trainerUnreadCount }}
            </span>
        @endif
        <span>Inbox</span>
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
    <a href="{{ route('admin.staff') }}" class="{{ request()->routeIs('admin.staff*') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    <span>Staff</span>
</a>
</nav>

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