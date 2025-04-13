<?php
session_start();

// Verificar si el usuario ha iniciado sesión o si se ha almacenado una cookie de sesión
if (!isset($_SESSION['email']) && !isset($_COOKIE['email'])) {
    header('Location: sign_up.php'); // Redirigir al registro si no hay sesión ni cookie
    exit;
}

// Leer los datos de usuarios del archivo JSON
$usuarios = json_decode(file_get_contents('../usuarios.json'), true);

// Determinar el identificador del usuario
$email = $_SESSION['email'] ?? $_COOKIE['email'];

// Restaurar la sesión si es necesario
$_SESSION['email'] = $email;

// Verificar si el usuario actual existe en el archivo JSON
$usuario = array_filter($usuarios, function($user) use ($email) {
    return $user['email'] === $email;
});

if (empty($usuario)) {
    header('Location: sign_up.php'); // Redirigir si no existe en la base de datos
    exit;
}

// Extraer los datos del usuario
$usuario = reset($usuario);
$nombre = $usuario['nombre_completo'];
$telefono = $usuario['telefono'];
$empresa = $usuario['empresa'];
$imagen = $usuario['imagen'] ?? 'd/perfildefault.jpg';

// Establecer una cookie de sesión para recordar al usuario (3 años)
if (!isset($_COOKIE['email']) || $_COOKIE['email'] !== $email) {
    setcookie('email', $email, time() + (86400 * 365 * 3), '/'); 
}

$d = $nombre;

$ggh2 = $usuario['imagen'];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, minimum-scale=1">
    <link rel="stylesheet" type="text/css" href="dark.css" />
    <link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="rgba(255, 255, 255, 0.1)">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

<script>
        document.addEventListener('DOMContentLoaded', () => {
            // Obtener el botón de alternar y el modo actual
            const toggleButton = document.getElementById('toggleDarkMode');
            const currentMode = localStorage.getItem('mode') || 'light';

            // Aplicar el modo actual al cargar la página
            applyMode(currentMode);

            // Escuchar el evento de clic para alternar el modo
            toggleButton.addEventListener('click', () => {
                const newMode = document.body.classList.contains('light-mode') ? 'dark' : 'light';
                applyMode(newMode);
                localStorage.setItem('mode', newMode);
            });

            function applyMode(mode) {
                // Limpiar clases previas
                document.body.classList.remove('light-mode', 'dark-mode');
                toggleButton.classList.remove('light-mode', 'dark-mode');
                
                document.body.classList.add(`${mode}-mode`);
                toggleButton.classList.add(`${mode}-mode`);
                
                // Actualizar clases en otros elementos
                document.querySelectorAll('.custom-navigation-bar, .darklight, .colors, .tows, .towse, .towsi, .towsis, .sis, .sos, .borde')
                    .forEach(element => {
                        element.classList.remove('light-mode', 'dark-mode');
                        element.classList.add(`${mode}-mode`);
                    });

                // Cambiar el meta theme-color para Android
                const metaThemeColor = document.querySelector('meta[name="theme-color"]');
                if (metaThemeColor) {
                    const darkColor = '<?php
// Obtén la URL actual
$currentUrl = $_SERVER["REQUEST_URI"];

// Verifica si la URL contiene el parámetro "chat"
if (strpos($currentUrl, '?chat=') !== false) {
    echo "rgb(26 26 26)";
} else {
    echo "black";
}
?>'; // Color oscuro
                    const lightColor = 'rgba(255, 255, 255, 0.1)'; // Color claro
                    metaThemeColor.setAttribute('content', mode === 'dark' ? darkColor : lightColor);
                }

                // Cambiar la barra de estado para Safari iOS (si aplica)
                const appleStatusBar = document.querySelector('meta[name="apple-mobile-web-app-status-bar-style"]');
                if (appleStatusBar) {
                    appleStatusBar.setAttribute('content', mode === 'dark' ? 'black-translucent' : 'default');
                }
            }
        });
    </script>
    
    <title>Inbox (<?php echo $d ?>)</title>
<style>
/* Estilo global para scrollbars en navegadores WebKit (Chrome, Edge, Safari, Opera) */
::-webkit-scrollbar {
    width: 2px; /* Ancho de la barra vertical */
    height: 2px; /* Ancho de la barra horizontal */
}

::-webkit-scrollbar-thumb {
    background-color: transparent; /* Pulgar transparente */
    border: none; /* Sin bordes */
}

::-webkit-scrollbar-track {
    background-color: transparent; /* Fondo del track transparente */
}

body.dark-mode a {
            color: white; /* Cambia el color del enlace en modo oscuro */
        }

        body.light-mode a {
            color: black; /* Cambia el color del enlace en modo claro */
        }
* {
    font-family: "Poppins", sans-serif;
    text-decoration: none;
    -webkit-tap-highlight-color: transparent;
    scrollbar-width: thin; /* Scrollbar más delgada */
    scrollbar-color: transparent transparent; /* Totalmente transparente */
}
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            max-height: 100vh;
            margin: 0;
        }
        
        #chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 0px solid #ccc;
        }
        #chat-header {
            display: flex;
            align-items: center;
            padding: 0px;
        }
        
        #chat-header2 {
            display: flex;
            align-items: center;
            padding: 0px;
        }
        
        #chat-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #chat-header svg {
            cursor: pointer;
            margin-right: 10px;
        }
        
        #chat-header2 img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        #chat-header2 svg {
            cursor: pointer;
            margin-right: 10px;
        }
        
        #chat-box {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }
        #message-form {
            display: flex;
            gap: 10px;
            padding: 0px;
            position
            border-top: 1px solid #ccc;
            align-items: center;
        }
        
        #users-list {
            flex-direction: column;
            width: 330px;
            padding: 0px;
            margin-left: 60px;
            border-right: solid 1px #ccc6;
            overflow-y: auto;
        }
        #users-list ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }
        #users-list li {
            padding: 10px;
            cursor: pointer;
            border-bottom: 0px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: ;
        }
       
        .new-message {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
        }
        
      /* Solo para pantallas móviles */
@media (max-width: 768px) {
    #users-list ul {
            padding: 0;
            margin: 14px 0px 0px 0px;
            list-style-type: none;
        }
    
    #chat-header {
            display: flex;
            align-items: center;
            padding: 0px;
            border-bottom: 1px solid #9d9a9a3d;
            position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
        }
        
        #chat-header2 {
            display: flex;
            align-items: center;
            padding: 0px;
            position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 99;
    border-bottom: 0.5px solid #736f6f1f;
        }
        
        #chat-box {
            flex: 1;
            padding: 10px 0px;
            overflow-y: auto;
            margin-top: 56px;
            margin-bottom: 36px;
        }
        
    #message-form {
            display: flex;
            gap: 10px;
            padding: 0px;
            border-top: 0px solid #ccc;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            align-items: center;
        }
    #users-list {
            flex-direction: column;
            min-width: 100%;
            max-width: 100%;
            padding: 0px;
            margin: 0px;
            overflow-y: auto;
        }
    
  .view {
    display: none;
    height: 100vh;
    width: 100%;
  }

  #chat-list.visible {
    display: block;
  }

  #chat-view.visible {
    display: block;
  }
  
  
}

.gh{
            height: 100vh;
            overflow-y: auto;
        }
                .messager {
            display: flex;
            align-items: center;
            border-radius: 30px;
            padding: 10px 10px;
            flex: 1;
        }

        .icon {
            margin-right: 10px;
        }

        .icon svg {
            width: 21px;
            height: 21px;
        }

        .message-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            font-size: 16px;
        }

        .send-button {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .send-button svg {
            width: 24px;
            height: 24px;
        }

        .message-input:not(:placeholder-shown) ~ .send-button {
            display: block;
        }
        .as-home {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 60px;
      background-color: transparent;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 10px;
      gap: 20px;
      border-right: solid 1px #ccc6;
      z-index: 99;
    }
    @media (max-width: 768px) {
        .as-home {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 60px;
      background-color: #000;
      display: none;
      flex-direction: column;
      align-items: center;
      padding-top: 10px;
      gap: 20px;
    }
    } 

    .as-home a {
      color: #fff;
      text-decoration: none;
      font-size: 24px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 40px;
      height: 40px;
    }

    .as-home img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-bottom: 35px;
    }

    .menu-icon {
      font-size: 28px;
    }

    .spacer {
      flex-grow: 1;
    }
    
    #searchInput {
    width: 85%;
    padding: 12px;
    font-size: 16px;
    border-radius: 10px;
    border: 1px solid #dddddd0f;
    margin-bottom: 10px;
    background-color: #c7baba12;
    color: gray;
}

/* Estilo cuando el input está en foco */
#searchInput:focus {
    border: none; /* Elimina el borde */
    outline: none; /* Elimina el contorno que aparece en algunos navegadores */
}

@media (max-width: 768px) {
    #searchInput {
        width: 85%;
        padding: 15px;
        font-size: 16px;
        border-radius: 30px;
        border: 0px solid #ddd;
        margin-bottom: 10px;
        margin-top: 70px;
        background-color: #c7baba12;
        color: gray;
    }

    /* Estilo cuando el input está en foco en dispositivos móviles */
    #searchInput:focus {
        border: none; /* Elimina el borde */
        outline: none; /* Elimina el contorno */
    }
}

        #userList {
            display: flex;
            flex-direction: column;
            gap: 0px;
        }
        .usuario {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 0px;
            transition: background-color 0.3s;
        }
        .usuario:hover {
            background-color: #c7baba12;
        }
        .usuario img {
            border-radius: 50%;
            object-fit: cover;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .usuario .name {
            font-size: 16px;
            font-weight: bold;
        }
        .usuario .info {
            flex-grow: 1;
        }
        .oculto {
        display: none;
    }
    
    .tabs_bottom {
  display: none;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  justify-content: space-between;
  margin-top: 4px;
  border-bottom: 1px gray solid;
  margin-bottom: 0px;
  z-index: 999;
  padding: 0px;}
  

@media screen and (max-width: 736px) {
.tabs_bottom {
  display: flex;
  position: fixed;
  bottom: 0;
  width: 100%;
  justify-content: space-between;
  margin-top: 4px;
  border-top: solid 1px #cccccc59;
  margin-bottom: -9px;
  padding: 0px;
}}


.tabs {
  display: flex;
  justify-content: space-between;
  margin-top:0px;
  margin-bottom:-2.8px;
  background-color: transparent;
}

.tab {
  flex: 1;
  padding: 0px;
  text-align: center;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 16px;
  margin: auto;
  cursor: pointer;
  border: 0px solid #ccc;
}

.tab2 {
  flex: 1;
  padding: 9px;
  text-align: center;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 16px;
  margin: auto;
  cursor: pointer;
  border: 0px solid #ccc;
}

@media (max-width: 600px) {
  .tab {
      flex: 1;
      padding: 7px;
      text-align: center;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 16px;
      margin: auto;
      cursor: pointer;
      border: 0px solid #ccc;
  }
}
.chatop {
    border-bottom: 1px solid #ccc6;
}


  /* Modal styles */
  #myModal {
    display: none;
    position: fixed;
    z-index: 1000;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.7);
  }

  .modal-content {
    position: absolute;
    bottom: -100%; /* Inicia fuera de la pantalla */
    left: 50%;
    transform: translateX(-50%);
    border-radius: 10px;
    padding: 46px 20px 10px 20px;
    width: 80%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    background: white;
    animation: slideUp 0.5s ease forwards; /* Animación para que suba */
  }

  .modal-content img {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin: 5px 0;
  }

  .close-btn {
    position: absolute;
    top: 10px;
    right: 17px;
    color: white;
    border: none;
    border-radius: 50%;
    padding: 2.6px 8px;
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
  }

  .collage {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 5px;
    max-height: 450px;
    overflow-y: auto;
  }

  .collage::-webkit-scrollbar {
    width: 8px;
  }

  .collage::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }

  /* Animación para que el modal suba */
  @keyframes slideUp {
    from {
      bottom: -100%; /* Comienza fuera de la pantalla */
    }
    to {
      bottom: 10%; /* Su posición final */
    }
  }

  /* Mobile-specific styles */
  @media (max-width: 768px) {
    .modal-content {
      width: 95%;
      height: 50%; /* Media pantalla */
      bottom: 0; /* Comienza en la parte inferior */
      border-radius: 10px 10px 0 0;
      padding: 46px 20px 10px 20px;
      box-shadow: 0px -2px 15px rgba(0, 0, 0, 0.2);
      animation: slideUpMobile 0.5s ease forwards;
    }

    @keyframes slideUpMobile {
      from {
        bottom: -50%; /* Comienza fuera de la pantalla */
      }
      to {
        bottom: 0; /* Su posición final */
      }
    }
  }
  
  .loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 1;
    transition: opacity 1s ease-out; /* Transición para el desvanecimiento */
}

.loader-container.hidden {
    opacity: 0; /* Oculto con opacidad */
    pointer-events: none; /* Desactiva interacciones cuando esté oculto */
}

.spinner {
      width: 30px;
      height: 30px;
      border: 2px solid rgb(26 155 239);
      border-top: 2px solid rgb(26 155 239);
      border-right: 2px solid transparent;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.message222 {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 16px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            border-radius: 10px;
            font-size: 16px;
            z-index: 9999;
            text-align: center;
        }
    </style>

</head>
<body>
    <div id="loader" class="loader-container tows">
    <div class="spinner"></div></div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const loader = document.getElementById("loader");
        let timeout; // Variable para el timeout

        // Establece un máximo de 2 segundos para ocultar el loader
        timeout = setTimeout(() => {
            loader.classList.add("hidden");
        }, 2000);

        // Escucha el evento load de la ventana
        window.addEventListener("load", () => {
            clearTimeout(timeout); // Cancela el timeout si la página carga antes de los 2 segundos
            loader.classList.add("hidden");
        });
    });
</script>
    <aside class="as-home">
    <a><span class="menu-icon"><svg width="28" height="28" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
  <path d="m23.021,10.173c-1.242-1.269-5.38-2.089-9.841-2.165,1.07-.458,1.82-1.52,1.82-2.758,0-1.004-.498-1.888-1.256-2.433.448-.438,1.141-.817,2.256-.817V0c-1.954,0-3.225.811-4,1.754-.775-.943-2.046-1.754-4-1.754v2c1.112,0,1.805.379,2.253.82-.756.545-1.253,1.427-1.253,2.43,0,1.238.75,2.299,1.82,2.758-4.461.076-8.599.896-9.841,2.165-1.305,1.333-1.305,3.494,0,4.828s3.421,1.333,4.726,0c.619-.632,1.603-1.763,2.633-3h7.326c1.029,1.237,2.014,2.368,2.633,3,1.305,1.333,3.421,1.333,4.726,0s1.305-3.495,0-4.828Zm-13.059-.173c.518-.652.996-1.272,1.403-1.82.205.044.417.07.635.07s.43-.026.635-.07c.406.548.885,1.168,1.403,1.82h-4.075Zm-2.407,4h8.888c-.07.666-.21,1.336-.39,2H7.946c-.181-.664-.32-1.334-.39-2Zm1.07,4h6.748c-.282.701-.592,1.375-.912,2h-4.924c-.32-.625-.63-1.299-.912-2Zm2.035,4h2.677c-.75,1.224-1.339,2-1.339,2,0,0-.589-.776-1.339-2Z"/>
</svg></span></a>
<br>
    <a href=""><span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" height="23" width="23"><path d="m13.5,12c0,.828-.672,1.5-1.5,1.5s-1.5-.672-1.5-1.5.672-1.5,1.5-1.5,1.5.672,1.5,1.5Zm3.5-1.5c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Zm-10,0c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Zm17,1.84v6.66c0,2.757-2.243,5-5,5h-5.917C6.082,24,.47,19.208.03,12.854c-.241-3.476,1.027-6.878,3.479-9.333S9.363-.206,12.836.029c6.26.425,11.164,5.833,11.164,12.312Zm-2,0c0-5.431-4.084-9.962-9.299-10.316-.229-.016-.458-.023-.686-.023-2.656,0-5.209,1.048-7.091,2.933-2.044,2.046-3.101,4.883-2.899,7.782.373,5.38,5.024,9.285,11.059,9.285h5.917c1.654,0,3-1.346,3-3v-6.66Z"/></svg></span></a>
    
    <a href=""><span class="menu-icon"><svg class="tows" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="23" height="23"><path d="M22.555,13.662l-1.9-6.836A9.321,9.321,0,0,0,2.576,7.3L1.105,13.915A5,5,0,0,0,5.986,20H7.1a5,5,0,0,0,9.8,0h.838a5,5,0,0,0,4.818-6.338ZM12,22a3,3,0,0,1-2.816-2h5.632A3,3,0,0,1,12,22Zm8.126-5.185A2.977,2.977,0,0,1,17.737,18H5.986a3,3,0,0,1-2.928-3.651l1.47-6.616a7.321,7.321,0,0,1,14.2-.372l1.9,6.836A2.977,2.977,0,0,1,20.126,16.815Z"></path></svg></span></a>
    <br>
    <a href="../profile/"><span class="menu-icon"><img src="../img/<?php echo $usuario['imagen'] ?>" alt="User Avatar"></span></a>
  </aside>
    <div id="chat-list" class="view">
    <aside class="gh darklight" id="users-list">
        <div class="tows borde-bottom" id="chat-header2">
    <div style="width: 100%; display: flex; align-items: center; padding: 10px; border-bottom: 0px solid #ddd; position: relative;">
    
    <img src="../img/<?php echo $usuario['imagen'] ?>" alt="vanne" style="display:none; width: 35px; height: 35px; border-radius: 50%; margin-right: 10px;">
    <span style="font-weight: bold; font-size: 28px;"><?php
// Obtener el idioma del navegador
$navegador_idioma = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

// Asignar el idioma del usuario
$usuario['idioma'] = $navegador_idioma;

switch ($usuario['idioma']) {
    case 'es': // Español
        echo "mensajes";
        break;
    case 'de': // Alemán (Deutsch)
        echo "Chat";
        break;
    case 'ja': // Japonés (日本語)
        echo "チャット";
        break;
    case 'ru': // Ruso (Русский)
        echo "Чат";
        break;
    case 'en': // Inglés
        echo "Chat";
        break;
    case 'fr': // Francés (Français)
        echo "Chat";
        break;
    case 'pt': // Portugués (Português)
        echo "Chat";
        break;
    case 'it': // Italiano
        echo "Chat";
        break;
    case 'ko': // Coreano (한국어)
        echo "채팅";
        break;
    case 'zh': // Chino simplificado (中文)
        echo "聊天";
        break;
    case 'ar': // Árabe (العربية)
        echo "دردشة";
        break;
    case 'hi': // Hindi (हिन्दी)
        echo "चैट";
        break;
    default: // Predeterminado a Inglés
        echo "Chat";
        break;
}
?></span>
    
    <!-- Ícono SVG alineado a la derecha con posición absoluta -->
    <div style="cursor: pointer; position: absolute; right: 5px;">
        
    <a><svg style="background-color: transparent;" class="tows" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="19" height="19"><path d="M18.656.93,6.464,13.122A4.966,4.966,0,0,0,5,16.657V18a1,1,0,0,0,1,1H7.343a4.966,4.966,0,0,0,3.535-1.464L23.07,5.344a3.125,3.125,0,0,0,0-4.414A3.194,3.194,0,0,0,18.656.93Zm3,3L9.464,16.122A3.02,3.02,0,0,1,7.343,17H7v-.343a3.02,3.02,0,0,1,.878-2.121L20.07,2.344a1.148,1.148,0,0,1,1.586,0A1.123,1.123,0,0,1,21.656,3.93Z"/><path d="M23,8.979a1,1,0,0,0-1,1V15H18a3,3,0,0,0-3,3v4H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2h9.042a1,1,0,0,0,0-2H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H16.343a4.968,4.968,0,0,0,3.536-1.464l2.656-2.658A4.968,4.968,0,0,0,24,16.343V9.979A1,1,0,0,0,23,8.979ZM18.465,21.122a2.975,2.975,0,0,1-1.465.8V18a1,1,0,0,1,1-1h3.925a3.016,3.016,0,0,1-.8,1.464Z"/></svg></a>
</div>
</div>

</div>
<center>
<input autocomplete="off" type="text" id="searchInput" placeholder="Search..." onkeyup="buscarUsuario()">
</center>

            

<div class="carousel-container" id="carousel-container">
    <div class="carousel" id="carousel">
                    
<?php
$archivo = "../flow.json";
$datos = array();
$dedu = 0;
if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido);
}

if (isset($_POST['eliminar'])) {
    $id = $_POST['eliminar'];
    unset($datos->$id);

    $json_datos = json_encode($datos, JSON_PRETTY_PRINT);
    file_put_contents($archivo, $json_datos);

    header("Location: csc.php");
    exit();
}

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $datos = array_filter($datos, function ($dato) use ($q) {
        return strpos($dato->foto, $q) !== false
            || strpos($dato->nombre, $q) !== false
            || strpos('$nombre', $q) !== false
            || strpos($dato->texto, $q) !== false;
    });
}

shuffle($datos);
?>

<?php
$promg = 0;
$no = $nombre;
foreach ($datos as $id => $dato) {
    if (strpos($dato->texto, ''.$nombre) !== false) {
        $file = "null";

        if ($file) {
            $info = pathinfo($file);
            $extension = strtolower($file);

            if ($extension === "mp4") {
                echo '
                
                ';
            } else if (in_array($extension, array("jpg", "jpeg", "webp", "gif", "png", "bmp", "tiff"))) {
                $imageData = base64_encode(file_get_contents($file));
                $src = 'data:image/' . $extension . ';base64,' . $imageData;
                echo '
               
                ';
            } else {
                echo '
                <a href="?chat='.$dato->nombre.'"><div class="carousel-item">
        <img src="../img/';

      $clave = $dato->nombre;
      $json = file_get_contents("../usuarios.json");
      $data = json_decode($json, true);
      
      $cadena = $data[$clave];
      
      if (isset($cadena["imagen"])) {
          echo $cadena["imagen"];
      }
      
      echo '" alt="Profile '.$dato->nombre.'">
        <p>';

$clave = $dato->nombre;
$json = file_get_contents("../usuarios.json");
$data = json_decode($json, true);

$cadena = $data[$clave] ?? null;

if ($cadena && isset($cadena["nombre_completo"])) {
    $nombreCompleto = $cadena["nombre_completo"];
    echo (strlen($nombreCompleto) > 8) ? substr($nombreCompleto, 0, 6) . '...' : $nombreCompleto;
}

echo '</p>
      </div></a>
                ';
            }
        } else {
            echo '
            
            ';
        }
    } else {
        // Hacer algo en caso de que no cumpla la condición
    }
}
?>
     
    </div>
  </div>
 <style>
    /* Clase para ocultar elementos */
    .oculto {
        display: none;
    }
    
    
   .carousel-container {
  position: relative;
  width: 100%;
  overflow: hidden;
  margin-top: 15px;
  display: none;
}

.carousel {
  display: flex;
  transition: transform 0.3s ease-in-out;
}

.carousel-item {
  flex: 0 0 25%; /* 4 elementos visibles */
  margin: 0 10px;
  text-align: center;
  white-space: nowrap;        /* Evita que el texto se divida en varias líneas */
  overflow: hidden;           /* Oculta el texto que se desborda */
  text-overflow: ellipsis;    /* Muestra los puntos suspensivos cuando el texto se desborda */
}

.carousel-item img {
  max-width: 60px;
  max-height: 60px;
  min-width: 60px;
  min-height: 60px;
  border-radius: 50%;
  margin-bottom: -5px;
}

@media screen and (max-width: 768px) {
  .carousel-container {
    display: block;
  }
}
</style>
<script>
    let currentIndex = 0;
let startX = 0;
let endX = 0;
const carousel = document.getElementById('carousel');
const items = document.querySelectorAll('.carousel-item');
const totalItems = items.length;
const visibleItems = 4;

function moveCarousel() {
  const offset = -currentIndex * (items[0].offsetWidth + 20); // Ajuste por margen
  carousel.style.transform = `translateX(${offset}px)`;
}

function handleTouchStart(event) {
  startX = event.touches[0].clientX;
}

function handleTouchEnd(event) {
  endX = event.changedTouches[0].clientX;

  if (startX - endX > 50) {
    // Deslizar a la izquierda
    currentIndex = (currentIndex + 1) % totalItems;
  } else if (endX - startX > 50) {
    // Deslizar a la derecha
    currentIndex = (currentIndex - 1 + totalItems) % totalItems;
  }

  moveCarousel();
}

// Detectar eventos de deslizamiento
const carouselContainer = document.getElementById('carousel-container');
carouselContainer.addEventListener('touchstart', handleTouchStart);
carouselContainer.addEventListener('touchend', handleTouchEnd);


</script>

            
            
    <div id="userList"></div>


<script>
    // URL del JSON
    const url = '../usuarios.json';

    // Función para cargar y mostrar los usuarios
    async function cargarUsuarios() {
        try {
            const response = await fetch(url);
            const data = await response.json();
            mostrarUsuarios(data);
        } catch (error) {
            console.error('Error al cargar los usuarios:', error);
        }
    }

    // Función para mostrar los usuarios
    function mostrarUsuarios(usuarios) {
        const userList = document.getElementById('userList');
        userList.innerHTML = ''; // Limpiar lista antes de agregar

        for (const key in usuarios) {
            if (usuarios.hasOwnProperty(key)) {
                const usuario = usuarios[key];

                // Usar `key` como el ID del usuario
                const id = key;

                // Crear el contenedor principal
                const div = document.createElement('div');
                div.classList.add('usuario', 'oculto'); // Agregar clases para manejar visibilidad

                // Crear el contenido con enlaces
                div.innerHTML = `
                    <a href="../inbox/?chat=${id}">
                        <img src="../img/${usuario.imagen}" alt="${usuario.nombre_completo}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    </a>
                    <a href="../inbox/?chat=${id}" style="text-decoration: none; color: inherit; width: 100%;">
                        <div style="width: 100%; display: flex; align-items: center; padding: 0px; border-bottom: 0px solid #ddd;">
                            <p class="name" style="margin: 0; font-size: 16px;">${usuario.nombre_completo}</p>
                            <div style="cursor: pointer; margin-left: auto;">
                                <svg style="background-color: transparent;" xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="currentColor" class="tows bi bi-envelope-open" viewBox="0 0 16 16">
                                    <path d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l5.75 3.45L8 8.917l1.25.75L15 6.217V5.4a1 1 0 0 0-.53-.882zM15 7.383l-4.778 2.867L15 13.117zm-.035 6.88L8 10.082l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738ZM1 13.116l4.778-2.867L1 7.383v5.734ZM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765z"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                `;

                // Agregar el contenedor principal a la lista
                userList.appendChild(div);
            }
        }
    }

    // Función para buscar usuarios
    function buscarUsuario() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const usuarios = document.querySelectorAll('.usuario');

        // Si el campo de búsqueda está vacío, ocultar todos los usuarios
        if (searchInput.trim() === '') {
            usuarios.forEach(usuario => usuario.classList.add('oculto'));
            return;
        }

        // Mostrar u ocultar usuarios según la búsqueda
        usuarios.forEach(usuario => {
            const nombre = usuario.querySelector('.name').textContent.toLowerCase();
            if (nombre.includes(searchInput)) {
                usuario.classList.remove('oculto'); // Mostrar el usuario
            } else {
                usuario.classList.add('oculto'); // Ocultar el usuario
            }
        });
    }

    // Cargar los usuarios al inicio
    cargarUsuarios();
</script>


        <ul></ul>
        
        <br><br>
        <div style="z-index:9999; border-top:0.5px #80808000 solid; border-bottom:1px #80808000 solid;" class="tabs_bottom tows">
        <div class="tab2 tows" style="border-bottom: 3px solid #03a9f400;" onclick="openTab('tab2')"><a href=""><svg id="Layer_1" class="tows" width="22" height="22" style="background-color:transparent" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path xmlns="http://www.w3.org/2000/svg" d="m12 1c-7.71 0-11 3.29-11 11s3.29 11 11 11c3.702 0 9.347-.483 9.586-.504.486-.042.871-.428.911-.914.021-.249.503-6.139.503-9.582 0-7.71-3.29-11-11-11zm-4.5 12.5c-.828 0-1.5-.672-1.5-1.5s.672-1.5 1.5-1.5 1.5.672 1.5 1.5-.672 1.5-1.5 1.5zm4.5 0c-.828 0-1.5-.672-1.5-1.5s.672-1.5 1.5-1.5 1.5.672 1.5 1.5-.672 1.5-1.5 1.5zm4.5 0c-.828 0-1.5-.672-1.5-1.5s.672-1.5 1.5-1.5 1.5.672 1.5 1.5-.672 1.5-1.5 1.5z"/></svg></a></div>
        <div class="tab2 tows" style="border-bottom: 3px solid #00000000;" onclick="openTab('tab1')"><a href=""><svg class="tows" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="20" height="20"><path d="M22.555,13.662l-1.9-6.836A9.321,9.321,0,0,0,2.576,7.3L1.105,13.915A5,5,0,0,0,5.986,20H7.1a5,5,0,0,0,9.8,0h.838a5,5,0,0,0,4.818-6.338ZM12,22a3,3,0,0,1-2.816-2h5.632A3,3,0,0,1,12,22Zm8.126-5.185A2.977,2.977,0,0,1,17.737,18H5.986a3,3,0,0,1-2.928-3.651l1.47-6.616a7.321,7.321,0,0,1,14.2-.372l1.9,6.836A2.977,2.977,0,0,1,20.126,16.815Z"></path></svg></a></div>
        <div class="tab2 tows" style="border-bottom: 3px solid #00000000; text-align: center;" onclick="openTab('tab1')"><a href="../profile/"><img src="../img/<?php echo $usuario['imagen'] ?>" alt="vanne" style="object-fit:cover; width: 23px;h;height: 23px;border-radius: 50%;"></a></div>
    </div>
    </aside>
  </div>
<div style="width: 100%;" id="chat-view" class="view hidden">
    <div class="gh" id="chat-container">
<?php
// Verificar si el parámetro "chat" está presente en la URL
$hasChat = isset($_GET['chat']);
?>
    <div style="display: <?php echo $hasChat ? 'none' : 'flex'; ?>; justify-content: center; align-items: center; height: 100vh; flex-direction: column;">
        <?php if (!$hasChat): ?>
            <!-- Mostrar SVG y mensaje si no hay parámetro "chat" -->
            <img style="height: 150px;" src="https://cdn3d.iconscout.com/3d/premium/thumb/chat-3d-icon-download-in-png-blend-fbx-gltf-file-formats--communication-message-chatting-conversation-design-thinking-pack-development-icons-5409494.png" alt="null">
           
            <p><?php
// Obtener el idioma del navegador
$navegador_idioma = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

// Asignar el idioma del usuario
$usuario['idioma'] = $navegador_idioma;

switch ($usuario['idioma']) {
    case 'es': // Español
        echo "Aún no has elegido un chat o iniciado conversación con amigo";
        break;
    case 'de': // Alemán (Deutsch)
        echo "Sie haben noch keinen Chat ausgewählt oder ein Gespräch mit einem Freund begonnen";
        break;
    case 'ja': // Japonés (日本語)
        echo "まだチャットを選択していないか、友達との会話を開始していません";
        break;
    case 'ru': // Ruso (Русский)
        echo "Вы еще не выбрали чат или начали разговор с другом";
        break;
    case 'en': // Inglés
        echo "You haven't selected a chat or started a conversation with a friend yet";
        break;
    case 'fr': // Francés (Français)
        echo "Vous n'avez pas encore choisi de chat ou commencé une conversation avec un ami";
        break;
    case 'pt': // Portugués (Português)
        echo "Você ainda não escolheu um chat ou iniciou uma conversa com um amigo";
        break;
    case 'it': // Italiano
        echo "Non hai ancora selezionato una chat o iniziato una conversazione con un amico";
        break;
    case 'ko': // Coreano (한국어)
        echo "아직 채팅을 선택하지 않았거나 친구</a>와 대화를 시작하지 않았습니다";
        break;
    case 'zh': // Chino simplificado (中文)
        echo "您尚未选择聊天或开始与朋友交谈";
        break;
    default: // Predeterminado a Inglés
        echo "You haven't selected a chat or started a conversation with a friend yet";
        break;
}
?></p>
        <?php endif; ?>
    </div>
        <div id="chat-box"></div>
        <form id="message-form" method="POST">
    <div class="tows" style="padding: 5px 10px 10px 10px">
        <div id="preview-container" style="display: none; margin-bottom: 10px; position: relative;">
                <img id="preview-image" src="" alt="Preview" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 8px;">
                <button type="button" id="remove-image" style="position: absolute; top: -19px; left: -22px; background: transparent; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer;"><svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="22" height="22" foxified=""><path d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z"/><path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z"/><path d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z"/></svg></button>
            </div>
        <div class="messager colors">
            <div class="icon">
                <img src="../img/<?php echo $usuario['imagen'] ?>" alt="you" width="20" height="20" style="margin-bottom: -2px; object-fit: cover; border-radius: 50%;">
            </div>
            <!-- Image Preview -->
            <!-- Input for text or combined value -->
            <input 
                type="text" 
                id="message"
                name="message"
                class="message-input tows" 
                placeholder="Aa..."
                autocomplete="off"
                style="background-color: transparent; width: 67%;"
            >
            <div class="icon">
                <svg style="background-color: transparent; margin-bottom: -2px; cursor: pointer;" xmlns="http://www.w3.org/2000/svg" id="Outline" fill="rgb(230, 180, 31)" class="bi bi-star not_loved" viewBox="0 0 24 24" width="15" height="15">
                    <path d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22ZM17.625,7.781,16.1,9l1.524,1.219-1.25,1.562L12.9,9l3.476-2.781ZM6.375,10.219,7.9,9,6.375,7.781l1.25-1.562L11.1,9,7.625,11.781ZM16.993,14c0,2-2,5-4.993,5S7.044,16,6.993,14Z"></path>
                </svg>
            </div>
            <div style="display: ;" onclick="openModal()" class="icon">
                <svg style="margin-bottom: -2px;" xmlns="http://www.w3.org/2000/svg" fill="rgb(230, 180, 31)" id="Layer_1" height="22" viewBox="0 0 24 24" width="22" data-name="Layer 1" foxified="">
                    <path d="m12 0a12 12 0 0 0 0 24h.414l11.586-11.586v-.414a12.013 12.013 0 0 0 -12-12zm-10 12a9.994 9.994 0 0 1 19.79-1.989 12 12 0 0 0 -11.779 11.779 10.008 10.008 0 0 1 -8.011-9.79zm10.022 9.564a10 10 0 0 1 9.541-9.542z"></path>
                </svg>
            </div>
            <button type="submit" class="send-button" id="send-button" style="display: none;">
                <svg style="background-color: transparent;" fill="rgb(230, 180, 31)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
                </svg>
            </button>
        </div>
    </div>
</form>
<div id="message222" class="message222"></div>
<script>
        let isActive = false;

        function toggleMessage() {
            const message222 = document.getElementById("message222");
            isActive = !isActive;

            message222.innerText = isActive ? "FriendJoy Active" : "FriendJoy Null";
            message222.style.display = "block";

            setTimeout(() => {
                message222.style.display = "none";
            }, 3000);
        }
    </script>
<div id="myModal">
    <div class="modal-content tows" id="modalContent">
        <a class="close-btn towsi" onclick="closeModal()">×</a>
        <div style="border-top: solid 0px transparent; padding-bottom: 3px; margin-top: 0px;" class="tabs taabs tows">
                 
                <div class="tab" style="padding:12px 7px; border-bottom: 1px solid #0095f6; text-align:center;"><span class="pat" style="font-weight: 600; color: #0095f6;">stickers</span></div>
                <div class="tab" style="padding:12px 7px; border-bottom: 1px solid #7b889259; text-align:center;"><span onclick="toggleMessage()" class="pat" style="font-weight: 400; color: #7b8892;">FriendJoy</span></div>
                
            </div>
        <div style="max-height: 450px; overflow-y: auto;" class="collage">
            <!-- Images -->
            
            <img src="https://dilivel.com/inbox/stickers/dili.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/dili.png')">
            <img src="https://dilivel.com/inbox/stickers/coffe.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/coffe.png')">
            <img src="https://dilivel.com/inbox/stickers/fitt.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/fitt.png')">
            <img src="https://dilivel.com/inbox/stickers/captus.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/captus.png')">
            <img src="https://dilivel.com/inbox/stickers/sad.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/sad.png')">
            <img src="https://dilivel.com/inbox/stickers/angry.png" alt="stickers" onclick="selectImage('https://dilivel.com/inbox/stickers/angry.png')">
            <img src="https://support.signal.org/hc/article_attachments/360083910451/animated-2.gif" alt="Image 2" onclick="selectImage('https://support.signal.org/hc/article_attachments/360083910451/animated-2.gif')">
            <img src="https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/516f62109902745.5fde8ad4723bc.gif" alt="Image 3" onclick="selectImage('https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/516f62109902745.5fde8ad4723bc.gif')">
            <img src="https://media.tenor.com/17DcqIkp0e4AAAAj/heart-love.gif" alt="Image 4" onclick="selectImage('https://media.tenor.com/17DcqIkp0e4AAAAj/heart-love.gif')">
            <img src="https://blogres.wechat.com/uploads/2015/10/Bubble-Pup-3.gif" alt="Image 5" onclick="selectImage('https://blogres.wechat.com/uploads/2015/10/Bubble-Pup-3.gif')">
            <img src="https://i.pinimg.com/originals/bf/18/1b/bf181b41d19481d8f0fd542837f5caa2.gif" alt="Image 5" onclick="selectImage('https://i.pinimg.com/originals/bf/18/1b/bf181b41d19481d8f0fd542837f5caa2.gif')">
            <img src="https://i.pinimg.com/originals/15/06/2e/15062e08233d5acd27f756342d50b042.gif" alt="Image 5" onclick="selectImage('https://i.pinimg.com/originals/15/06/2e/15062e08233d5acd27f756342d50b042.gif')">
            <img src="https://media4.giphy.com/media/boOoHL2PAFXahZyObR/giphy.gif?cid=6c09b95296pwqvg89q7czfj2uirgjbao2z23xnd40vlnlnc9&ep=v1_stickers_search&rid=giphy.gif&ct=s" alt="Image 5" onclick="selectImage('https://media4.giphy.com/media/boOoHL2PAFXahZyObR/giphy.gif?cid=6c09b95296pwqvg89q7czfj2uirgjbao2z23xnd40vlnlnc9&ep=v1_stickers_search&rid=giphy.gif&ct=s')">
            <img src="https://media3.giphy.com/avatars/Badseal/q8TUU7ZeTf0J.png" alt="Image 5" onclick="selectImage('https://media3.giphy.com/avatars/Badseal/q8TUU7ZeTf0J.png')">
            <img src="https://media.tenor.com/J6N-pzgDaSAAAAAj/bonk-chibi.gif" alt="Image 5" onclick="selectImage('https://media.tenor.com/J6N-pzgDaSAAAAAj/bonk-chibi.gif')">
            <img src="https://images.squarespace-cdn.com/content/v1/529cb650e4b09eb8019390ae/1551356614553-3Q8VUAEQMSYTSYVG066C/Hello-Sunshine.gif" alt="Image 5" onclick="selectImage('https://images.squarespace-cdn.com/content/v1/529cb650e4b09eb8019390ae/1551356614553-3Q8VUAEQMSYTSYVG066C/Hello-Sunshine.gif')">
            <img src="https://i.pinimg.com/originals/cf/8e/d7/cf8ed70683778279b28dc89bb34f9f8a.gif" alt="Image 5" onclick="selectImage('https://i.pinimg.com/originals/cf/8e/d7/cf8ed70683778279b28dc89bb34f9f8a.gif')">
            <img src="https://media.stickerswiki.app/jpkitty/725531.512.webp" alt="Image 5" onclick="selectImage('https://media.stickerswiki.app/jpkitty/725531.512.webp')">
            <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/1b657a162533777.63d7ac8f9f3c3.gif" alt="Image 5" onclick="selectImage('https://mir-s3-cdn-cf.behance.net/project_modules/disp/1b657a162533777.63d7ac8f9f3c3.gif')">
            <img src="https://freight.cargo.site/w/512/i/32070fa2c29d0e7855684e361a86a44f3dfe2e235b0abc13cf2f2244f5d688f1/26c.gif" alt="Image 5" onclick="selectImage('https://freight.cargo.site/w/512/i/32070fa2c29d0e7855684e361a86a44f3dfe2e235b0abc13cf2f2244f5d688f1/26c.gif')">
            <img src="https://media0.giphy.com/media/YSlD6I04v4s9pgwPcT/giphy.gif?cid=6c09b952miekdemt8tvwuobtnleoc7tg3mn8a22qns20fc26&ep=v1_stickers_search&rid=giphy.gif&ct=s" alt="Image 5" onclick="selectImage('https://media0.giphy.com/media/YSlD6I04v4s9pgwPcT/giphy.gif?cid=6c09b952miekdemt8tvwuobtnleoc7tg3mn8a22qns20fc26&ep=v1_stickers_search&rid=giphy.gif&ct=s')">
            <img src="https://www.descargarstickers.com/src_img/2021/09/295939.gif" alt="Image 5" onclick="selectImage('https://www.descargarstickers.com/src_img/2021/09/295939.gif')">
            <img src="https://cdn.domestika.org/c_limit,dpr_auto,f_auto,q_80,w_820/v1565289005/content-items/003/182/766/sticker-reaccion-original.gif?1565289005" alt="Image 5" onclick="selectImage('https://cdn.domestika.org/c_limit,dpr_auto,f_auto,q_80,w_820/v1565289005/content-items/003/182/766/sticker-reaccion-original.gif?1565289005')">
            <img src="https://i.gifer.com/Vi0.gif" alt="Image 5" onclick="selectImage('https://i.gifer.com/Vi0.gif')">
            <img src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/1f3789122762039.60e11f12392f6.gif" alt="Image 5" onclick="selectImage('https://mir-s3-cdn-cf.behance.net/project_modules/disp/1f3789122762039.60e11f12392f6.gif')">
            <img src="https://cdn-icons-png.flaticon.com/512/8453/8453300.png" alt="Image 5" onclick="selectImage('https://cdn-icons-png.flaticon.com/512/8453/8453300.png')">
            <img src="https://i.pinimg.com/originals/83/c8/ce/83c8ce04697f232e16a0717997c548a7.gif" alt="Image 5" onclick="selectImage('https://i.pinimg.com/originals/83/c8/ce/83c8ce04697f232e16a0717997c548a7.gif')">
        
<br><br><br>
        </div>
    </div>
</div>

<script>
    // Open modal function
    function openModal() {
        document.getElementById("myModal").style.display = "block";
    }

    // Close modal function
    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    // Function to preview the selected image
    function selectImage(imageUrl) {
        const previewContainer = document.getElementById("preview-container");
        const previewImage = document.getElementById("preview-image");
        const input = document.getElementById("message");
        const sendButton = document.getElementById("send-button");

        // Set the image source for preview
        previewImage.src = imageUrl;
        previewContainer.style.display = "block";

        // Save the image URL in the input's dataset
        input.dataset.image = imageUrl;

        // Show the send button
        toggleSendButton();

        // Close the modal
        closeModal();
    }

    // Remove image preview
    document.getElementById("remove-image").addEventListener("click", function () {
        const previewContainer = document.getElementById("preview-container");
        const input = document.getElementById("message");

        // Hide the preview and remove the image URL
        previewContainer.style.display = "none";
        input.dataset.image = "";

        // Toggle the send button
        toggleSendButton();
    });

    // Show or hide the send button based on input or image
    function toggleSendButton() {
        const input = document.getElementById("message");
        const sendButton = document.getElementById("send-button");

        const textValue = input.value.trim();
        const imageUrl = input.dataset.image || "";

        // Show the send button if there's text or an image
        if (textValue || imageUrl) {
            sendButton.style.display = "inline-block";
        } else {
            sendButton.style.display = "none";
        }
    }

    // Listen for text input changes
    document.getElementById("message").addEventListener("input", toggleSendButton);

    // Combine text and image before submitting
    document.getElementById("message-form").addEventListener("submit", function (event) {
        const input = document.getElementById("message");
        const textValue = input.value.trim();
        const imageUrl = input.dataset.image || ""; // Get the image URL if available
        const sendButton = document.getElementById("send-button");

        // Combine text and image URL
        input.value = imageUrl ? `${textValue} ${imageUrl}` : textValue;

        // Hide the send button when the form is submitted
        sendButton.style.display = "none";

        // Check if there is an image before reloading the page
        if (imageUrl) {
            location.reload(); // Reload immediately
        }
    });
</script>




<script>
    // Lista de emojis para alternar
    const emojis = ['❤️', '👋', '🎉', '😊', '🎂', '😘', '🏳️‍🌈', '🍕', '🥰', '😢', ''];
    let emojiIndex = 0; // Índice actual en la lista de emojis

    // Evento para enviar mensaje (form)
    document.querySelector('.messager').addEventListener('submit', function(e) {
        e.preventDefault();
        const message = document.querySelector('.message-input').value;
        document.querySelector('.message-input').value = '';
        console.log('Mensaje enviado:', message);
    });

    // Evento para alternar emojis al presionar el botón
    document.querySelector('.bi-star').addEventListener('click', function() {
        const input = document.querySelector('.message-input');

        // Cambia el contenido del input por el emoji actual
        input.value = emojis[emojiIndex];

        // Incrementa el índice para el siguiente emoji
        emojiIndex = (emojiIndex + 1) % emojis.length; // Reinicia a 0 cuando llega al final

        // Mantiene el foco en el input
        input.focus();
    });
</script>

    </div>
  </div>
  
  <script>
    // Escuchar el evento 'load' para modificar la vista
    window.addEventListener("load", () => {
        const chatList = document.getElementById("chat-list");
        const chatView = document.getElementById("chat-view");
        const messageForm = document.getElementById("message-form");

        // Detectar si hay un parámetro ?chat en la URL
        const urlParams = new URLSearchParams(window.location.search);
        const isChatActive = urlParams.has("chat");

        // Mostrar u ocultar las vistas según la URL
        if (isChatActive) {
            chatList.classList.remove("visible");
            chatView.classList.add("visible");
            messageForm.style.display = "block"; // Mostrar el formulario
        } else {
            chatList.classList.add("visible");
            chatView.classList.remove("visible");
            messageForm.style.display = "none"; // Ocultar el formulario
        }
    });
</script>

<script>
    const chatBox = document.getElementById('chat-box');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message');
    const usersList = document.getElementById('users-list').querySelector('ul');
    const chatHeader = document.createElement('div');
    chatHeader.id = 'chat-header';
    chatHeader.className = 'chatop';
    document.getElementById('chat-container').prepend(chatHeader);

    let currentUser = '<?php echo $d ?>'; // Usuario actual
    let currentChat = new URLSearchParams(window.location.search).get('chat') || null; // Chat actual
    let usersWithUnreadMessages = {}; // Usuarios con mensajes no leídos
    let lastMessageTimestamps = {}; // Últimos tiempos de mensajes para ordenar chats
    let isAtBottom = true; // Variable para rastrear si el usuario está en la parte inferior del chat
    let newMessageIndicator = false; // Variable para rastrear si hay un nuevo mensaje no leído

    // Funciones para gestionar cookies
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`).pop().split(';').shift();
        return parts || null;
    }

    // Función para formatear el tiempo transcurrido
    function timeAgo(timestamp) {
        const now = new Date();
        const diff = now - new Date(timestamp);

        if (diff < 60000) return "new"; // Menos de 1 minuto
        if (diff < 3600000) return `${Math.floor(diff / 60000)} min`; // Menos de 1 hora
        if (diff < 86400000) return `${Math.floor(diff / 3600000)} h`; // Menos de 1 día
        if (diff < 604800000) return `${Math.floor(diff / 86400000)} d`; // Menos de 1 semana
        return `${Math.floor(diff / 604800000)} sem`; // Más de 1 semana
    }

    // Actualizar usuarios con mensajes no leídos
    function updateUnreadMessages(messages) {
        usersWithUnreadMessages = {};
        messages.forEach(msg => {
            const otherUser = msg.from === currentUser ? msg.to : msg.from;
            lastMessageTimestamps[otherUser] = msg.timestamp;

            if (msg.to === currentUser && !msg.views) {
                const readChats = JSON.parse(getCookie('readChats') || '{}');
                if (!readChats[otherUser]) {
                    usersWithUnreadMessages[otherUser] = true;
                }
            }
        });

        loadUsers();
    }

    // Marcar mensajes como leídos
    async function markMessagesAsRead(username) {
        await fetch(`chat.php?action=markAsRead&user=${currentUser}&chat=${username}`, { method: 'POST' });
        const readChats = JSON.parse(getCookie('readChats') || '{}');
        readChats[username] = true; // Marcar como leído
        setCookie('readChats', JSON.stringify(readChats), 7);

        if (usersWithUnreadMessages[username]) {
            delete usersWithUnreadMessages[username];
        }

        loadUsers();
    }

    // Eliminar un mensaje
    async function deleteMessage(id) {
        const response = await fetch(`chat.php?action=delete&id=${id}`, { method: 'DELETE' });
        if (response.ok) {
            loadMessages(currentChat);
        } else {
            alert('No se pudo eliminar el mensaje.');
        }
    }

    // Cargar usuarios
async function loadUsers() {
    try {
        const response = await fetch('../usuarios.json');
        const users = await response.json();

        const responseMessages = await fetch(`chat.php?action=get&user=${currentUser}`);
        const messages = await responseMessages.json();

        updateUnreadMessages(messages);

        // Filtrar usuarios con los que ya has tenido una conversación
        const usersWithConversation = Object.keys(users)
            .filter(username => username !== currentUser)
            .filter(username => {
                // Verificar si hay mensajes con este usuario
                return messages.some(msg => msg.from === username || msg.to === username);
            })
            .sort((a, b) => {
                const timeA = new Date(lastMessageTimestamps[a] || 0);
                const timeB = new Date(lastMessageTimestamps[b] || 0);
                return timeB - timeA;
            });

        usersList.innerHTML = usersWithConversation.map(username => {
            const user = users[username];
            const hasUnreadMessages = usersWithUnreadMessages[username];
            const nameStyle = hasUnreadMessages ? 'font-weight: bold;' : 'font-weight: normal;';
            const lastMessageText = user.lastMessage || `@${username}`; // Si no hay último mensaje, muestra @username
            return `<a href="?chat=${username}"><li style="${hasUnreadMessages ? 'background-color:#c7baba12;' : ''} position: relative; display: flex; flex-direction: column; align-items: flex-start; padding: 10px; border-bottom: 0px solid #ddd;">
                <div style="display: flex; align-items: center; width: 100%;">
                    <img src="../img/${user.imagen}" alt="${user.nombre_completo}" width="50" height="50" style="object-fit: cover; border-radius: 50%; margin-right: 10px;">
                    <div style="flex: 1; display: flex; flex-direction: column;">
                        <span class="tows darklight" 
                            style="${nameStyle} font-size: 17px;" 
                            id="user-${username}">
                            ${user.nombre_completo.length > 13 ? user.nombre_completo.slice(0, 13) + '...' : user.nombre_completo}
                        </span>
                        <span style="font-size: 12px; color: #666;" id="last-message-${username}">
                            ${lastMessageText}
                        </span>
                    </div>
                    <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  style="margin-bottom: -5px;" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438zM3.809 3.563A1.5 1.5 0 0 1 4.981 3h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374z"/>
                        </svg>
                        ${hasUnreadMessages ? '<div class="new-message" style="width: 10px; height: 10px; background: red; border-radius: 50%; display: inline-block; margin-left: 10px;"></div>' : ''}
                    </span>
                </div>
            </li></a>`;
        }).join('');
    } catch (error) {
        console.error('Error al cargar usuarios:', error);
    }
}

    // Actualizar encabezado del chat
    async function updateChatHeader(username) {
        try {
            const response = await fetch('../usuarios.json');
            const users = await response.json();
            const user = users[username];

            chatHeader.innerHTML = 
                `<div style="width:100%; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px) saturate(1.5); -webkit-backdrop-filter: blur(10px) saturate(1.5); display: flex; align-items: center; padding: 10px; border-bottom: 0px solid #ddd;">
                    <a href="javascript:history.back()" style="text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgb(230, 180, 31)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px; cursor: pointer; margin-right: 10px;">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                    <img src="../img/${user.imagen}" alt="${user.nombre_completo}" style="object-fit: cover; width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <span style="font-weight: bold;">${user.nombre_completo}</span>
                    
                    <div style="cursor: pointer; position: absolute; right: 5px;">
                        <a><svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" fill="rgb(230, 180, 31)" style="width: 22px; height: 22px;">
                            <path d="m17,10c-2.868,0-5.336,1.735-6.416,4.21-.166-.123-.361-.21-.584-.21-.552,0-1,.447-1,1v2c0,.553.448,1,1,1,.027,0,.051-.013.078-.016.481,3.394,3.398,6.016,6.922,6.016,3.86,0,7-3.141,7-7s-3.14-7-7-7Zm0,12c-2.757,0-5-2.243-5-5s2.243-5,5-5,5,2.243,5,5-2.243,5-5,5Zm3.221-6.212c.383.398.37,1.031-.029,1.414l-2.212,2.124c-.452.446-1.052.671-1.653.671s-1.204-.225-1.664-.674l-1.132-1.108c-.395-.387-.401-1.02-.015-1.414.387-.395,1.02-.401,1.414-.016l1.132,1.108c.144.141.379.142.522,0l2.223-2.134c.398-.381,1.031-.37,1.414.029Zm-10.221,7.212c0,.553-.448,1-1,1h-4c-2.757,0-5-2.243-5-5v-6c0-2.045,1.237-3.802,3-4.576v-1.424C3,3.14,6.14,0,10,0s7,3.14,7,7c0,.552-.448,1-1,1s-1-.448-1-1c0-2.757-2.243-5-5-5s-5,2.243-5,5v1h8c.552,0,1,.448,1,1s-.448,1-1,1H5c-1.654,0-3,1.346-3,3v6c0,1.654,1.346,3,3,3h4c.552,0,1,.447,1,1Z"/>
                        </svg></a>
                        
                    </div>
                </div>`;
        } catch (error) {
            console.error('Error al cargar encabezado:', error);
        }
    }

    // Volver a la lista de usuarios
    function goBack() {
        currentChat = null;
        window.history.pushState({}, '', window.location.pathname);
        chatBox.innerHTML = '';
        chatHeader.innerHTML = '';
    }

    // Iniciar un chat
    async function startChat(username) {
        currentChat = username;

        window.history.pushState({}, '', `?chat=${username}`);
        await markMessagesAsRead(username);
        loadMessages(username);
        updateChatHeader(username); // Actualizar el encabezado al iniciar un chat
    }

    // Cargar mensajes
    async function loadMessages(username) {
        if (!username) return;

        // Detectar si el usuario está al final del chat
        isAtBottom = chatBox.scrollHeight - chatBox.scrollTop === chatBox.clientHeight;

        try {
            const response = await fetch(`chat.php?action=get&user=${currentUser}&chat=${username}`);
            const messages = await response.json();
            chatBox.innerHTML = 
                `<div style="position: relative; margin-bottom: 10px; height: 50px;">
                    <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); text-align: center; color: #ccc; padding: 7px;">
                        <br><br><br><br><br>
                        <?php
                            // Verificar si el parámetro "chat" está presente en la URL
                            $usuario_id = isset($_GET['chat']) ? $_GET['chat'] : null;

                            if ($usuario_id) {
                                // Leer el archivo JSON
                                $json = file_get_contents('https://dilivel.com/usuarios.json');

                                // Decodificar el JSON a un array asociativo
                                $usuarios = json_decode($json, true);

                                // Verificar si el usuario existe
                                if (isset($usuarios[$usuario_id])) {
                                    $usuario = $usuarios[$usuario_id];
                                    $imagen = htmlspecialchars($usuario['imagen']); // Ruta de la imagen
                                } else {
                                    $imagen = null;
                                }
                            } else {
                                $imagen = null;
                            }
                        ?>

                        <?php if ($imagen): ?>
                            <img src="https://dilivel.com/perfiles/<?= $imagen ?>" style="object-fit: cover; width: 65px; height: 65px; border-radius: 50%;" alt="Imagen de usuario">
                            <img src="../img/<?php echo $ggh2 ?>" style="border: solid 1.5px white; object-fit: cover; width: 22px; height: 22px; border-radius: 50%; margin-left: -27px;" alt="Imagen de usuario">
                        <?php else: ?>
                        <?php endif; ?>

                        <br>
                        <?php
                            // Obtener el idioma del navegador
                            $navegador_idioma = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

                            // Asignar el idioma del usuario
                            $usuario['idioma'] = $navegador_idioma;

                            switch ($usuario['idioma']) {
                                case 'es': // Español
                                    echo "Inicia conversación con";
                                    break;
                                case 'de': // Alemán (Deutsch)
                                    echo "Beginne ein Gespräch mit";
                                    break;
                                case 'ja': // Japonés (日本語)
                                    echo "と会話を始める";
                                    break;
                                case 'ru': // Ruso (Русский)
                                    echo "Начать разговор с";
                                    break;
                                case 'en': // Inglés
                                    echo "Start conversation with";
                                    break;
                                case 'fr': // Francés (Français)
                                    echo "Commence une conversation avec";
                                    break;
                                case 'pt': // Portugués (Português)
                                    echo "Iniciar conversa com";
                                    break;
                                case 'it': // Italiano
                                    echo "Inizia una conversazione con";
                                    break;
                                case 'ko': // Coreano (한국어)
                                    echo "대화를 시작합니다";
                                    break;
                                case 'zh': // Chino simplificado (中文)
                                    echo "开始对话";
                                    break;
                                case 'ar': // Árabe (العربية)
                                    echo "ابدأ محادثة مع";
                                    break;
                                case 'hi': // Hindi (हिन्दी)
                                    echo "के साथ बातचीत शुरू करें";
                                    break;
                                default: // Predeterminado a Inglés
                                    echo "Start conversation with";
                                    break;
                            }
                        ?> ${username}
                    </div>
                </div><br><br><br><br>
                ${messages
                    .filter(msg => (msg.from === username && msg.to === currentUser) || (msg.from === currentUser && msg.to === username))
                    .map(msg => {
                        const isCurrentUser = msg.from === currentUser;
                        const alignment = isCurrentUser ? 'align-self: flex-end; left:5px;' : 'align-self: flex-start; right:5px;';
                        const backgroundColor = isCurrentUser ? '#3797f0' : '#262626';
                        const timestamp = timeAgo(msg.timestamp);
                        const views = isCurrentUser
                            ? msg.views
                                ? '<span style="color: gray;">visto</span>'
                                : '<span style="color: gray;"></span>'
                            : '';

                        const parseText = (text) => {
                            // Dividimos el texto por espacios para procesarlo palabra por palabra
                            const words = text.split(/\s+/);

                            for (let i = 0; i < words.length; i++) {
                                const word = words[i];

                                // Comprobamos si es un enlace de imagen
                               if (/^(https?:\/\/[^\s]+(?:\.(png|gif|jpg|jpeg|webp)))/i.test(word)) {
            words[i] = `<img src="${word}" style="max-width: 100%; max-height: auto; border-radius: 0px; margin-top: 5px;" />`;
        }
        // Comprobamos si es un enlace genérico
        else if (/^(https?:\/\/[^\s]+(?:\.com|\.net|\.org|[a-z]{2,}))/i.test(word)) {
            const truncatedText = word.length > 25 ? word.slice(0, 25) + '...' : word;
            words[i] = `<a href="${word}" target="_blank" style="font-weight: 800; color: white;">${truncatedText}</a>`;
        }
        // Comprobamos si es una palabra larga (más de 25 caracteres)
        else if (word.length > 25) {
            words[i] = word.slice(0, 25) + '...';
        }
        // Cambiamos el color de palabras específicas
        else {
            let lowerWord = word.toLowerCase();

           if (/\blgbtq\b|\blgbtq\+\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, red, orange, yellow, green, blue, indigo, violet); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`;
            } else if (/\bfeminista\b|\bfeminism\b|\bféministe\b/i.test(lowerWord)) {
                words[i] = `<span style="color: purple; font-weight: bold;">${word}</span>`;
            } else if (/\bamigos\b|\bfriends\b|\bamis\b|\bfreunde\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, red, orange); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`;
            } else if (/\boro\b|\bgold\b|\bor\b/i.test(lowerWord)) {
                words[i] = `<span style="color: gold; font-weight: bold;">${word}</span>`;
            } else if (/\bregalo\b|\bgift\b|\bcadeau\b|\bgeschenk\b/i.test(lowerWord)) {
                words[i] = `<span style="color: pink; font-weight: bold;">${word}</span>`;
            } else if (/\benojado\b|\bangry\b|\bfâché\b|\bwütend\b/i.test(lowerWord)) {
                words[i] = `<span style="color: red; font-weight: bold;">${word}</span>`;
            } else if (/\bamor\b|\blove\b|\bamour\b|\bliebe\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, #ec30abde, crimson); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`; // 💖 Rojo pasión
            } else if (/\btranquilidad\b|\bpeace\b|\bpaix\b|\bfrieden\b/i.test(lowerWord)) {
                words[i] = `<span style="color: lightblue; font-weight: bold;">${word}</span>`; // ☁️ Azul cielo
            } else if (/\bmagia\b|\bmagic\b|\bmagie\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, #7F00FF, #E100FF); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`; // 🔮 De morado a rosa brillante
            } else if (/\bfuego\b|\bfire\b|\bfeu\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, red, orange, yellow); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`; // 🔥 Rojo-naranja-amarillo
            } else if (/\bhielo\b|\bice\b|\bglace\b/i.test(lowerWord)) {
                words[i] = `<span style="color: cyan; font-weight: bold;">${word}</span>`; // ❄️ Azul hielo
            } else if (/\btristeza\b|\bsadness\b|\btristesse\b/i.test(lowerWord)) {
                words[i] = `<span style="color: navy; font-weight: bold;">${word}</span>`; // 😢 Azul oscuro
            } else if (/\bfelicidad\b|\bhappiness\b|\bbonheur\b/i.test(lowerWord)) {
                words[i] = `<span style="color: yellow; font-weight: bold;">${word}</span>`; // 😊 Amarillo brillante
            } else if (/\bdinero\b|\bmoney\b|\bargent\b/i.test(lowerWord)) {
                words[i] = `<span style="color: green; font-weight: bold;">${word}</span>`; // 💵 Verde dinero
            } else if (/\bnoche\b|\bnight\b|\bnuit\b/i.test(lowerWord)) {
                words[i] = `<span style="color: darkblue; font-weight: bold;">${word}</span>`; // 🌙 Azul noche
            }
            else if (/\porno\b|\porn\b|\xxx\b/i.test(lowerWord)) {
                words[i] = `<span style="background: linear-gradient(90deg, red, #ad4af0de); -webkit-background-clip: text; color: transparent; font-weight: bold;">${word}</span>`; // 🌙 Azul noche
            }

            // Agregamos imágenes tipo emoji para ciertas palabras clave
            const emojiMap = {
                "#love#": "https://i.gifer.com/Vh2.gif", // ❤️ Corazón
                "#happy#": "https://i.scdn.co/image/ab67616d00001e0203eaf78e3d42241022751bb2", // 😊 Carita feliz
                "#sad#": "https://static.vecteezy.com/system/resources/thumbnails/013/743/772/small/sad-face-emoji-png.png", // 😢 Carita triste
                "#cat#": "https://png.pngtree.com/png-vector/20240914/ourmid/pngtree-realistic-cat-head-vector-material-png-image_12909681.png", // 🐱 Gato
                "#dog#": "https://www.centralbarkusa.com/wp-content/uploads/2024/02/image005-copy.png", // 🐶 Perro
                "#hitler#": "https://i.gifer.com/9hgC.gif", // ⭐ Estrella
            };

            if (emojiMap[word.toLowerCase()]) {
                words[i] = `<img src="${emojiMap[word.toLowerCase()]}" style="width: 25px; height: 25px; vertical-align: middle;">`;
            }
        }
    }

    // Reunimos las palabras procesadas en un solo texto
    return words.join(' ');
};

                        return `
                            <div style="display: flex; ${isCurrentUser ? 'justify-content: flex-end;' : 'justify-content: flex-start;'} margin-bottom: 10px;">
                                <div class="tweet-style" style="
                                    display: flex; 
                                    align-items: flex-start; 
                                    background-color: ${isCurrentUser ? 'transparent' : 'transparent'}; 
                                    border: 1px solid ${isCurrentUser ? 'transparent' : 'transparent'}; 
                                    border-radius: 12px; 
                                    padding: 10px; 
                                    color: white;
                                    margin-${isCurrentUser ? 'right' : 'left'}: 10px; 
                                    max-width: 70%;">
                                    
                                    <div style="flex-grow: 1; text-align: ${isCurrentUser ? 'left' : 'left'};">
                                        <div style="padding: 12px; border-top-left-radius: 20px; border-top-right-radius: ${isCurrentUser ? '20px' : '20px'}; border-bottom-right-radius: ${isCurrentUser ? '20px' : '20px'}; border-bottom-left-radius: ${isCurrentUser ? '20px' : '20px'}; background: linear-gradient(${isCurrentUser ? '180deg, rgb(230, 180, 31), rgb(230, 180, 31)' : '180deg, #7f7b7b7d, #7f7b7b7d'});"><strong style="color: ${isCurrentUser ? 'white' : 'white'}; font-size: 14px;">
                                            <strong style="display: none;">${isCurrentUser ? '' : msg.from}</strong>
                                        </strong>
                                        <p style="font-weight: 400; color: ${isCurrentUser ? 'white' : 'white'}; margin: 5px 0; font-size: 15px; line-height: 1.4; word-wrap: break-word;">
                                            ${parseText(msg.text)}
                                        </p>
                                        </div>
                                        <div style="padding: 2px 5px; display: flex; justify-content: ${isCurrentUser ? 'flex-end' : 'flex-start'}; align-items: center; font-size: 12px; color: #657786;">
                                            <span>${timestamp}</span>
                                            ${views}
                                            ${isCurrentUser ? 
                                                `<button onclick="deleteMessage(${msg.id})" 
                                                    style="border: none; background: none; padding: 0px 4px; color: rgb(230, 180, 31); cursor: pointer; font-size: 12px; margin-left: auto;">
                                                    @${msg.from}
                                                </button>` : ''}
                                        </div>
                                    </div>
                                    ${isCurrentUser ? 
                                        `<img src="../img/<?php echo $usuario['imagen'] ?>" 
                                            alt="Profile Image" 
                                            style="display:none; margin-top: -16px; width: 30px; height: 30px; border-radius: 50%; margin-left: 5px; margin-right: 0px; object-fit: cover;" />`
                                     : ''}
                                </div>
                            </div>
                        `;
                    }).join('')}`;

            // Desplazar al final si el usuario estaba en la parte inferior
            if (isAtBottom) {
                chatBox.scrollTop = chatBox.scrollHeight;
                newMessageIndicator = false; // Reiniciar el indicador de nuevo mensaje
            } else {
                // Mostrar el botón de "ir al final" si el usuario no está en la parte inferior
                if (!document.getElementById('scroll-to-bottom-button')) {
                    const scrollButton = document.createElement('button');
                    scrollButton.id = 'scroll-to-bottom-button';
scrollButton.style.position = 'fixed';
scrollButton.style.bottom = '60px'; 
scrollButton.style.left = '50%'; 
scrollButton.style.transform = 'translateX(-50%)'; 
scrollButton.style.width = '40px';  
scrollButton.style.height = '40px';  
scrollButton.style.backgroundColor = '#7f7b7b7d';
scrollButton.style.color = 'white';
scrollButton.style.padding = '12px';
scrollButton.style.border = 'none';
scrollButton.style.borderRadius = '50%';
scrollButton.style.cursor = 'pointer';
scrollButton.style.zIndex = '1000';
scrollButton.style.display = 'flex';  
scrollButton.style.alignItems = 'center';  
scrollButton.style.justifyContent = 'center';  

scrollButton.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512.308 512.308" xml:space="preserve" height="34" width="34">
        <g>
            <path fill="white" d="M505.878,36.682L110.763,431.69c8.542,4.163,17.911,6.351,27.413,6.4h67.669c5.661-0.015,11.092,2.236,15.083,6.251   
            l36.672,36.651c19.887,20.024,46.936,31.295,75.157,31.317c11.652-0.011,23.224-1.921,34.261-5.653   
            c38.05-12.475,65.726-45.46,71.403-85.099l72.085-342.4C513.948,64.89,512.311,49.871,505.878,36.682z"/>
            <path fill="white" d="M433.771,1.652L92.203,73.61C33.841,81.628-6.971,135.44,1.047,193.802c3.167,23.048,13.782,44.43,30.228,60.885   
            l36.651,36.651c4.006,4.005,6.255,9.439,6.251,15.104v67.669c0.049,9.502,2.237,18.872,6.4,27.413L475.627,6.41   
            C462.645,0.03,447.853-1.651,433.771,1.652z"/>
        </g>
    </svg>
`;

                    scrollButton.onclick = () => {
                        chatBox.scrollTop = chatBox.scrollHeight;
                        scrollButton.remove(); // Eliminar el botón después de hacer clic
                    };

                    // Mostrar el punto rojo solo si hay un nuevo mensaje no leído
                    if (newMessageIndicator) {
                        const dot = document.createElement('div');
                        dot.style.width = '10px';
                        dot.style.height = '10px';
                        dot.style.backgroundColor = 'red';
                        dot.style.borderRadius = '50%';
                        dot.style.position = 'absolute';
                        dot.style.top = '0';
                        dot.style.right = '0';
                        scrollButton.appendChild(dot);
                    }

                    document.body.appendChild(scrollButton);
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Enviar mensaje
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = messageInput.value;

        if (!message.trim() || !currentChat) return;

        const timestamp = new Date().toISOString();

        try {
            await fetch(`chat.php?action=send&user=${currentUser}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    from: currentUser,
                    to: currentChat,
                    text: message,
                    timestamp: timestamp
                })
            });

            messageInput.value = '';
            loadMessages(currentChat);
            chatBox.scrollTop = chatBox.scrollHeight; // Desplazar al final después de enviar un mensaje
        } catch (error) {
            console.error('Error al enviar mensaje:', error);
        }
    });

    // Inicializar usuarios y mensajes
    if (currentChat) {
        startChat(currentChat);
    }

    // Verificar si el usuario está en la parte inferior del chat
    chatBox.addEventListener('scroll', () => {
        isAtBottom = chatBox.scrollHeight - chatBox.scrollTop === chatBox.clientHeight;
        if (isAtBottom && document.getElementById('scroll-to-bottom-button')) {
            document.getElementById('scroll-to-bottom-button').remove();
        }
    });

    // Actualizar usuarios y mensajes cada 2 segundos
    setInterval(() => {
        loadUsers();
        if (currentChat) {
            loadMessages(currentChat);
            // Verificar si hay un nuevo mensaje no leído
            const unreadMessages = Object.values(usersWithUnreadMessages).filter(Boolean).length;
            newMessageIndicator = unreadMessages > 0;
        }
    }, 2000);

    loadUsers();
</script>

<button style=" display: none;" id="toggleDarkMode">Toggle Dark Mode</button>
    <script src="../dark.js"></script>
   
</body> 
</html>