<?php
session_start();
include './../includes/config.php';

// Inicializar el carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Función para obtener los detalles del carrito
function obtenerDetallesCarrito() {
    global $conexion;
    $carrito = [];
    
    foreach ($_SESSION['cart'] as $producto_id => $cantidad) {
        // Preparar la consulta SQL
        $stmt = $conexion->prepare("SELECT id, nombre, precio FROM productos WHERE id = ?");
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            // Agregar la cantidad seleccionada al array
            $fila['cantidad'] = $cantidad;
            $carrito[] = $fila;
        }
        $stmt->close();
    }
    return $carrito;
}

// Función para calcular el total del carrito
function calcularTotal($carrito) {
    return array_sum(array_map(function($item) {
        return $item['precio'] * $item['cantidad'];
    }, $carrito));
}

// Función para calcular el número total de productos seleccionados
function calcularTotalProductos($carrito) {
    return array_sum(array_map(function($item) {
        return $item['cantidad'];
    }, $carrito));
}

$mensaje = '';
$mostrarOpciones = false;

// Procesar la compra
if (isset($_POST['comprar'])) {
    $carrito = obtenerDetallesCarrito();
    $total = calcularTotal($carrito);
    $total_productos = calcularTotalProductos($carrito);

    // Simular el procesamiento del pago
    $numero_orden = uniqid('TernuVen-');
    
    // Guardar la orden en la base de datos
$fecha_actual = date('Y-m-d');
$query = "INSERT INTO ventas (fecha, total) VALUES (?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param('sd', $fecha_actual, $total);
$stmt->execute();
$stmt->close();

    // Guardar la orden en la base de datos
$fecha_actual = date('Y-m-d');
$query = "INSERT INTO ventas (fecha, total) VALUES (?, ?)";
$stmt = $conexion->prepare($query);
$stmt->bind_param('sd', $fecha_actual, $total);
$stmt->execute();
$stmt->close();


    // Limpiar el carrito después de la compra
    $_SESSION['cart'] = [];
} 

// Obtener el carrito actual
$carrito = obtenerDetallesCarrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TernuVen - Carrito de Compras</title>
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
        background-image: url('https://i.pinimg.com/originals/1a/85/aa/1a85aac66c257cbb46fb716b300c0559.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        animation: fadeIn 2s ease-in-out;
    }
    
    /* Contenedor para centrar la tabla y los botones en columna */
    .content-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Estilo para la tabla */
    table {
        width: 80%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semitransparente */
        backdrop-filter: blur(5px); /* Desenfoque para mejorar legibilidad */
        border-radius: 10px;
        overflow: hidden;
    }
    
    th, td {
        padding: 15px;
        text-align: left;
        border: 1px solid #ddd;
        color: #333; /* Texto en color oscuro para contraste */
    }
    
    th {
        background-color: rgba(0, 0, 0, 0.7); /* Encabezados oscuros semitransparentes */
        color: white;
    }
    
    /* Estilos para los botones */
    .button-container {
        display: flex;
        gap: 10px; /* Espacio entre botones */
        margin-top: 20px; /* Espacio para separar botones de la tabla */
    }

    .btn {
        padding: 10px 20px;
        background-color: #0b3d91; /* Azul similar al logo de la NASA */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }
    
    .btn-primary {
        background-color: #0b3d91; /* Azul NASA */
    }
    
    .btn-secondary {
        background-color: #0b3d91; /* Azul NASA */
    }

    .btn:hover {
        background-color: #0a347a; /* Azul más oscuro en hover */
        opacity: 0.9;
    }
</style>
</head>
<body>
    <h1>TernuVen - Carrito de Compras</h1>
    
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if ($mostrarOpciones): ?>
        <h3>Elige una opción:</h3>
        <a href="generar_pdf.php" target="_blank" class="btn">Descargar PDF</a><br>
        <a href="generar_ticket.php" target="_blank" class="btn">Imprimir Ticket</a><br>
        <a href="enviar_email.php" class="btn">Enviar por Email</a>
    <?php else: ?>
        <?php if (empty($carrito)): ?>
            <p>Tu carrito está vacío</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($carrito as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>$<?php echo number_format(calcularTotal($carrito), 2); ?></strong></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Total de Productos</strong></td>
                    <td><strong><?php echo calcularTotalProductos($carrito); ?></strong></td>
                </tr>
            </table>
            <form method="post" action="">
                <input type="submit" name="comprar" value="Confirmar" class="btn btn-primary">
            </form>
            <button onclick="window.location.href='comprardos.php'" class="btn btn-secondary">
    Más opciones
</button>


        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
