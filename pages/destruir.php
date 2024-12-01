<?php
session_start();
session_destroy();

header("location:index.php?mensaje=sesion_cerrada");
?>