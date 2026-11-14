<?php
session_start();
require 'conexion.php';

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $nueva = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    // Verificara si el correo existe en la base de datos
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        $error = "El correo electrónico no está registrado.";
    } elseif ($nueva !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($nueva) < 8 || !preg_match('/[A-Z]/', $nueva) || !preg_match('/[a-z]/', $nueva) || !preg_match('/[0-9]/', $nueva)) {
        $error = "La nueva contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.";
    } else {
        // Encripta la nueva contraseña y actualizarla
        $nuevo_hash = password_hash($nueva, PASSWORD_DEFAULT);
        $stmt_update = $conexion->prepare("UPDATE usuarios SET password = ? WHERE correo = ?");
        $stmt_update->bind_param("ss", $nuevo_hash, $correo);
        
        if ($stmt_update->execute()) {
            $exito = "¡Contraseña restablecida con éxito! Ya puedes iniciar sesión.";
        } else {
            $error = "Error al actualizar la contraseña.";
        }
        $stmt_update->close();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-form">
            <h2>Recuperar Clave</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger text-center p-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($exito): ?>
                <div class="alert alert-success text-center p-2"><?= htmlspecialchars($exito) ?></div>
                <div class="text-center mt-3">
                    <a href="login.php" class="btn-orange" style="text-decoration: none;">Ir al Login</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="input-group-custom">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" required autofocus>
                    </div>
                    <div class="input-group-custom">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="password" required minlength="8">
                    </div>
                    <div class="input-group-custom">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" name="confirmar" required minlength="8">
                    </div>
                    <button type="submit" class="btn-orange">Restablecer Clave</button>
                    <div class="text-center mt-3">
                        <a href="login.php" style="color: #aaa; text-decoration: none; font-size: 13px;">Volver al Login</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
        
        <div class="login-welcome" style="background: linear-gradient(135deg, #b900ff 0%, #5e008a 100%);">
            <h2>DON'T<br>WORRY!</h2>
            <p>Enter your email and set a new password. It happens to the best of us.</p>
        </div>
    </div>
</body>
</html>