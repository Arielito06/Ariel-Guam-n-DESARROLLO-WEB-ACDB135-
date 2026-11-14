<?php
session_start();
require 'conexion.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: agenda.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Agregamos 'correo' al SELECT para que la sesión lo pueda guardar
    $stmt = $conexion->prepare("SELECT id, nombre, correo, password FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        if (password_verify($password, $fila['password'])) {
            $_SESSION['usuario_id'] = $fila['id'];
            $_SESSION['usuario_nombre'] = $fila['nombre'];
            $_SESSION['usuario_correo'] = $fila['correo'];
            header("Location: agenda.php");
            exit;
        }
    }
    $error = 'Correo o contraseña incorrectos.';
    $stmt->close();
}
$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-form">
                <h2>Login</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center p-2"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="input-group-custom">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" required autofocus>
                    </div>
                    <div class="input-group-custom">
                        <label>Contraseña</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-orange">Entrar</button>
                    
                    <div class="text-center mt-3">
                        <a href="registro.html" style="color: #ff5e00; text-decoration: none;">Sign Up (Crear cuenta)</a>
                        <br><br>
                        <a href="recuperar_password.php" style="color: #aaa; text-decoration: none; font-size: 13px;">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            </div>
            <div class="login-welcome">
                <h2>WELCOME<br>BACK!</h2>
                <p>We are happy to have you with us again.</p>
            </div>
        </div>
    </div>
</body>
</html>