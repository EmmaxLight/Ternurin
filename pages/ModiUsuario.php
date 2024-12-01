<h1>Haz Entrado super Usuario </h1></br>
<h1>Tienes permiso de modificar lo que necesites  </h1></br>

<?php
session_start();
if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 2 && isset($_SESSION['modificar_id'])){
?>

<!DOCTYPE html>   <!--indica la versión de HTML5 que se esta Usado -->
<html lang="es"> <!--indica el idioma en este caso español -->
<head>      <!--Define la meta-información -->
    <meta charset="UTF-8">   <!-- establece que los caracteres del documento-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--ajustar automáticamente el ancho de la ventana para dispositivos moviles -->
    <title> Inicio de sesión</title> <!--Establece un título a la página web -->
    <link rel="stylesheet" href="./../assets/css/bootstrap.css"> <!-- vincula la hoja de estilos CSS bootstrap.css-->
</head> <!--fin de la etiqueta de cierre de la Meta información -->

<body style="background-image: URL('./../assets/resources/amarillo.jpg');" width="20%">

<?php
        include "./../includes/config.php";
        $peticion = "SELECT * FROM usuariosadmin WHERE id = ?";
        $stmt = $conexion->prepare($peticion);
        $stmt->bind_param('i', $_SESSION['modificar_id']);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        
        if($fila = $respuesta->fetch_assoc()){
    ?>
    
<form action="actualizarempleado.php" method="post">

    <label for="">Nombre: </label><br><br>
    <input type="text" name="nombre" value="<?php echo $fila['nombre']?>"><br><br>

    <label for="">Apellido paterno</label></br>
    <input type="text" name="ap" value="<?php echo $fila['apellido_pa']?>"></br></br>

    <label for="">Apellido materno</label></br>
    <input type="text" name="am" value="<?php echo $fila['apellido_ma']?>"></br></br>

    <label for="">Grupo</label></br>
    <input type="text" name="gru" value="<?php echo $fila['grupo']?>"></br></br>

    <label for="">Usuario</label></br>
    <input type="text" name="usu" value="<?php echo $fila['usuari']?>"></br></br>

    <label for="">Contraseña</label></br>
    <input type="text" name="con" value="<?php echo $fila['contra']?>"></br></br>

    <label for="">Tipo de Usuario</label></br>
    <input type="text" name="ti" value="<?php echo $fila['tipo']?>"></br></br>

    <input type="submit" value="Modificar Usuario"></br></br>

</form>

<?php
        } else {
            echo "No se encontraron datos para el usuario seleccionado.";
        }
?>
</body>
</html>
<?php
} else {
    header("location:index.php");
}
?>

<a href="destruir.php">Cerrar Sesión</a>
