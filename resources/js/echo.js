import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// // //window.Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',  // Use 'pusher' instead of 'reverb'
    key: import.meta.env.VITE_PUSHER_APP_KEY,  // Use the correct Pusher key variable
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,  // Use the Pusher cluster
    forceTLS: false,  // Force TLS if needed
    //forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',  // Force TLS if needed
   
});
var channel = window.Echo.channel('notifications');
channel.listen('.SystemNotificationEvent', function(data) {
  alert(JSON.stringify(data));
  console.log('from syetem',data);
});
var channel2 = window.Echo.channel('notifications');
channel2.listen('.new-notification', function(data) {
    const notificationElement = document.createElement('div');
            notificationElement.classList.add('bg-blue-500', 'text-white', 'p-2', 'mb-2', 'rounded');
            notificationElement.innerText = data.message;
            document.getElementById('notifications-list').appendChild(notificationElement);
  alert(JSON.stringify(data));
  
});
// var channel1 = window.Echo.channel('my-channel');
// channel1.listen('.my-event', function(data) {
//   alert(JSON.stringify(data));
  
// });
// console.log('window.Echo');
// // window.Pusher.logToConsole = true;
// console.log(window.Echo.channel('notifications'));
// window.Echo.channel('notifications')
//     .subscribed(() => {
//         console.log('Successfully subscribed to notifications channel');
//     })
//     .listen('.SystemNotificationEvent', function(event)  {
//         console.log('New notification received:', event);
//         const notificationElement = document.createElement('div');
//         notificationElement.classList.add('bg-blue-500', 'text-white', 'p-2', 'mb-2', 'rounded');
//         notificationElement.innerText = event.notification.message;
//         document.getElementById('notifications-list').appendChild(notificationElement);
//     });
// window.Echo.channel('newnotifications').listen('.OrderPlaced', (event) => {
//         console.log('New notification received:', event);
//         const notificationElement = document.createElement('div');
//         notificationElement.classList.add('bg-blue-500', 'text-white', 'p-2', 'mb-2', 'rounded');
//         notificationElement.innerText = event.notification.message;
//         document.getElementById('notifications-list').appendChild(notificationElement);
//     });
// window.Echo.channel('notifications')
//     .subscribed(() => {
//         console.log('Successfully subscribed to notifications channel');
//     })
//     .listen('SystemNotificationEvent', (event) => {
//         console.log('New notification received:', event);
//         const notificationElement = document.createElement('div');
//         notificationElement.classList.add('bg-blue-500', 'text-white', 'p-2', 'mb-2', 'rounded');
//         notificationElement.innerText = event.notification.message;
//         document.getElementById('notifications-list').appendChild(notificationElement);
//     });
