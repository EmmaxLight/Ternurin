<?php

    //incluimos base de datos
    include "./../includes/config.php";

    $opcion = $_GET ['op'];
    switch ($opcion) {
        case 1:
        $peticion = "TRUNCATE TABLE usuariosadmin";
        //recibimos info de la base de datos
        $respuesta = mysqli_query($conexion, $peticion);
        header("location:superUsuario.php");

                break;
        case 2:
         $peticion = "TRUNCATE TABLE historial";
        //recibimos info de la base de datos
         $respuesta = mysqli_query($conexion, $peticion);
         header("location:superUsuario.php");

                        break;

        
        default:
        header("location:superUsuario.php");
           
            break;
    }

?>
