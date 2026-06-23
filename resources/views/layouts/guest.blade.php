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
            body{background:#141414;color:#fff;font-family:'Figtree',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
            .bfh-card{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:14px;padding:16px}
            .bfh-form-group{margin-bottom:16px}
            .bfh-form-label{color:#888;font-size:12px;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;display:block}
            .bfh-input{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:10px;padding:13px 14px;width:100%;color:#fff;font-size:14px;outline:none;transition:border-color 0.2s;font-family:'Figtree',sans-serif}
            .bfh-input:focus{border-color:#FF6B00}
            .bfh-input::placeholder{color:#555}
            .bfh-btn{background:#FF6B00;color:#fff;border:none;border-radius:12px;padding:14px;width:100%;font-size:14px;font-weight:700;letter-spacing:1px;text-transform:uppercase;cursor:pointer;display:block;text-align:center;text-decoration:none;transition:background 0.2s}
            .bfh-btn:hover{background:#e05f00;color:#fff}
            .bfh-error{color:#ff4444;font-size:12px;margin-top:4px}
            .bfh-select{background:#1e1e1e;border:0.5px solid #2e2e2e;border-radius:10px;padding:13px 14px;width:100%;color:#fff;font-size:14px;outline:none;appearance:none;font-family:'Figtree',sans-serif}
        </style>
    </head>
    <body>
        <div style="width:100%;max-width:420px">
            {{ $slot }}
        </div>
    </body>
</html>