<?php
// Comprobar si el formulario ha sido enviado
if (isset($_POST['email']) && isset($_POST['contrasena'])) {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Comprobar si el usuario existe y si la contraseña es correcta
    $usuarios = json_decode(file_get_contents('usuarios.json'), true); // Obtener los usuarios registrados
    $usuarioEncontrado = null;
    
    // Buscar el usuario con el correo electrónico proporcionado
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email) {
            $usuarioEncontrado = $usuario;
            break;
        }
    }

    if ($usuarioEncontrado) {
        // Verificar la contraseña
        if (password_verify($contrasena, $usuarioEncontrado['contrasena'])) {
            // Obtener la lista de cuentas registradas desde la cookie
            $cuentas_registradas = $_COOKIE['cuentas_registradas'] ?? '';
            $cuentas_registradas = explode(',', $cuentas_registradas);
            $cuentas_registradas = array_filter($cuentas_registradas); // Eliminar elementos vacíos
            $cuentas_registradas = array_unique($cuentas_registradas); // Eliminar duplicados

            // Agregar el correo electrónico actual a la lista de cuentas registradas y guardarla en la cookie
            $cuentas_registradas[] = $email;
            $cuentas_registradas = array_unique($cuentas_registradas); // Eliminar duplicados
            setcookie('cuentas_registradas', implode(',', $cuentas_registradas), time() + (86400 * 30), '/'); // Cookie válida por 30 días

            // Iniciar sesión y redirigir a profile.php después de un inicio de sesión exitoso
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['nombre_completo'] = $usuarioEncontrado['nombre_completo'];  // Guardar nombre completo en la sesión
            $_SESSION['telefono'] = $usuarioEncontrado['telefono'];  // Guardar teléfono en la sesión
            $_SESSION['empresa'] = $usuarioEncontrado['empresa'];  // Guardar empresa en la sesión
            $_SESSION['imagen'] = $usuarioEncontrado['imagen'];  // Guardar imagen de perfil en la sesión
            header('Location: profile.php');
            exit;
        } else {
            $mensaje = 'Contraseña incorrecta';
        }
    } else {
        $mensaje = 'El correo electrónico no está registrado';
    }
} else {
    $mensaje = '';
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
        <div style="background: url('https://i.pinimg.com/736x/e4/ee/56/e4ee56f243786fef5ecda76915ac429e.jpg') no-repeat center center;" class="imagen"></div>
        <div class="formulario">
            <h2>Inicio de sesion</h2>
            <form method="POST">
                <div class="input-container">
                    <label for="email">Correo electrónico:</label>
                    <input type="email" name="email" id="email" placeholder="Tu correo electrónico" required>
                </div>

                <div class="input-container">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" name="contrasena" id="contrasena" placeholder="Mín 2 caracteres, máx 8" minlength="2" maxlength="8" required>
                </div>

                <button type="submit">Iniciar sesión</button>
                <center style="margin-top: 10px;"><a style="color:#f6cb4a;" href="registro.php">¿Aun no tienes cuenta? Registrate</a></center>
            </form>

            
        </div>
    </div>

    <script src="./archivo.js"></script>
</body>
</html>
