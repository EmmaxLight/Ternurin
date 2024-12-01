<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $correo_destino = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
    
    // Validar el correo
    if (!filter_var($correo_destino, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico no válido.");
    }

    // Generar el PDF
    session_start();
    include './../includes/config.php';

    // Verificar la existencia del archivo FPDF
    if (!file_exists('./../includes/fpdf/fpdf.php')) {
        die("El archivo fpdf.php no se encontró en la ruta especificada.");
    }
    include './../includes/fpdf/fpdf.php';

    // Establecer la zona horaria
    date_default_timezone_set('America/Mexico_City');

    // Función para obtener los detalles del carrito
    function obtenerDetallesCarrito() {
        global $conexion;
        $carrito = [];
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $producto_id => $cantidad) {
                $stmt = $conexion->prepare("SELECT id, nombre, precio, imagen FROM productos WHERE id = ?");
                $stmt->bind_param("i", $producto_id);
                $stmt->execute();
                $resultado = $stmt->get_result();
                if ($fila = $resultado->fetch_assoc()) {
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
    $pdf->Cell(0, 10, 'TernuVen - Detalles de la Compra', 0, 1, 'C');
    $pdf->Ln(5);
    $fecha_hora = date('d/m/Y H:i:s');
    $pdf->Cell(0, 10, 'Fecha y Hora: ' . $fecha_hora, 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetX(10);
    $pdf->Cell(30, 10, 'ID Producto', 1, 0, 'C');
    $pdf->Cell(80, 10, 'Nombre', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Precio Unitario', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Subtotal', 1, 1, 'C');

    foreach ($carrito as $item) {
        $subtotal = $item['precio'] * $item['cantidad'];
        $pdf->SetX(10);
        $pdf->Cell(30, 10, $item['id'], 1, 0, 'C');
        $pdf->Cell(80, 10, $item['nombre'], 1, 0, 'C');
        $pdf->Cell(30, 10, $item['cantidad'], 1, 0, 'C');
        $pdf->Cell(30, 10, '$' . number_format($item['precio'], 2), 1, 0, 'C');
        $pdf->Cell(30, 10, '$' . number_format($subtotal, 2), 1, 1, 'C');
    }

    $total = array_sum(array_map(function($item) {
        return $item['precio'] * $item['cantidad'];
    }, $carrito));

    $pdf->SetX(10);
    $pdf->Cell(140, 10, 'Total:', 0, 0, 'C');
    $pdf->Cell(30, 10, '$' . number_format($total, 2), 0, 1, 'C');
    $pdf->Ln(10);

    // Añadir imágenes de los productos centradas debajo de la tabla
    foreach ($carrito as $item) {
        $imagen_path = './../pages/imagenes/' . $item['imagen'];
        if (file_exists($imagen_path)) {
            $pdf->Image($imagen_path, 75, $pdf->GetY(), 60, 60);
            $pdf->Ln(60);
        } else {
            $pdf->Cell(50, 10, 'Sin Imagen', 1, 1, 'C');
            $pdf->Ln(10);
        }
    }

    // Guardar el PDF en un archivo temporal
    $pdf_nombre = 'ticket_compra.pdf';
    $pdf->Output('F', $pdf_nombre); // Guarda el PDF en un archivo en el servidor

    // Enviar el correo
    $asunto = "Archivo adjunto de TernuVen";
    $mensaje = "Adjunto encontrarás el archivo solicitado.";
    $boundary = md5(uniqid(time()));
    $headers = "From: emmanuellrvi9@gmail.com\r\n";
    $headers .= "Reply-To: emmanuellrvi9@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // Crear cuerpo del mensaje
    $cuerpo = "--$boundary\r\n";
    $cuerpo .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $cuerpo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $cuerpo .= $mensaje . "\r\n\r\n";

    // Adjuntar el archivo PDF
    $contenido_archivo_pdf = file_get_contents($pdf_nombre);
    $contenido_archivo_pdf = chunk_split(base64_encode($contenido_archivo_pdf));

    $cuerpo .= "--$boundary\r\n";
    $cuerpo .= "Content-Type: application/pdf; name=\"$pdf_nombre\"\r\n";
    $cuerpo .= "Content-Disposition: attachment; filename=\"$pdf_nombre\"\r\n";
    $cuerpo .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $cuerpo .= $contenido_archivo_pdf . "\r\n\r\n";
    $cuerpo .= "--$boundary--";

    // Enviar el correo
    if (mail($correo_destino, $asunto, $cuerpo, $headers)) {
        echo "Correo enviado con éxito.";
    } else {
        echo "Error al enviar el correo. Verifica la configuración del servidor.";
    }

    // Eliminar el archivo PDF temporal después de enviar el correo
    unlink($pdf_nombre);
} else {
    echo "Método de solicitud no válido.";
}
?>
