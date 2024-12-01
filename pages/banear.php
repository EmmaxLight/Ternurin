<?php
        $id= $_GET ['id'];
        include "./../includes/config.php";
         
         $peticion = "DELETE FROM usuariosadmin WHERE id = $id";
         $respuesta = mysqli_query($conexion, $peticion);
         header ("location:superUsuario.php");

?>