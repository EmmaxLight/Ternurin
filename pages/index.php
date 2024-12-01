<!DOCTYPE html>   <!--indica la versión de HTML5 que se esta Usado -->
<html lang="es"> <!--indica el idioma en este caso español -->
<head>      <!--Define la meta-información -->
    <meta charset="UTF-8">   <!-- establece que los caracteres del documento-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--ajustar automáticamente el ancho de la ventana para dispositivos moviles -->
    <title> Inicio de sesion</title> <!--Establece un titulo a la  pagina wed -->
    
</head> <!--fin de la etiqueta de cierre de la Meta información -->

<!--Esto es necesario para que funcione la pagina web todo lo de arriba -->



<?php include "./../componentes/formulario_registro.php"?> <!--incluye un archivo php-->
</form> <!--crea un formulario-->
<!--NOTAS-->
<!--El atributo action especifica la URL del servidor que se encargará de procesar los datos del formulario cuando se envíen -->
<!--El atributo method especifica el método HTTP que se utilizará para enviar los datos del formulario -->
<!--POST se utiliza para enviar datos al servidor de una manera que no se muestra los datos  en la URL del navegador.  -->
<form action="registroNormal.php" method="post"> <!--envia los datos a el archivo registrar.php con el método post-->

<!--NOTAS-->
<!-- input es una etiqueta utilizada para crear varios tipos de controles interactivos en formularios web.-->
<!--El atributo type especifica el tipo de control de entrada-->
<!--type="submit" indica que el control es un botón de envío del formulario.-->
<!--El atributo value define el texto que aparecerá en el botón-->
<!---->

        
        </div>

        
        
       

</form> <!--este es el cierre del formulario -->
</div>
    </div>  


</body>


</html>