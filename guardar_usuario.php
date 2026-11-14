<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // VALIDACIÓN DE SEGURIDAD DE LA CONTRASEÑA
    // 1. Mínimo 8 caracteres
    // 2. Al menos una mayúscula
    // 3. Al menos una minúscula
    // 4. Al menos un número
    $tieneMayuscula = preg_match('/[A-Z]/', $password);
    $tieneMinuscula = preg_match('/[a-z]/', $password);
    $tieneNumero    = preg_match('/[0-9]/', $password);
    
    if (strlen($password) < 8 || !$tieneMayuscula || !$tieneMinuscula || !$tieneNumero) {
        echo "<script>
                alert('La contraseña no cumple los requisitos de seguridad. Debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.');
                window.history.back();
              </script>";
        exit;
    }

    // Encriptar contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $correo, $password_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado con éxito.'); window.location='login.php';</script>";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
    $stmt->close();
}
$conexion->close();
?>