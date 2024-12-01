<?php
session_start();
include "./../includes/config.php";

$usuario = $_POST['usuari'];
$contrasena = $_POST['contra'];

$peticion = "SELECT * FROM usuariosadmin WHERE usuari = ?";
$stmt = $conexion->prepare($peticion);
$stmt->bind_param('s', $usuario);
$stmt->execute();
$respuesta = $stmt->get_result();
$contador = 0;

while($fila = $respuesta->fetch_assoc()){
    if ($fila['contra'] === $contrasena) { // Aquí debes usar password_verify si usas contraseñas hasheadas
        $contador++;
        $_SESSION['modificar_id'] = $fila['id']; // Guardar el ID del usuario validado
    }
}

if($contador > 0){
    // Si la validación es correcta, redirige a ModiUsuario.php
    header("Location: ModiUsuario.php");
} else {
    // Si no, muestra un mensaje de error
    header("Location: superUsuario.php?mensaje=error");
}
?>