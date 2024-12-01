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

    // Verificar si el carrito tiene elementos
    if (isset($_SESSION['cart'])) {
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
    }
    return $carrito;
}

// Función para hacer la solicitud POST a la API externa
function llamarAPIExterna($endpoint, $data) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint, // URL de la API
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true, // Usamos POST
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'], // Cabecera JSON
        CURLOPT_POSTFIELDS => json_encode($data), // Enviar datos en formato JSON
    ]);

    $response = curl_exec($curl);
    if(curl_errno($curl)) {
        echo 'Error en la llamada API: ' . curl_error($curl);
    }
    curl_close($curl);

    return json_decode($response, true); // Suponiendo que la API devuelve JSON
}

// Definir los datos a enviar a la API
$API_ENDPOINT = "https://magicloops.dev/api/loop/run/5b1ae216-db3d-4ce8-835a-f904413ea70c";
$data = [
    'input' => 'I love Magic Loops!',
    'PARSED_DATA_OUTPUT' => $PARSED_DATA_OUTPUT ?? '', // Asigna las variables según corresponda
    'EMAIL_CONTENT_OUTPUT' => $EMAIL_CONTENT_OUTPUT ?? '',
    'API_NOTIFY_OUTPUT' => $API_NOTIFY_OUTPUT ?? '',
    'EMAIL_BLOCK_OUTPUT' => $EMAIL_BLOCK_OUTPUT ?? ''
];

// Ejemplo de llamada a una API externa con datos
$api_response = llamarAPIExterna($API_ENDPOINT, $data);

// Obtener el carrito actual
$carrito = obtenerDetallesCarrito();

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

// Calcular el total y total de productos
$total = calcularTotal($carrito);
$total_productos = calcularTotalProductos($carrito);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TernuVen - Confirmar Compra</title>
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
    <h1>TernuVen - Confirmar Compra</h1>

    <!-- Mostrar la respuesta de la API -->
    <?php if ($api_response && isset($api_response['mensaje'])): ?>
        <p><strong>Estado de la compra:</strong> <?php echo htmlspecialchars($api_response['mensaje']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID Producto</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
        </tr>
        <?php if (empty($carrito)): ?>
            <tr>
                <td colspan="5">No hay productos en el carrito.</td>
            </tr>
        <?php else: ?>
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
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
            <tr>
                <td colspan="4"><strong>Total de Productos</strong></td>
                <td><strong><?php echo $total_productos; ?></strong></td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Formularios existentes -->
    <form method="post" action="generar_pdf.php">
        <input type="submit" name="pdf" value="Generar PDF" class="btn btn-primary">
    </form>
    
    <form method="post" action="enviar_correo.php">
        <label for="correo">Correo electrónico:</label>
        <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required>
        
        <br>
        <input type="submit" value="Enviar Correo" class="btn btn-primary">
    </form>

    <form method="post" action="generar_ticket.php">
        <input type="submit" name="ticket" value="Generar Ticket" class="btn btn-primary">
    </form>
</body>
</html>
