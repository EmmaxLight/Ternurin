<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario Exitoso</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('https://fbi.cults3d.com/uploaders/33925309/illustration-file/4c6334b8-923d-4185-8b91-a6e7bda3974c/imagen_2024-05-19_204900589.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: fadeIn 2s ease-in-out;
            position: relative;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.3);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out 1s both;
            backdrop-filter: blur(5px);
            position: relative;
            width: 300px;
            z-index: 2;
            text-align: center;
        }
        input[type="submit"], button {
            background-color: purple;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        h1 {
            margin-bottom: 15px;
            color: black;
        }
        .message {
            margin: 15px 0;
            color: black;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="form-container">
        <?php
        // Incluimos base de datos
        include "./../includes/config.php";

        // Mensajes iniciales
        $mensaje = "";
        $tipo = "";

        // Comprobamos si se está registrando un usuario
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['eliminar']) && !isset($_POST['editar'])) {
            $nombre = $_POST['nom'] ?? '';
            $app = $_POST['ap'] ?? '';
            $apm = $_POST['am'] ?? '';
            $usua = $_POST['usu'] ?? '';
            $contra = $_POST['con'] ?? '';

            // Comprobamos si el usuario ya existe
            $peticionx = "SELECT * FROM usuariosAdmin WHERE usuari ='$usua'";
            $respuestax = mysqli_query($conexion, $peticionx);

            if ($filax = mysqli_fetch_array($respuestax)) {
                // El usuario ya existe
                $mensaje = "El usuario ya existe.";
                $tipo = "error";
            } else {
                // Insertamos nuevo usuario
                $peticion = "INSERT INTO usuariosAdmin VALUES(NULL, '$nombre', '$app', '$apm', '$usua', '$contra', 1)";
                if (mysqli_query($conexion, $peticion)) {
                    $mensaje = "Te has registrado con éxito.";
                    $tipo = "success";
                } else {
                    $mensaje = "Error al registrar usuario.";
                    $tipo = "error";
                }
            }
        }

        // Acciones de eliminar o editar usuario
        if (isset($_POST['eliminar'])) {
            // Eliminar usuario
            $usua = $_POST['usu'];
            $peticion = "DELETE FROM usuariosAdmin WHERE usuari = '$usua'";
            if (mysqli_query($conexion, $peticion)) {
                $mensaje = "Usuario eliminado con éxito.";
                $tipo = "success";
            } else {
                $mensaje = "Error al eliminar usuario.";
                $tipo = "error";
            }
        } elseif (isset($_POST['editar'])) {
            // Obtener los datos del usuario
            $usua = $_POST['usu'];
            $peticion = "SELECT * FROM usuariosAdmin WHERE usuari = '$usua'";
            $respuestax = mysqli_query($conexion, $peticion);
            if ($filax = mysqli_fetch_array($respuestax)) {
                // Mostrar formulario de edición
                $nombre = $filax['nombre'];
                $app = $filax['apellido_pa'];
                $apm = $filax['apellido_ma'];
                $us = $filax['usuari'];
                $contra = $filax['contra'];
                echo "
                <form action='' method='post'>
                    <h1>Editar Usuario</h1>
                    <input type='text' name='nom' value='$nombre' required placeholder='Nombre'>
                    <input type='text' name='ap' value='$app' required placeholder='Apellido Paterno'>
                    <input type='text' name='am' value='$apm' required placeholder='Apellido Materno'>
                    <input type='text' name='usu' value='$us' required placeholder='Usuario' readonly>
                    <input type='password' name='con' value='$contra' required placeholder='Contraseña'>
                    <input type='hidden' name='tipo' value='1'>
                    <input type='submit' name='guardar' value='Guardar Cambios'>
                </form>";
            } else {
                $mensaje = "Usuario no encontrado.";
                $tipo = "error";
            }
        } elseif (isset($_POST['guardar'])) {
            // Guardar cambios del usuario
            $nombre = $_POST['nom'];
            $app = $_POST['ap'];
            $apm = $_POST['am'];
            $contra = $_POST['con'];
            $usua = $_POST['usu'];

            $peticion = "UPDATE usuariosAdmin SET nombre='$nombre', apellido_pa='$app', apellido_ma='$apm', contra='$contra' WHERE usuari='$usua'";
            if (mysqli_query($conexion, $peticion)) {
                $mensaje = "Datos actualizados con éxito.";
                $tipo = "success";
            } else {
                $mensaje = "Error al actualizar datos.";
                $tipo = "error";
            }
        }

        // Mostrar mensaje correspondiente
        if ($mensaje) {
            echo "<h1 class='message " . ($tipo == "error" ? "error" : "") . "'>$mensaje</h1>";
        }
        ?>
        <form action="" method="post">
            <input type="hidden" name="usu" value="<?php echo isset($usua) ? $usua : ''; ?>">
            <button type="submit" name="eliminar">Eliminar Usuario</button>
            <button type="submit" name="editar">Editar Usuario</button>
        </form>
        <form action="index.php" method="post">
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
