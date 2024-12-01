<h1>Haz Entrado como usuario común </h1></br>
<h1>Tienes permiso de modificar lo que necesites  </h1></br>

<?php
session_start();
if(isset($_SESSION['tipo']) AND $_SESSION['tipo'] == 1 && isset($_SESSION['modificar_id'])){
?>

<!DOCTYPE html>   <!--indica la versión de HTML5 que se esta Usado -->
<html lang="es"> <!--indica el idioma en este caso español -->
<head>      <!--Define la meta-información -->
    <meta charset="UTF-8">   <!-- establece que los caracteres del documento-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--ajustar automáticamente el ancho de la ventana para dispositivos moviles -->
    <title>Inicio de sesión</title> <!--Establece un título a la página web -->
    <link rel="stylesheet" href="./../assets/css/bootstrap.css"> <!-- vincula la hoja de estilos CSS bootstrap.css-->
    <script>
        function calcularPromedio() {
            var cali1 = parseFloat(document.getElementsByName('cali1')[0].value) || 0;
            var cali2 = parseFloat(document.getElementsByName('cali2')[0].value) || 0;
            var cali3 = parseFloat(document.getElementsByName('cali3')[0].value) || 0;
            var promedio = (cali1 + cali2 + cali3) / 3;

            document.getElementsByName('pro')[0].value = promedio.toFixed(2);

            var mensaje = promedio >= 7.0 ? "El alumno aprobó la materia." : "El alumno reprobó la materia.";
            alert(mensaje);
        }
    </script>
</head> <!--fin de la etiqueta de cierre de la Meta información -->

<body style="background-image: URL('./../assets/resources/amarillo.jpg');" width="20%">

<?php
        include "./../includes/config.php";
        $peticion = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($peticion);
        $stmt->bind_param('i', $_SESSION['modificar_id']);
        $stmt->execute();
        $respuesta = $stmt->get_result();
        
        if($fila = $respuesta->fetch_assoc()){
    ?>
    
<form action="actualizaralumno.php" method="post">

    <label for="">Matricula: </label><br><br>
    <input type="text" name="matricula" value="<?php echo $fila['matricula']?>"><br><br>

    <label for="">Nombre: </label><br><br>
    <input type="text" name="nom" value="<?php echo $fila['nombre']?>"><br><br>
    

    <label for="">Apellido paterno</label></br>
    <input type="text" name="ap" value="<?php echo $fila['primer_apellido']?>"></br></br>

    <label for="">Apellido materno</label></br>
    <input type="text" name="am" value="<?php echo $fila['segundo_apellido']?>"></br></br>

    <label for="">Grupo</label></br>
    <input type="text" name="gru" value="<?php echo $fila['grupo']?>"></br></br>

    <label for="">Usuario</label></br>
    <input type="text" name="usu" value="<?php echo $fila['usuario']?>"></br></br>

    <label for="">Contraseña</label></br>
    <input type="text" name="con" value="<?php echo $fila['contrasena']?>"></br></br>


    <label for="">Correo Electronico: </label><br><br>
    <input type="text" name="corre" value="<?php echo $fila['correo']?>"><br><br>

    <label for="">Telefono Celular: </label><br><br>
    <input type="text" name="tele" value="<?php echo $fila['telefono']?>"><br><br>

    <label for="">Calificasion 1: </label><br><br>
    <input type="text" name="cali1" value="<?php echo $fila['calificasion1']?>"><br><br>

    <label for="">Calificasion 2: </label><br><br>
    <input type="text" name="cali2" value="<?php echo $fila['calificasion2']?>"><br><br>


    <label for="">calificasion 3: </label><br><br>
    <input type="text" name="cali3" value="<?php echo $fila['calificasion3']?>"><br><br>


    <label for="">Calificasion Final: </label><br><br>
    <input type="text" name="pro" value="<?php echo $fila['prome']?>"><br><br>

    <label for="">Tipo de Usuario</label></br>
    <input type="text" name="ti" value="<?php echo $fila['tipo']?>"></br></br>

    <input type="submit" value="Modificar Usuario"></br></br>
    <input type="button" value="Promediar" onclick="calcularPromedio()"></br></br>

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