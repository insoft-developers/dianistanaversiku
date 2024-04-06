importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyD4_3G9UpqpWg-Xk7On-PwzaY9bU-wiMl8",
    authDomain: "my-dian-istana.firebaseapp.com",
    projectId: "my-dian-istana",
    storageBucket: "my-dian-istana.appspot.com",
    messagingSenderId: "832093630984",
    appId: "1:832093630984:web:a0d969f7afe0a1a212d7bd"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});