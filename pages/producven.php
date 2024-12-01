<?php
session_start();
include './../includes/config.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Manejar acciones de agregar/quitar
if (isset($_POST['action']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']); // Validar entrada
    $action = $_POST['action'];

    // Obtener el producto de la base de datos
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        if ($action === 'add' && $product['existencias'] > 0) {
            $product['existencias']--;
            $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;

            // Actualizar existencias en la base de datos
            $update_query = "UPDATE productos SET existencias = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conexion, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ii", $product['existencias'], $product_id);
            mysqli_stmt_execute($update_stmt);
        } elseif ($action === 'remove' && isset($_SESSION['cart'][$product_id]) && $_SESSION['cart'][$product_id] > 0) {
            $product['existencias']++;
            $_SESSION['cart'][$product_id]--;

            // Actualizar existencias en la base de datos
            $update_query = "UPDATE productos SET existencias = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conexion, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ii", $product['existencias'], $product_id);
            mysqli_stmt_execute($update_stmt);

            if ($_SESSION['cart'][$product_id] == 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    
    // Si es una solicitud AJAX, devolver solo los datos actualizados
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $query = "SELECT * FROM productos";
        $result = mysqli_query($conexion, $query);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        
        // Agregar la información del carrito a cada producto
        foreach ($products as &$product) {
            $product['cart'] = $_SESSION['cart'][$product['id']] ?? 0;
        }
        
        echo json_encode([
            'products' => $products,
            'cart' => $_SESSION['cart'],
            'total_items' => array_sum($_SESSION['cart'])
        ]);
        exit;
    }
}


// Calcular total de items en el carrito
$total_items = array_sum($_SESSION['cart']);

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
            justify-content: flex-start;
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
            margin-left: 40px;
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
            margin-top: 120px;
            color: white;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 2;
            position: relative;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            color: black;
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

        .cart-button {
            position: fixed;
            top: 15px;
            right: 40px;
            color: white;
            text-decoration: none;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .cart-count {
            background-color: #ff4d4d;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -8px;
            right: -8px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons button {
            padding: 5px 10px;
        }
    

        /* Estilo para la imagen de producto */
        img {
            max-width: 50px;
            height: auto;
        }

        .graph-button {
    background-color: transparent; /* Botón transparente */
    color: white; /* Letras blancas */
    border: 2px solid white; /* Borde blanco */
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
    margin: 20px auto;
    display: block;
    text-align: center;
    font-size: 18px; /* Tamaño de letra aumentado */
    transition: background-color 0.3s ease; /* Efecto suave al pasar el ratón */
}

.graph-button:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Cambia el fondo al pasar el ratón */
}


    </style>
</head>
<body>
    <header>
        <button class="menu-btn" onclick="openNav()">☰</button>
        <h1 class="site-title">TernuVen</h1>
        <a href="comprar.php" class="cart-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M3 3h18l-1.68 8.47a2 2 0 0 1-1.96 1.53H8.06a2 2 0 0 1-1.96-1.53L3 3"></path>
            </svg>
            <span class="cart-count"><?= $total_items ?></span>
        </a>
    </header>

    <div id="mySidenav" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="?page=inicio">Inicio</a>
        <a href="#">Productos</a>
        <a href="#">Mi Perfil</a>
        <a href="destruir.php">Cerrar Sesión</a>
    </div>

    <h1>Productos</h1>
    <div class="product-container">
        <table>
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
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['precio'] ?></td>
                        <td><?= $row['existencias'] ?></td>
                        <td><img src='../pages/imagenes/<?= $row["imagen"] ?>' width='50'></td>
                        <td class="action-buttons">
                            <button class="btn-remove" onclick="updateCart(<?= $row['id']; ?>, 'remove')">-</button>
                            <span class="product-quantity"><?= $_SESSION['cart'][$row['id']] ?? 0; ?></span>
                            <button class="btn-add" onclick="updateCart(<?= $row['id']; ?>, 'add')" <?= $row['existencias'] === 0 ? 'disabled' : ''; ?>>+</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

        function updateCart(productId, action) {
            // Hacer la solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Actualizar la página con la nueva información del carrito
                    location.reload();
                }
            };
            xhr.send('product_id=' + productId + '&action=' + action);
        }
    </script>
    <form action ="grafica.php" method="post">
        <button type="submit" class="graph-button">Grafica</button>

    </form>
</body>
</html>
