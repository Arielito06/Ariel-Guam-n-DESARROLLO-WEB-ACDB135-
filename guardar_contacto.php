<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = (int)$_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $correo_raw = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    // Hice una mejora en categoria para recibir categoria_id (por defecto 5 = 'Otro')
    $categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 5;
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    // Hice esta corrección para hacer que los campos sean obligatorios y usar FILTER_VALIDATE_EMAIL
    if ($nombre === '' || !filter_var($correo_raw, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('El nombre es obligatorio y debes ingresar un correo electrónico válido.');
                window.history.back();
              </script>";
        exit;
    }

    // Hice una mejora en categoria para ques se pueda verificar que categoria_id existe
    $cat_stmt = $conexion->prepare("SELECT id FROM categorias WHERE id = ? LIMIT 1");
    $cat_stmt->bind_param("i", $categoria_id);
    $cat_stmt->execute();
    if ($cat_stmt->get_result()->num_rows === 0) {
        $categoria_id = 5;
    }
    $cat_stmt->close();

    // Hice una mejora en categoria para guardar categoria_id en contactos
    $stmt = $conexion->prepare("INSERT INTO contactos (id_usuario, nombre, correo, telefono, ciudad, categoria_id, favorito) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssii", $id_usuario, $nombre, $correo_raw, $telefono, $ciudad, $categoria_id, $favorito);
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>