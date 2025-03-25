<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprobar si el formulario ha sido enviado
    if (isset($_POST['nombre']) && isset($_POST['nombre_completo']) && isset($_POST['email']) && isset($_POST['telefono']) && isset($_POST['comentarios']) && isset($_POST['pass']) && isset($_POST['empresa'])) {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $nombre_completo = $_POST['nombre_completo'];
        $email = $_POST['email'];
        $telefono = $_POST['telefono'];
        $comentarios = $_POST['comentarios'];
        $contrasena = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Encriptar la contraseña
        $empresa = $_POST['empresa'];

        // Verificar si se sube una imagen de perfil
        $imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imagen = $_FILES['imagen']['name'];
            // Mover la imagen a la carpeta "img"
            move_uploaded_file($_FILES['imagen']['tmp_name'], 'img/' . $imagen);
        } else {
            $imagen = 'perfildefault.jpg'; // Imagen por defecto si no se sube ninguna
        }

        // Leer los usuarios existentes desde el archivo JSON
        $usuarios = json_decode(file_get_contents('usuarios.json'), true); // Obtener los usuarios registrados
        if (isset($usuarios[$nombre])) {
            echo '<span class="text-gray-700 dark:text-gray-400">Ya existe este usuario</span>';
        } else {
            // Añadir el nuevo usuario al archivo JSON utilizando el nombre como id
            $usuarios[$nombre] = [
                'id' => $nombre,  // Usar nombre como ID
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'comentarios' => $comentarios,
                'contrasena' => $contrasena,
                'empresa' => $empresa,
                'imagen' => $imagen
            ];
            file_put_contents('usuarios.json', json_encode($usuarios, JSON_PRETTY_PRINT)); // Guardar los usuarios actualizados

            // Establecer la sesión del usuario
            $_SESSION['usuario'] = [
                'id' => $nombre,  // Usar nombre como ID
                'nombre_completo' => $nombre_completo,
                'email' => $email,
                'telefono' => $telefono,
                'comentarios' => $comentarios,
                'empresa' => $empresa,
                'imagen' => $imagen
            ];

            // Redireccionar al índice (index) con los datos del usuario
            echo "<script>
            // Redirigir automáticamente a 'adw.php' al cargar la página
            window.location.href = 'adw.php?usuario=" . urlencode($nombre) . "';
            </script>";
            exit;
        }
    } else {
        echo '<span class="text-gray-700 dark:text-gray-400">Ingresa todos los datos requeridos</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body style="background-color: #f6cb4a;">
    <div class="container">
        <div style="background: url('https://i.pinimg.com/736x/f6/a2/ce/f6a2ce549c8d56fae41633f700553583.jpg') no-repeat center center;" class="imagen"></div>
        <div class="formulario">
            <h2>Registro de Usuario</h2>
 <form action="registro.php" method="POST" enctype="multipart/form-data">
    <input style="display: none;" placeholder="Tu nombre de usuario" type="text" id="user" name="nombre" readonly>

<script>
    const generados = new Set(); // Almacena los nombres generados

    function generarUsuario() {
        let usuario;
        do {
            usuario = generarAleatorio();
        } while (generados.has(usuario)); // Verifica que no se repita
        
        generados.add(usuario); // Guarda el nuevo usuario
        document.getElementById("user").value = usuario;
    }

    function generarAleatorio() {
        const letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        const numeros = "0123456789";

        let parteLetras = "";
        let parteNumeros = "";

        for (let i = 0; i < 4; i++) {
            parteLetras += letras[Math.floor(Math.random() * letras.length)];
        }

        for (let i = 0; i < 5; i++) {
            parteNumeros += numeros[Math.floor(Math.random() * numeros.length)];
        }

        return parteLetras + parteNumeros;
    }

    // Genera el usuario automáticamente al cargar la página
    window.onload = generarUsuario;
</script>


    <div class="input-container">
        <label for="nombre_completo">Nombre completo:</label>
        <input placeholder="Tu nombre completo" type="text" id="nombre_completo" name="nombre_completo" required>
        <span class="mensaje-error" id="error-nombre_completo"></span>
    </div>

    <div class="input-container">
        <label for="email">Correo Electrónico:</label>
        <input placeholder="Tu correo Electrónico" type="email" id="email" name="email">
        <div class="autocomplete-sugerencia" id="sugerenciaEmail" title="Agregar @gmail.com"></div>
        <span class="mensaje-error" id="error-email"></span>
    </div>
    

    <div class="input-container">
        <label for="telefono">Teléfono:</label>
        <input placeholder="Tu teléfono" type="text" id="telefono" name="telefono" required>
        <span class="mensaje-error" id="error-telefono"></span>
    </div>

    <div class="input-container">
        <label for="comentarios">Localidad:</label>
        <textarea placeholder="Ejemplo: Los Robles, Guadalajara, Jalisco, Mexico" id="comentarios" name="comentarios"></textarea>
        <span class="mensaje-error" id="error-comentarios"></span>
    </div>

    <div class="input-container">
        <label for="pass">Contraseña:</label>
        <input type="password" id="pass" name="pass" required>
        <span class="mensaje-error" id="error-pass"></span>
    </div>

    <div class="input-container">
        <label for="empresa">Empresa:</label>
        <input placeholder="Tu empresa" type="text" id="empresa" name="empresa" required>
        <span class="mensaje-error" id="error-empresa"></span>
    </div>


    <div class="input-container">
        <button type="submit">Registrar</button>
    </div>
    
    <center style="margin-top: 10px;"><a style="color:#f6cb4a;" href="login.php">¿Ya tienes cuenta? Login</a></center>
</form>




            <div id="catalogoRegistros" class="hidden"></div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="modalRegistro" class="modal hidden">
        <div class="modal-content">
            <h3>Registro agregado</h3>
            <div id="registroPreview"></div>
            <button id="aceptarRegistro">Aceptar</button>
        </div>
    </div>
<script src="./archivo.js"></script>
</body>
</html>