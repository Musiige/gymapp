importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyAIRlCLDFjobM8BI1FONn3xYIfHFAkDJYA",
    authDomain: "gymapp-e29f0.firebaseapp.com",
    projectId: "gymapp-e29f0",
    storageBucket: "gymapp-e29f0.firebasestorage.app",
    messagingSenderId: "782521955218",
    appId: "1:782521955218:web:702207a35c9f68d676c74b"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/icon.png'
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});