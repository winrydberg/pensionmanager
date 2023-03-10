$(document).ready(function() {
// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyD6Z9raBZp8htftiMIYw_2-BVp4YCEU_Lg",
    authDomain: "pensioncmp.firebaseapp.com",
    projectId: "pensioncmp",
    storageBucket: "pensioncmp.appspot.com",
    messagingSenderId: "488692969467",
    appId: "1:488692969467:web:8491e8a88093a84b67655e",
    measurementId: "G-R1Y73RG9DS",
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

function initFirebaseMessagingRegistration() {
    messaging
        .requestPermission()
        .then(function () {
            return messaging.getToken();
        })
        .then(function (token) {
            
            localStorage.setItem('fcm_token', token);
            $.ajax({
                url: "/update-fcm-token",
                method: "POST",
                data: {
                    fcm_token: token,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },

                success: function (res) {
                    console.log(res);
                },
                error: function (error) {
                    console.log(error);
                },
            });
        })
        .catch(function (err) {
            console.log(`Token Error :: ${err}`);
        });
}

    if (localStorage.getItem("fcm_token") == null){
        initFirebaseMessagingRegistration();
    } 

    messaging.onMessage(function (data) {
        console.log(data)
        // new Notification(title, { body });
        const notification = new Notification(data.notification.title, {
            body: data.notification.body,
            // icon: img,
        });

        notification.addEventListener("click", function () {
            window.location.href = data.data.url;
        });
    });
});
