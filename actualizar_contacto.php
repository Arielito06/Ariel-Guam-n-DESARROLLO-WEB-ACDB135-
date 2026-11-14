<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $id_usuario = $_SESSION['usuario_id']; // 
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $ciudad = htmlspecialchars(trim($_POST['ciudad']));
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    // Actualiza solo si el contacto es del usuario
    $stmt = $conexion->prepare("UPDATE contactos SET nombre=?, correo=?, telefono=?, ciudad=?, favorito=? WHERE id=? AND id_usuario=?");
    $stmt->bind_param("ssssiii", $nombre, $correo, $telefono, $ciudad, $favorito, $id, $id_usuario); 
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>