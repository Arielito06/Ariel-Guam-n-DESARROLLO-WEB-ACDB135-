<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $id_usuario = $_SESSION['usuario_id']; 
    
    //  Borra solo si el contacto es del usuario
    $stmt = $conexion->prepare("DELETE FROM contactos WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>