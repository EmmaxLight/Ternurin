<?php
session_start();
session_destroy();

header("location:superUsuario.php?mensaje=sesion_cerrada");
?>