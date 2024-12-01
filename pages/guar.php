<?php
session_start();
include './../includes/config.php'; // Conectar a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos</title>
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
            flex-direction: column;
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
            background-color: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-out 1s both;
            backdrop-filter: blur(5px);
            position: relative;
            width: 400px;
            z-index: 2;
            text-align: center;
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
        h2, p {
            color: white;
        }
        a {
            text-decoration: none;
        }
        img {
            border-radius: 8px;
            margin-top: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #003DA5;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
            width: calc(100% - 40px); /* Ancho igual para ambos botones */
            max-width: 360px; /* Ajustar ancho máximo si es necesario */
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px 0; /* Margen para separación */
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="form-container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cantidad = $_POST['cantidad'];
            $imagen = $_FILES['imagen'];

            // Verificar que se haya subido la imagen sin errores
            if ($imagen['error'] == 0) {
                $nombre_imagen = $imagen['name'];
                $ruta_temporal = $imagen['tmp_name'];
                $carpeta_destino = 'imagenes/'; // Carpeta donde se guardará la imagen

                // Crear la carpeta si no existe
                if (!is_dir($carpeta_destino)) {
                    mkdir($carpeta_destino, 0777, true);
                }

                // Mover la imagen a la carpeta de destino
                if (move_uploaded_file($ruta_temporal, $carpeta_destino . $nombre_imagen)) {
                    // Insertar datos en la base de datos
                    $query = "INSERT INTO productos (nombre, precio, existencias, imagen) VALUES ('$nombre', '$precio', '$cantidad', '$nombre_imagen')";
                    if (mysqli_query($conexion, $query)) {
                        echo "<h2>Producto registrado con éxito.</h2>";
                        echo "<p>Nombre: $nombre</p>";
                        echo "<p>Precio: $$precio</p>";
                        echo "<p>Cantidad: $cantidad</p>";
                        echo "<img src='$carpeta_destino$nombre_imagen' style='max-width: 300px; max-height: 300px;'>";
                        echo '<br><a href="http://localhost/Ternu/pages/admin.php?page=productos"><button>Volver a la página de productos</button></a>';
                        // Cambiar aquí para que redirija a vertodos.php
                        echo '<br><a href="http://localhost/Ternu/pages/vertodos.php"><button>Ver todos los productos</button></a>';
                    } else {
                        echo "Error al registrar el producto: " . mysqli_error($conexion);
                    }
                } else {
                    echo "Error al guardar la imagen.";
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Mostrar formulario de registro de productos
            ?>
            <h2>Registrar Producto</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="nombre" placeholder="Nombre del producto" required>
                <input type="number" name="precio" placeholder="Precio" required>
                <input type="number" name="cantidad" placeholder="Cantidad" required>
                <input type="file" name="imagen" required>
                <input type="submit" value="Registrar Producto">
            </form>
            <?php
        }
        ?>
    </div>

    <?php
    // Mostrar tabla de productos si se solicita
    if (isset($_GET['page']) && $_GET['page'] == 'ver_todos_productos') {
        // Consulta para obtener productos
        $query = "SELECT * FROM productos";
        $result = mysqli_query($conexion, $query);
        echo "<h2>Productos Registrados</h2>";
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nombre</th>';
        echo '<th>Precio</th>';
        echo '<th>Existencias</th>';
        echo '<th>Imagen</th>';
        echo '<th>Acciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($producto = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $producto['id'] . '</td>';
            echo '<td>' . $producto['nombre'] . '</td>';
            echo '<td>$' . $producto['precio'] . '</td>';
            echo '<td>' . $producto['existencias'] . '</td>';
            echo '<td><img src="imagenes/' . $producto['imagen'] . '" style="max-width: 100px; max-height: 100px;"></td>';
            echo '<td>';
            echo '<form method="POST" style="display:inline;">'; // Formulario para eliminar producto
            echo '<input type="hidden" name="id_eliminar" value="' . $producto['id'] . '">';
            echo '<input type="submit" name="eliminar" value="Eliminar" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este producto?\');">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    // Manejo de eliminación de productos
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
        $id_eliminar = $_POST['id_eliminar'];
        $query = "DELETE FROM productos WHERE id='$id_eliminar'";
        mysqli_query($conexion, $query);
        header("Location: http://localhost/Ternu/pages/admin.php?page=productos"); // Redirigir después de eliminar
        exit;
    }
    ?>
</body>
</html>