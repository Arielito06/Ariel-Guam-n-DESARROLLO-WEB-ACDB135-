<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario_id']; //
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $ciudad = htmlspecialchars(trim($_POST['ciudad']));
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    //Agregamos id_usuario a la consulta
    $stmt = $conexion->prepare("INSERT INTO contactos (id_usuario, nombre, correo, telefono, ciudad, favorito) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $id_usuario, $nombre, $correo, $telefono, $ciudad, $favorito); 
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>