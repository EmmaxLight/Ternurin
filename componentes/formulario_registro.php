<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
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
            background-image: url('https://s3.peing.net/t/uploads/user/icon/13927330/a463825c.jpeg');
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
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out 1s both;
            backdrop-filter: blur(5px);
            position: relative;
            width: 300px;
            z-index: 2;
        }

        input {
            display: block;
            margin: 10px auto;
            padding: 10px;
            width: 80%;
            transition: all 0.3s ease;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input:focus {
            transform: scale(1.05);
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
            border-color: purple;
        }

        input[type="submit"] {
            background-color: purple;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 48%;
        }

        .form-submit {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .register-link {
            color: purple;
            text-decoration: none;
            font-size: 14px;
            width: 48%;
            text-align: left;
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="form-container">
        <form action="comprobar.php" method="post">
            <h2>Iniciar Sesión</h2>
            <input type="email" name="usuario" placeholder="Correo electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <div class="form-submit">
                <a href="registroNormal.php" class="register-link">Registrate</a>
                <input type="submit" value="Iniciar Sesión">
            </div>
        </form>
    </div>
</body>
</html>

