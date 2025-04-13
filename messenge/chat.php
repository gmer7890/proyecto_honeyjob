<?php
$filename = 'chat.json'; // Archivo centralizado de chat
$usuariosFile = 'usuarios.json';
$chatsDir = 'chats/';

$encryption_key = 'your-encryption-key'; // Cambia esto por tu clave secreta
$iv_length = openssl_cipher_iv_length('aes-256-cbc'); // Largo del vector de inicialización (IV)
$method = 'aes-256-cbc'; // Algoritmo de cifrado

// Función para cifrar un mensaje
function encryptMessage($message, $key) {
    global $iv_length, $method;
    $iv = openssl_random_pseudo_bytes($iv_length); // Generar un IV aleatorio
    $ciphertext = openssl_encrypt($message, $method, $key, 0, $iv); // Cifrar el mensaje
    return base64_encode($ciphertext . '::' . $iv); // Guardar el mensaje cifrado junto con el IV
}

// Función para descifrar un mensaje
function decryptMessage($encrypted_message, $key) {
    global $iv_length, $method;
    list($ciphertext, $iv) = explode('::', base64_decode($encrypted_message), 2); // Extraer IV y mensaje
    return openssl_decrypt($ciphertext, $method, $key, 0, $iv); // Descifrar el mensaje
}

// Función para generar un ID único de 36 caracteres
function generateUniqueId() {
    return bin2hex(random_bytes(18)); // Generar un ID de 36 caracteres
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'get') {
        $currentUser = $_GET['user'];  // ID del usuario que está consultando los mensajes
        $messages = json_decode(file_get_contents($filename), true) ?? [];
        $filteredMessages = array_filter($messages, function($message) use ($currentUser) {
            // Filtrar mensajes que coincidan con el usuario actual o el destinatario
            return $message['from'] === $currentUser || $message['to'] === $currentUser;
        });

        // Descifrar los mensajes antes de enviarlos al cliente
        $decryptedMessages = array_map(function($message) use ($encryption_key) {
            $message['text'] = decryptMessage($message['text'], $encryption_key);
            return $message;
        }, array_values($filteredMessages));

        echo json_encode($decryptedMessages); // Mostrar solo los mensajes relevantes
        exit;
    }

    if ($_GET['action'] === 'get-users') {
        if (file_exists($usuariosFile)) {
            $users = json_decode(file_get_contents($usuariosFile), true);
            echo json_encode($users);
        } else {
            echo json_encode([]); // Si no hay usuarios, responder con un array vacío
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'send') {
    $currentUser = $_GET['user'];  // ID del usuario que envía el mensaje
    $data = json_decode(file_get_contents('php://input'), true);

    // Cifrar el mensaje antes de guardarlo
    $encryptedText = encryptMessage($data['text'], $encryption_key);

    // Generar un ID único para el mensaje
    $messageId = generateUniqueId(); // ID único de 36 caracteres

    // Obtener el contenido de chat.json y agregar el nuevo mensaje
    $messages = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];
    $message = [
        'id' => $messageId, // Agregar el ID único al mensaje
        'from' => $currentUser,
        'to' => $data['to'], // ID del usuario al que se le envía el mensaje
        'text' => $encryptedText, // Guardar el mensaje cifrado
        'timestamp' => date('Y-m-d H:i:s')
    ];
    $messages[] = $message;

    // Guardar el mensaje cifrado en el archivo chat.json
    file_put_contents($filename, json_encode($messages, JSON_PRETTY_PRINT));

    echo json_encode(['status' => 'success']);
    exit;
}
?>
