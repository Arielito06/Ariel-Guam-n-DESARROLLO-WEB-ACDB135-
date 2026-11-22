<?php
session_start();
require 'conexion.php';

$mensaje = '';
$tipo_alerta = 'danger';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Corregi esta parte para que se pueda validar el formato del correo
    $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_VALIDATE_EMAIL);

    // Corregí esta parte Exigir al usuario lacontraseña actual con el fin de verificar identidad antes del cambio
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nueva  = $_POST['password_nueva'] ?? '';

    $tieneMayuscula = preg_match('/[A-Z]/', $password_nueva);
    $tieneMinuscula = preg_match('/[a-z]/', $password_nueva);
    $tieneNumero    = preg_match('/[0-9]/', $password_nueva);

    if (!$correo) {
        $mensaje = 'Ingresa un correo electrónico válido.';
    } elseif (strlen($password_nueva) < 8 || !$tieneMayuscula || !$tieneMinuscula || !$tieneNumero) {
        $mensaje = 'La nueva contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.';
    } else {
        $stmt = $conexion->prepare("SELECT id, password FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // El cambio de contraseña funcionara si se verifica la identidad con password_verify
        if ($usuario && password_verify($password_actual, $usuario['password'])) {
            $nuevo_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
            $upd = $conexion->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $upd->bind_param("si", $nuevo_hash, $usuario['id']);

            if ($upd->execute()) {
                $mensaje = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
                $tipo_alerta = 'success';
            } else {
                $mensaje = 'No se pudo actualizar la contraseña.';
            }
            $upd->close();
        } else {
            $mensaje = 'No se pudo verificar la identidad con los datos proporcionados.';
        }
    }
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar / Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card-custom p-4">
                    <h3 class="mb-3" style="color: #ff5e00;">Cambiar Contraseña</h3>
                    <p class="small text-light">Por seguridad, confirma tu identidad ingresando tu contraseña actual.</p>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-<?= $tipo_alerta ?> p-2"><?= htmlspecialchars($mensaje) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control form-control-custom" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña Actual</label>
                            <input type="password" name="password_actual" class="form-control form-control-custom" required>
                        </div>
                        <div class="mb-3">
                            <label>Nueva Contraseña</label>
                            <input type="password" name="password_nueva" class="form-control form-control-custom" required>
                        </div>
                        <button type="submit" class="btn-orange">Actualizar Contraseña</button>
                        <a href="login.php" class="btn btn-secondary w-100 mt-2">Volver al Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>