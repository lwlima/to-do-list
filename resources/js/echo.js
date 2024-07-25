// src/echo.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configurar o Echo
window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    encrypted: true,
});

export default echo;