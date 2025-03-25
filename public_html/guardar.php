<?php
session_start(); // Iniciar sesión

$file = "users.json";

// Obtener datos enviados desde JavaScript o formulario
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

    // Encriptar la contraseña antes de guardarla
    $data["pass"] = password_hash($data["pass"], PASSWORD_DEFAULT);

    // Agregar usuario
    $users[] = $data;

    // Guardar en users.json
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

    // Guardar sesión y cookie
    $_SESSION["usuario"] = $data["user"];
    setcookie("usuario", $data["user"], time() + (90 * 24 * 60 * 60), "/");

    // Redirigir a profile.php
    header("Location: profile.php");
    exit();
} else {
    echo "Error al guardar usuario";
}
?>
