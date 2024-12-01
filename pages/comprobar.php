<?php
    
    session_start();
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $ip = $_SERVER['REMOTE_ADDR'];
    include "./../includes/config.php";

    $peticion0="INSERT INTO historial VALUES(NULL,'$usuario',NOW(),'$ip')"; 
    $respuesta0 = mysqli_query($conexion, $peticion0);

    $peticion = "SELECT * FROM usuariosadmin WHERE usuari = '$usuario' AND contra = '$contrasena'";
    $respuesta = mysqli_query($conexion, $peticion);
    $contador = 0;
    while($fila = mysqli_fetch_array($respuesta)){
        $contador++;
        $tipousuario = $fila['tipo'];
        $_SESSION['tipo'] =$fila['tipo'];
        $_SESSION['idu'] =$fila['id'];
    }
    if($contador > 0 AND $tipousuario ==1){
        header("location:UsuarioComun.php");
}elseif($contador > 0 AND $tipousuario ==0){
    header("location:admin.php");
}elseif($contador > 0 AND $tipousuario == 2){
    header ("location:superUsuario.php");
}else{ 
header("location:index.php?mensaje=error208");
    }
?>
<!-- 0 para  Administrador -->
<!-- 1 para Usuario comun -->
<!-- 2 Super usuario -->