/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({
    apiKey: "AIzaSyBJGjRapdLzCQzEHaryirAB6z9AxHv1E2E",
    authDomain: "fimihub-rider.firebaseapp.com",
    projectId: "fimihub-rider",
    storageBucket: "fimihub-rider.appspot.com",
    messagingSenderId: "325134169313",
    appId: "1:325134169313:web:27177c09890124edac33a0",
    measurementId: "G-QS48Z6L0JH"
});

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/asset/customer/assets/images/logo.png",
        url: "Background Message url",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );


});