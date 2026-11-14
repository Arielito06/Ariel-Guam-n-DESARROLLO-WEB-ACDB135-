<?php
session_start(); //inicia la sesión
session_unset(); // se libera todas las variables de sesión
session_destroy(); // Destruimos la sesión por completo del servidor
header("Location: login.php");
exit;
?>