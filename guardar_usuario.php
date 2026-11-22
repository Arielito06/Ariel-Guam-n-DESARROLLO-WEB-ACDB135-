<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo_raw = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Realice la corrección para que se pueda
    // validar el nombre obligatorio y formato de correo con FILTER_VALIDATE_EMAIL
    if ($nombre === '' || !filter_var($correo_raw, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Ingresa un nombre válido y un correo electrónico con formato correcto.');
                window.history.back();
              </script>";
        exit;
    }

    // hice esta mejora para que al ingrear los correros sean con minúsculas
    $correo = strtolower($correo_raw);

    // Requisitos de seguridad de contraseña
    $tieneMayuscula = preg_match('/[A-Z]/', $password);
    $tieneMinuscula = preg_match('/[a-z]/', $password);
    $tieneNumero    = preg_match('/[0-9]/', $password);

    if (strlen($password) < 8 || !$tieneMayuscula || !$tieneMinuscula || !$tieneNumero) {
        echo "<script>
                alert('La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.');
                window.history.back();
              </script>";
        exit;
    }

    // Realice esta correccion para evitar correos duplicados
    $check = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? LIMIT 1");
    $check->bind_param("s", $correo);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        echo "<script>
                alert('Este correo electrónico ya está registrado.');
                window.history.back();
              </script>";
        exit;
    }
    $check->close();

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