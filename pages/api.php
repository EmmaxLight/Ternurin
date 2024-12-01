<?php
// Conexión a la base de datos
$pdo = new PDO('mysql:host=localhost;dbname=tutorias', 'root', '');

// Validar la solicitud
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['face_id'])) {
    $face_id = intval($_GET['face_id']);

    // Consultar la información de la base de datos
    $stmt = $pdo->prepare('SELECT info FROM helado_faces WHERE id = ?');
    $stmt->execute([$face_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['info' => $data['info']]);
    } else {
        echo json_encode(['error' => 'No se encontró información para esta cara.']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida.']);
}
?>