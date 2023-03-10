importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyD6Z9raBZp8htftiMIYw_2-BVp4YCEU_Lg",
    projectId: "pensioncmp",
    messagingSenderId: "488692969467",
    appId: "1:488692969467:web:8491e8a88093a84b67655e",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function ({
    data: { title, body, icon },
}) {
    return self.registration.showNotification(title, { body, icon });
});
