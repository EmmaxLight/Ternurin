<?php
// Conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=tutorias', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Error de conexión: ' . $e->getMessage()]));
}

// Validar la solicitud y guardar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar los datos enviados en formato JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['info'])) {
        $info = $data['info'];

        // Insertar en la tabla helado_faces
        $stmt = $pdo->prepare('INSERT INTO helado_faces (info) VALUES (:info)');
        $stmt->bindParam(':info', $info);

        if ($stmt->execute()) {
            echo json_encode(['success' => 'Datos guardados exitosamente.']);
        } else {
            echo json_encode(['error' => 'Error al guardar los datos.']);
        }
    } else {
        echo json_encode(['error' => 'Información faltante.']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
