<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos</title>
    <style>
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
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white; /* Cambiado a blanco */
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Asegura que el padding no afecta el ancho total */
        }
        input[type="submit"] {
            background-color: #003DA5;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056e0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro de Productos</h2>
        <form action="guar.php" method="post" enctype="multipart/form-data"> <!-- Cambiado a guar.php -->
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre del producto" required>
            <input type="number" name="precio" id="precio" step="0.01" min="0" placeholder="Ingrese el precio" required>
            <input type="number" name="cantidad" id="cantidad" min="0" placeholder="Ingrese la cantidad" required>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>
            <input type="submit" value="Registrar Producto">
        </form>
    </div>
</body>
</html>
