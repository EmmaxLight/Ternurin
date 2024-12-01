<?php
// Conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=tutorias', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}

// Función para obtener las ventas y sumarlas en un rango de fechas
function obtenerVentasPorRango($fechaInicio, $fechaFin) {
    global $pdo; // Usamos la conexión global

    // Consulta SQL para obtener las ventas en el rango de fechas y calcular la suma total
    $query = "SELECT fecha, total FROM ventas WHERE fecha BETWEEN :fechaInicio AND :fechaFin";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':fechaFin', $fechaFin);
    $stmt->execute();

    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular la suma total
    $sumaTotal = 0;
    foreach ($ventas as $venta) {
        $sumaTotal += $venta['total'];
    }

    return [
        'ventas' => $ventas,
        'sumaTotal' => $sumaTotal
    ];
}

// Variables para almacenar resultados
$resultado = null;
$fechaInicio = '';
$fechaFin = '';

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener las fechas ingresadas por el usuario
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    // Obtener las ventas en el rango de fechas especificado
    $resultado = obtenerVentasPorRango($fechaInicio, $fechaFin);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://statics.uniradioinforma.com/2024/01/crop/65af0e02cd16f__940x940.webp');
            background-size: cover;
            background-position: center;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }
        h1 {
            font-size: 50px;
            color: #333;
            background-color: rgba(255, 255, 255, 0.8); /* Fondo semitransparente para mejorar la legibilidad */
            padding: 10px;
            border-radius: 5px;
        }
        form {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo semitransparente */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 400px;
            width: 100%;
        }
        label {
            font-weight: bold;
        }
        input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }
        button {
            background-color: #00FF00;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .ventas {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo semitransparente */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        .venta-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .venta-item:last-child {
            border-bottom: none;
        }
        .boton {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            color: #fff;
            background-color: #00FF00;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .boton:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
   
    
    <!-- Formulario para ingresar las fechas de inicio y fin -->
    <form method="post" action="">
        <label for="fechaInicio">Fecha de Inicio:</label>
        <input type="date" id="fechaInicio" name="fechaInicio" value="<?php echo htmlspecialchars($fechaInicio); ?>" required>
        
        <label for="fechaFin">Fecha de Fin:</label>
        <input type="date" id="fechaFin" name="fechaFin" value="<?php echo htmlspecialchars($fechaFin); ?>" required>
        
        <button type="submit">verificar</button>
    </form>

    <?php if ($resultado): ?>
        <div class="ventas">
            <h2>Ventas del <?php echo htmlspecialchars($fechaInicio); ?> al <?php echo htmlspecialchars($fechaFin); ?>:</h2>
            <?php if (!empty($resultado['ventas'])): ?>
                <?php foreach ($resultado['ventas'] as $venta): ?>
                    <div class="venta-item">
                        <p>Fecha: <?php echo htmlspecialchars($venta['fecha']); ?> - Total: $<?php echo htmlspecialchars($venta['total']); ?></p>
                    </div>
                <?php endforeach; ?>
                <h3>Suma total de ventas: $<?php echo htmlspecialchars($resultado['sumaTotal']); ?></h3>
            <?php else: ?>
                <p>No se encontraron ventas en el rango de fechas especificado.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div>
        <a href="comprar.php" class="boton">Atrás</a>
    </div>
</body>
</html>
