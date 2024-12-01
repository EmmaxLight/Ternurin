<?php
session_start();
include './../includes/config.php';

// Verificar la existencia del archivo FPDF
if (!file_exists('./../includes/fpdf/fpdf.php')) {
    die("El archivo fpdf.php no se encontró en la ruta especificada.");
}
include './../includes/fpdf/fpdf.php'; // Asegúrate de incluir el archivo fpdf.php

// Establecer la zona horaria a la de tu computadora (opcional)
date_default_timezone_set('America/Mexico_City'); // Cambia esto según tu ubicación

// Función para obtener los detalles del carrito
function obtenerDetallesCarrito() {
    global $conexion;
    $carrito = [];

    // Verificar si el carrito tiene elementos
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $producto_id => $cantidad) {
            // Preparar la consulta SQL
            $stmt = $conexion->prepare("SELECT id, nombre, precio, imagen FROM productos WHERE id = ?");
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

// Obtener el carrito actual
$carrito = obtenerDetallesCarrito();

// Crear un nuevo PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Título
$pdf->Cell(0, 10, 'TernuVen - Detalles de la Compra', 0, 1, 'C');
$pdf->Ln(5); // Salto de línea

// Fecha y Hora
$fecha_hora = date('d/m/Y H:i:s');
$pdf->Cell(0, 10, 'Fecha y Hora: ' . $fecha_hora, 0, 1, 'C');
$pdf->Ln(5); // Salto de línea

// Ajustar posición para centrar la tabla
$pdf->SetX(10); // Ajustar posición desde la izquierda para centrar la tabla

// Encabezado de la tabla
$pdf->Cell(30, 10, 'ID Producto', 1, 0, 'C');
$pdf->Cell(80, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
$pdf->Cell(30, 10, 'Precio Unitario', 1, 0, 'C');
$pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C');

// Datos de la tabla
foreach ($carrito as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];

    // Añadir las celdas de datos
    $pdf->SetX(10); // Asegúrate de que la tabla se alinee a la izquierda
    $pdf->Cell(30, 10, $item['id'], 1, 0, 'C');
    $pdf->Cell(80, 10, $item['nombre'], 1, 0, 'C');
    $pdf->Cell(30, 10, $item['cantidad'], 1, 0, 'C');
    $pdf->Cell(30, 10, '$' . number_format($item['precio'], 2), 1, 0, 'C');
    $pdf->Cell(30, 10, '$' . number_format($subtotal, 2), 1, 1, 'C'); // Alineación al centro
}

// Total
$total = array_sum(array_map(function($item) {
    return $item['precio'] * $item['cantidad'];
}, $carrito));

// Espacio para el total
$pdf->SetX(10); // Ajustar posición para el total
$pdf->Cell(140, 10, 'Total:', 0, 0, 'C'); // Sin borde
$pdf->Cell(30, 10, '$' . number_format($total, 2), 0, 1, 'C'); // Sin borde, alineado a la derecha
$pdf->Ln(10); // Salto de línea

// Añadir imágenes de los productos centradas debajo de la tabla
$pdf->Ln(10); // Salto de línea antes de las imágenes
foreach ($carrito as $item) {
    // Agregar la imagen del producto
    $imagen_path = './../pages/imagenes/' . $item['imagen'];
    // Verificar si la imagen existe
    if (file_exists($imagen_path)) {
        // Centrar la imagen
        $pdf->Image($imagen_path, 75, $pdf->GetY(), 60, 60); // Ajusta el tamaño de la imagen según sea necesario
        $pdf->Ln(60); // Salto de línea después de la imagen
    } else {
        // Si la imagen no existe, agregar un texto alternativo
        $pdf->Cell(50, 10, 'Sin Imagen', 1, 1, 'C');
        $pdf->Ln(10); // Salto de línea
    }
}

// Salida del PDF
$pdf->Output('D', 'ticket_compra.pdf'); // El nombre del archivo que se descargará
?>
