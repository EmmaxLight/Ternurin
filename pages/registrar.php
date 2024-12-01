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
            background-image: url('https://i.ytimg.com/vi/fQaJDrgw6KU/maxresdefault.jpg?sqp=-oaymwEmCIAKENAF8quKqQMa8AEB-AH-CYAC0AWKAgwIABABGGUgXChNMA8=&rs=AOn4CLCRdtOimXUDCnGgPP5xwITEsErMXg');
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
        input[type="submit"] {
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
        .error-message {
            margin-bottom: 15px;
            color: red;
            font-size: 1.5em; /* Tamaño igual al h1 */
            font-family: Arial, sans-serif; /* Mismo tipo de letra */
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="form-container">
        <?php
        $nombre = $_POST['nom'];
        $app = $_POST['ap'];
        $apm = $_POST['am'];
        $usua = $_POST['usu'];
        $contra = $_POST['con'];
        $tipo = $_POST['ti'];

        // Incluimos base de datos
        include "./../includes/config.php";
        $peticionx = "SELECT * FROM usuariosAdmin WHERE usuari ='$usua'";
        $respuestax = mysqli_query($conexion, $peticionx);
        
        if ($filax = mysqli_fetch_array($respuestax)){
            // El usuario ya existe
            echo "<p class='error-message'>El usuario ya existe.</p>";
        } else {
            $peticion = "INSERT INTO usuariosAdmin VALUES(NULL, '$nombre', '$app', '$apm', '$usua', '$contra',$tipo)";
            $respuesta = mysqli_query($conexion, $peticion);
            echo "<h1>Usuario Registrado con Éxito</h1>";
        }
        ?>
        <form action="admin.php" method="post"> <!-- Cambiado a admin.php -->
            <input type="submit" value="Regresar">
        </form>
    </div>
</body>
</html>
