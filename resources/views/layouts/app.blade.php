<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        @auth
        @if(Auth::user()->role === 'client')

        <button id="notif-test-btn" style="
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            z-index: 9999;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        ">🔔 Enable Notifications</button>

        <script>
            async function loadFirebaseAndRequest() {
                try {
                    alert('Step 1: Loading Firebase...');

                    const { initializeApp } = await import('https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js');
                    const { getMessaging, getToken, onMessage } = await import('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging.js');

                    alert('Step 2: Firebase loaded');

                    const firebaseConfig = {
                        apiKey: "{{ env('FIREBASE_API_KEY') }}",
                        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
                        messagingSenderId: "{{ env('FCM_SENDER_ID') }}",
                        appId: "{{ env('FIREBASE_APP_ID') }}"
                    };

                    alert('Step 3: Config ready. Project = ' + firebaseConfig.projectId);

                    const app = initializeApp(firebaseConfig);

                    alert('Step 4: Registering service worker...');

                    let messaging;
                    if ('serviceWorker' in navigator) {
                        const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
alert('Step 5: Service worker registered, waiting for activation...');

await navigator.serviceWorker.ready;
alert('Step 6: Service worker is active and ready');

messaging = getMessaging(app);
alert('Step 7: Messaging initialized');
                    } else {
                        alert('ERROR: Service workers not supported on this browser');
                        return;
                    }

                    alert('Step 7: Requesting permission...');

                    const permission = await Notification.requestPermission();
                    alert('Step 8: Permission = ' + permission);

                    if (permission === 'granted') {
                        alert('Step 9: Getting token...');
                        const token = await getToken(messaging, {
                            vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}"
                        });
                        alert('Step 10: Token = ' + (token ? token.substring(0, 30) + '...' : 'NULL'));

                        if (token) {
                            const response = await fetch('/client/fcm-token', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ token: token })
                            });
                            alert('Step 11: Saved. Status = ' + response.status);
                            document.getElementById('notif-test-btn').style.display = 'none';
                            alert('✅ Notifications enabled successfully!');
                        }
                    } else {
                        alert('Permission denied: ' + permission);
                    }

                    onMessage(messaging, (payload) => {
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
                            <p style="font-weight:600;margin:0 0 4px;">🏋️ ${payload.notification.title}</p>
                            <p style="font-size:13px;margin:0;opacity:0.9;">${payload.notification.body}</p>
                        `;
                        document.body.appendChild(toast);
                        setTimeout(() => toast.remove(), 6000);
                    });

                } catch (error) {
                    alert('ERROR at some step: ' + error.message);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                var btn = document.getElementById('notif-test-btn');
                if (btn) {
                    btn.addEventListener('click', loadFirebaseAndRequest);
                }
            });
        </script>

        @endif
        @endauth

    </body>
</html>