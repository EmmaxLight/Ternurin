<?php
include './../includes/config.php';

// Manejo de la eliminación y actualización de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar'])) {
        $usuari_eliminar = $_POST['usuari_eliminar'];
        $query = "DELETE FROM usuariosadmin WHERE usuari='$usuari_eliminar'";
        mysqli_query($conexion, $query);
        echo json_encode(['status' => 'success']);
        exit;
    }

    if (isset($_POST['editUser'])) {
        $usuari = $_POST['editUser'];
        $nombre = $_POST['nombre'];
        $apellido_pa = $_POST['apellido_pa'];
        $apellido_ma = $_POST['apellido_ma'];
        $query = "UPDATE usuariosadmin SET nombre='$nombre', ape
        llido_pa='$apellido_pa', apellido_ma='$apellido_ma' WHERE usuari='$usuari'";
        mysqli_query($conexion, $query);
        echo json_encode(['status' => 'success']);
        exit;
    }
}

// Consulta para obtener usuarios
$query = "SELECT * FROM usuariosadmin";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* Fondo con imagen de esqueleto astronauta */
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
            flex-direction: column; /* Añadido para apilar el contenido verticalmente */
        }

        h1 {
            color: white;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 50px 0; /* Ajustado para bajar la tabla */
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
            margin: 20px 0;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            width: 80%;
        }

        /* Estilos del menú */
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
    </style>
</head>
<body>

    <header>
        <button class="menu-btn" onclick="openNav()">☰</button>
        <h1 class="site-title">TernuVen</h1>
    </header>

    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
        <a href="http://localhost/Ternu/pages/admin.php">Inicio</a>
        <a href="http://localhost/Ternu/pages/admin.php?page=productos">Productos</a>
        <a href="#">Mi Perfil</a>
        <a href="#">Cerrar Sesión</a>
    </div>

    <h1>Gestión de Usuarios</h1> <!-- Este texto se mantiene arriba de la tabla -->

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr id="user-<?php echo $row['usuari']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['apellido_pa']; ?></td>
                    <td><?php echo $row['apellido_ma']; ?></td>
                    <td><?php echo $row['usuari']; ?></td>
                    <td>
                        <button onclick="editUser('<?php echo $row['usuari']; ?>')">Editar Usuario</button>
                        <button onclick="deleteUser('<?php echo $row['usuari']; ?>')">Eliminar Usuario</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="editForm">
        <h2>Editar Usuario</h2>
        <form id="userEditForm" onsubmit="event.preventDefault(); saveChanges();">
            <input type="hidden" id="editUser" name="editUser">
            <label for="editNombre">Nombre:</label>
            <input type="text" id="editNombre" name="nombre" required>
            <label for="editApellidoPa">Apellido Paterno:</label>
            <input type="text" id="editApellidoPa" name="apellido_pa" required>
            <label for="editApellidoMa">Apellido Materno:</label>
            <input type="text" id="editApellidoMa" name="apellido_ma" required>
            <button type="submit" style="background-color: #007BFF;">Guardar Cambios</button>
        </form>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }

        function editUser(usuari) {
            const row = document.getElementById("user-" + usuari);
            const nombre = row.cells[1].innerText;
            const apellido_pa = row.cells[2].innerText;
            const apellido_ma = row.cells[3].innerText;

            document.getElementById("editUser").value = usuari;
            document.getElementById("editNombre").value = nombre;
            document.getElementById("editApellidoPa").value = apellido_pa;
            document.getElementById("editApellidoMa").value = apellido_ma;

            document.getElementById("editForm").style.display = "block";
        }

        function deleteUser(usuari) {
            if (confirm("¿Estás seguro de eliminar este usuario?")) {
                const formData = new FormData();
                formData.append('eliminar', true);
                formData.append('usuari_eliminar', usuari);

                fetch('', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById("user-" + usuari).remove();
                    }
                });
            }
        }

        function saveChanges() {
            const formData = new FormData(document.getElementById("userEditForm"));

            fetch('', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload(); // Recargar la página para reflejar los cambios
                }
            });
        }
    </script>

</body>
</html>

