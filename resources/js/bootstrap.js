import axios from 'axios';
// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// import Echo from 'laravel-echo'
// import Pusher from 'pusher-js'

// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: 'de58ca8cf42f57b8f9f5',
//   cluster: 'ap2',
//   forceTLS: false
// });

// var channel = window.Echo.channel('my-channel');
// channel.listen('.my-event', function(data) {
//   alert(JSON.stringify(data));
// });
// window.Pusher = Pusher;
// //window.Pusher.logToConsole = true;

// window.Echo = new Echo({
//     broadcaster: 'pusher',  // Use 'pusher' instead of 'reverb'
//     key: import.meta.env.VITE_PUSHER_APP_KEY,  // Use the correct Pusher key variable
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,  // Use the Pusher cluster
    
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,  // Default WebSocket port
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,  // Default secure WebSocket port
//     forceTLS: false,  // Force TLS if needed
//     //forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',  // Force TLS if needed
//     enabledTransports: ['ws', 'wss'],  // WebSocket transports
// });
// /**
//  * Echo exposes an expressive API for subscribing to channels and listening
//  * for events that are broadcast by Laravel. Echo and event broadcasting
//  * allow your team to quickly build robust real-time web applications.
//  */

//
 import './echo';
