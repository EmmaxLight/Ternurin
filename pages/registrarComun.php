es el Comun
<h1>Usuario Registrado con exito </h1></br>



<?php
$matricula = $_POST['matri'];
$nombre = $_POST['nom'];
$app = $_POST['ap'];
$apm = $_POST['am'];
$grup = $_POST['gru'];
$usua = $_POST['usu'];
$contra = $_POST['con'];
$correo =$_POST['corre'];
$telefono =$_POST['tele'];
$calificasion1 =$_POST['cali1'];
$calificasion2 =$_POST['cali2'];
$calificasion3 =$_POST['cali3'];
$prome =$_POST['pro'];
$tipo = $_POST['ti'];




    //incluimos base de datos
    include "./../includes/config.php";
    $peticionx = "SELECT * FROM usuarios WHERE usuario ='$usua'";
    $respuestax = mysqli_query($conexion, $peticionx);
    if ($filax = mysqli_fetch_array($respuestax)){
    //header("location:registrocliente.php?mensaje=yaexiste")
    }else {
$peticion = "INSERT INTO usuarios VALUES(NULL,'$matricula', '$nombre', '$app', '$apm','$grup', '$usua', '$contra','$correo','$telefono','$calificasion1','$calificasion2','$calificasion3','$prome','$tipo')";
$respuesta = mysqli_query($conexion, $peticion);


//header("location:index.php?mensaje=exitoalcrear");
    }
?>
 <form action="index.php" method="post"> <!---->
    <input type="submit" value="Regresar"></br></br>
    

</form>