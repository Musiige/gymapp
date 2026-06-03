<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    
    @auth
    @if(Auth::user()->role === 'client')
    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js';
        import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js';

        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            messagingSenderId: "{{ env('FCM_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        async function requestPermissionAndSaveToken() {
            try {
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    const token = await getToken(messaging, {
                        vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}"
                    });
                    if (token) {
                        await fetch('/client/fcm-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ token: token })
                        });
                    }
                }
            } catch (error) {
                console.log('Notification permission error:', error);
            }
        }

        requestPermissionAndSaveToken();

        onMessage(messaging, (payload) => {
            const title = payload.notification.title;
            const body  = payload.notification.body;

            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4f46e5;
                color: white;
                padding: 16px 20px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                z-index: 9999;
                max-width: 320px;
                font-family: sans-serif;
            `;
            toast.innerHTML = `
                <p style="font-weight:600;margin:0 0 4px;">🏋️ ${title}</p>
                <p style="font-size:13px;margin:0;opacity:0.9;">${body}</p>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 6000);
        });
    </script>
    @endif
@endauth

    </body>
</html>
