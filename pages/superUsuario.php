<h1> Haz Entrado Como Super Usuario </h1></br>

<?php
session_start();
    if(isset($_SESSION['tipo']) AND $_SESSION['tipo']==2){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="background-image: URL('./../assets/resources/amarillo.jpg');">
    <style>
        .botones{
            background-color: aqua;
            padding:20px;
            margin: auto;
            width: 50%;

        }
        .botones > button{
            padding:10px 15px;
            width:100%;
            background-color: #f0f0f0;
            border:0px solid;
            border-radius:50px;
            margin-bottom: 20px;
            box-shadow:10px 10px 10px rgba(0, 0, 0, .5);
            transition: all  .8s ease;

        }
        .botones > button > a{
            text-decoration:none;
            color:red;
        }
        .botones > button:hover{
            padding:15px 20px;

        }
        table {
    border: 1px solid black;
    width: 90%;
    margin: 40px auto;
}
table tr td{
    border: 1px solid black;
    text-align: center;
    padding: 10px 15 px;
}

    </style>


<form action = "validarUsuario.php" method="post">
usuario: <input type="text" name="usuari" placeholder="Coloca tu usuario" required><br/><br/>
contrasena: <input type="password" name="contra" placeholder="Coloca tu contraseña" required><br/><br/>
<input type="submit" value="Entrar">
        
    </form>



    
    <div class="botones">
     
     <button><a href= "borrar.php?op=1"> Borrar usuarios </a></button>
     <button><a href= "borrar.php?op=2"> Borrar historial </a></button>
    </div>

    <div class="baneo">
    <table>
            <tr>
                <td>id</td>
                <td>Nombre</td>
                <td>Apellido paterno</td>
                <td>Apellido materno</td>
                <td>grupo</td>
                <td>usuario</td>
                <td>contraseña</td>
                <td>TIPO</td>
                <td>Banear</td>


         </tr>

        <?php
         include "./../includes/config.php";
         $peticion = "SELECT * FROM usuariosadmin";
         $respuesta = mysqli_query($conexion, $peticion);
         while($fila = mysqli_fetch_array($respuesta)){

        ?>
       <tr>
        <td><?php echo $fila['id']?></td>
        <td><?php echo $fila['nombre']?></td>
        <td><?php echo $fila['apellido_pa']?></td>
        <td><?php echo $fila['apellido_ma']?></td>
        <td><?php echo $fila['grupo']?></td>
        <td><?php echo $fila['usuari']?></td>
        <td><?php echo $fila['contra']?></td>
        <td><?php echo $fila['tipo']?></td>

        <td><a href="banear.php?id=<?php echo $fila['id']?>"><button>Banear</button></a></td>
         </tr>


        <?php
        }
        ?>
        </table>
    </div>
    
    <a href="destruirSUPER.php">Cerrar SUPER USUARIO</a>
</body>
</html>
<?php
}else{
    header("location:index.php");
}
?>