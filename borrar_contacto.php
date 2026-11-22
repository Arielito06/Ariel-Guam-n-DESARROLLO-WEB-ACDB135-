<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

// Hice la correcion para que la Eliminación ahora se procesa por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $id_usuario = (int)$_SESSION['usuario_id'];

    if ($id > 0) {
        $stmt = $conexion->prepare("DELETE FROM contactos WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $id, $id_usuario);
        $stmt->execute();
        $stmt->close();
    }
}
header("Location: agenda.php");
exit;
?>