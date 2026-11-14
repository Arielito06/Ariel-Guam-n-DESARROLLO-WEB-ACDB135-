<?php
session_start();
require 'conexion.php';
if (!isset($_SESSION['usuario_id'])) exit(header("Location: login.php"));

$id = (int)$_GET['id'];
$id_usuario = $_SESSION['usuario_id']; // Se Obtiene el ID del usuario

// Se Busca el contacto y verificamos que sea del usuario
$stmt = $conexion->prepare("SELECT * FROM contactos WHERE id = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id, $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$contacto = $resultado->fetch_assoc();

// Si no existe o no es suyo, lo devolvemos a la agenda
if (!$contacto) exit(header("Location: agenda.php"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h3 class="mb-4" style="color: #ff5e00;">Editar Contacto</h3>
                    <form action="actualizar_contacto.php" method="POST">
                        <input type="hidden" name="id" value="<?= $contacto['id'] ?>">
                        
                        <div class="mb-3">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-custom" value="<?= htmlspecialchars($contacto['nombre']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control form-control-custom" value="<?= htmlspecialchars($contacto['correo']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-custom" value="<?= htmlspecialchars($contacto['telefono']) ?>">
                        </div>
                        <div class="mb-3">
                            <label>Ciudad</label>
                            <input type="text" name="ciudad" class="form-control form-control-custom" value="<?= htmlspecialchars($contacto['ciudad']) ?>">
                        </div>

                        <!-- 👈 NUEVO: Checkbox de Favorito -->
                        <div class="form-check text-light mb-3">
                            <input class="form-check-input" type="checkbox" name="favorito" value="1" id="favorito" <?= $contacto['favorito'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="favorito">Marcar como Favorito ⭐</label>
                        </div>

                        <button type="submit" class="btn-orange">Actualizar Contacto</button>
                        <a href="agenda.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>