// Solicitar permiso para mostrar notificaciones
if ('Notification' in window && 'serviceWorker' in navigator) {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            console.log('Permiso para notificaciones concedido.');

            // Registrar el Service Worker
            navigator.serviceWorker.register('/sw.js').then(reg => {
                console.log('Service Worker registrado:', reg);
            }).catch(err => {
                console.error('Error al registrar el Service Worker:', err);
            });
        } else {
            console.error('Permiso para notificaciones denegado.');
        }
    });
}

// Función para cargar mensajes y mostrar notificaciones
async function loadMessages() {
    try {
        const response = await fetch('/inbox/mensajes.json'); // Actualiza la URL si es necesario
        const messages = await response.json();
        
        messages.forEach(msg => {
            if (msg.to === currentUser && !msg.views) {
                // Mostrar notificación
                showNotification(msg);
            }
        });
    } catch (err) {
        console.error('Error al cargar mensajes:', err);
    }
}

// Función para mostrar notificaciones
function showNotification(msg) {
    const title = `Nuevo mensaje de ${msg.from}`;
    const options = {
        body: msg.text.length > 50 ? msg.text.slice(0, 50) + '...' : msg.text,
        icon: `https://dilivel.com/perfiles/${users[msg.from].imagen}`,
        data: {
            url: `/inbox/mensaje/${msg.id}` // URL para redirigir al mensaje
        }
    };

    navigator.serviceWorker.ready.then(sw => {
        sw.showNotification(title, options);
    });
}

// Registrar evento para manejar clics en la notificación
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});

// Llama a la función para cargar mensajes periódicamente
setInterval(loadMessages, 5000);