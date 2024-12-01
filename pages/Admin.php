<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empleados</title>
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
            background-image: url('https://motionbgs.com/media/3256/astronaut-skeleton-in-space.jpg');
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
            background-color: rgba(255, 255, 255, 0);
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
            border-color: blue;
        }
        input[type="submit"] {
            background-color: #003DA5;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        h1 {
            text-align: center;
            margin-bottom: 15px;
            color: white;
        }
        header {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.75em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        .menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            padding: 0 20px;
            display: flex;
            align-items: center;
            height: 100%;
        }
        .site-title {
            font-size: 1.5em;
            margin: 0;
            margin-right: auto;
            margin-left: 20px;
            color: white;
        }
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1001;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.7);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
            backdrop-filter: blur(5px);
        }
        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 18px;
            color: #f1f1f1;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #003DA5;
        }
        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
        main {
            padding: 1em;
            margin-top: 80px;
        }
        footer {
            position: fixed;
            bottom: 10px;
            left: 10px;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <button class="menu-btn" onclick="openNav()">☰</button>
        <h1 class="site-title">TernuVen</h1>
    </header>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="?page=inicio">Inicio</a>
        <a href="usuarios.php">Usuarios</a> <!-- Cambiado a usuarios.php -->
        <a href="?page=productos">Productos</a>
        <a href="ventas.php">Ventas</a>
        <a href="helado.php">Helado</a>
        <a href="destruir.php">Cerrar Sesión</a>
    </div>

    <main id="main">
        <div class="overlay"></div>
        <div class="form-container">
            <?php
            // Si se presiona el botón de 'Productos', se carga la página de productos.
            if (isset($_GET['page']) && $_GET['page'] == 'productos') {
                include('productos.php');
            } else {
                // Mostrar el formulario de registro por defecto.
                echo '
                <h1>Registro de Empleados</h1>
                <form action="registrar.php" method="post" enctype="multipart/form-data">
                    <input type="text" name="nom" placeholder="Nombre" required>
                    <input type="text" name="ap" placeholder="Apellido paterno" required>
                    <input type="text" name="am" placeholder="Apellido materno" required>
                    <input type="text" name="usu" placeholder="Usuario" required>
                    <input type="password" name="con" placeholder="Contraseña" required>
                    <input type="text" name="ti" placeholder="Tipo de Usuario" required>
                    <input type="submit" value="Registrar Usuario">
                </form>';
            }
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 TernuVen. Todos los derechos reservados.</p>
    </footer>

    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</body>
</html>
