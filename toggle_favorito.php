<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $id_usuario = $_SESSION['usuario_id']; // 
    // Cambia el favorito solo si es del usuario
    $stmt = $conexion->prepare("UPDATE contactos SET favorito = NOT favorito WHERE id = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>