<?php
include './../includes/config.php';

// Manejo de la eliminación y actualización de productos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $id_eliminar = $_POST['id_eliminar'];
        $query = "DELETE FROM productos WHERE id='$id_eliminar'";
        mysqli_query($conexion, $query);
        echo json_encode(['status' => 'success']);
        exit;
    }

    if (isset($_POST['editProduct'])) {
        $id = $_POST['editProduct'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $existencias = $_POST['existencias'];

        // Manejo de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $ruta_imagen = 'ruta/donde/guardar/imagenes/' . $_FILES['imagen']['name'];
            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen);
            $query = "UPDATE productos SET nombre='$nombre', precio='$precio', existencias='$existencias', imagen='$ruta_imagen' WHERE id='$id'";
        } else {
            $query = "UPDATE productos SET nombre='$nombre', precio='$precio', existencias='$existencias' WHERE id='$id'";
        }

        mysqli_query($conexion, $query);
        echo json_encode(['status' => 'success', 'id' => $id, 'nombre' => $nombre, 'precio' => $precio, 'existencias' => $existencias, 'imagen' => $ruta_imagen ?? null]);
        exit;
    }
}

// Consulta para obtener productos
$query = "SELECT * FROM productos";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('https://motionbgs.com/media/3256/astronaut-skeleton-in-space.jpg');
            background-size: cover;
            background-position: center;
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
            height: 100%;
        }

        .site-title {
            font-size: 1.5em;
            margin: 0;
            margin-left: 20px;
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
            padding: 8px 32px;
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
        }

        h1 {
            text-align: center;
            margin-top: 90px; /* Ajustar para evitar el encabezado fijo y moverlo más abajo */
            color: white;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 2;
            position: relative;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            color: black; /* Color del texto en la tabla */
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #editForm {
            display: none;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            width: 80%;
            z-index: 2;
            position: relative;
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
        <a href="usuarios.php">Usuarios</a>
        <a href="?page=productos">Productos</a>
        <a href="?page=ventas">Ventas</a>
        <a href="destruir.php">Cerrar Sesión</a>
    </div>

    <div class="overlay"></div>

    <h1>Gestión de Productos</h1>

    <table id="productTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr id="product-<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td><?php echo $row['existencias']; ?></td>
                    <td><img src="<?php echo $row['imagen']; ?>" alt="Imagen del producto" style="width: 50px; height: 50px;"></td>
                    <td>
                        <button onclick="editProduct('<?php echo $row['id']; ?>', '<?php echo $row['imagen']; ?>')">Editar</button>
                        <button onclick="deleteProduct('<?php echo $row['id']; ?>')">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="editForm">
        <h2>Editar Producto</h2>
        <form id="productEditForm" enctype="multipart/form-data" onsubmit="event.preventDefault(); saveChanges();">
            <input type="hidden" id="editProduct" name="editProduct">
            <label for="editNombre">Nombre:</label>
            <input type="text" id="editNombre" name="nombre" required>
            <label for="editPrecio">Precio:</label>
            <input type="number" id="editPrecio" name="precio" required>
            <label for="editExistencias">Existencias:</label>
            <input type="number" id="editExistencias" name="existencias" required>
            <label for="editImagen">Imagen:</label>
            <input type="file" id="editImagen" name="imagen" accept="image/*">
            <img id="previewImage" style="width: 100px; display: none;">
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }

        function editProduct(id, imagen) {
            const row = document.getElementById("product-" + id);
            const nombre = row.cells[1].innerText;
            const precio = row.cells[2].innerText;
            const existencias = row.cells[3].innerText;

            document.getElementById("editProduct").value = id;
            document.getElementById("editNombre").value = nombre;
            document.getElementById("editPrecio").value = precio;
            document.getElementById("editExistencias").value = existencias;

            const previewImage = document.getElementById("previewImage");
            previewImage.src = imagen;
            previewImage.style.display = "block";

            document.getElementById("editForm").style.display = "block";
        }

        function deleteProduct(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    const response = JSON.parse(this.responseText);
                    if (response.status === 'success') {
                        document.getElementById("product-" + id).remove(); // Remover el producto de la tabla
                    }
                };
                xhr.send("eliminar=true&id_eliminar=" + id);
            }
        }

        function saveChanges() {
            const form = document.getElementById("productEditForm");
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.onload = function () {
                const response = JSON.parse(this.responseText);
                if (response.status === 'success') {
                    // Actualizar la fila de la tabla con los nuevos datos
                    const row = document.getElementById("product-" + response.id);
                    row.cells[1].innerText = response.nombre;
                    row.cells[2].innerText = response.precio;
                    row.cells[3].innerText = response.existencias;
                    row.cells[4].innerHTML = '<img src="' + response.imagen + '" style="width: 90px; height: 90px;">';
                    document.getElementById("editForm").style.display = "none"; // Ocultar el formulario
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>