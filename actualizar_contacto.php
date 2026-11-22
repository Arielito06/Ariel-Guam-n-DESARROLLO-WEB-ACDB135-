<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $id_usuario = (int)$_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre'] ?? '');
    $correo_raw = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    // Realice una mejora en categoria para poder Recibir categoria_id
    $categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : 5;
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    // Hice la correccion para que se pueda validar los campos obligatorios y usar FILTER_VALIDATE_EMAIL
    if ($id <= 0 || $nombre === '' || !filter_var($correo_raw, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('El nombre es obligatorio y el correo electrónico debe ser válido.');
                window.history.back();
              </script>";
        exit;
    }

    // Realice una mejora en categoria para verificar cuál es la categoría válida
    $cat_stmt = $conexion->prepare("SELECT id FROM categorias WHERE id = ? LIMIT 1");
    $cat_stmt->bind_param("i", $categoria_id);
    $cat_stmt->execute();
    if ($cat_stmt->get_result()->num_rows === 0) {
        $categoria_id = 5;
    }
    $cat_stmt->close();

    // Realice una mejora en categoria con el fin de Actualizar la categoria_id solo del contacto del usuario
    $stmt = $conexion->prepare("UPDATE contactos SET nombre=?, correo=?, telefono=?, ciudad=?, categoria_id=?, favorito=? WHERE id=? AND id_usuario=?");
    $stmt->bind_param("ssssiiii", $nombre, $correo_raw, $telefono, $ciudad, $categoria_id, $favorito, $id, $id_usuario);
    $stmt->execute();
    $stmt->close();
}
header("Location: agenda.php");
exit;
?>