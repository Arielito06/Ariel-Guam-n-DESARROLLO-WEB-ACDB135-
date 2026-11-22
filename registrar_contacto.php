<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Consultar las categorías desde la base de datos
$categorias = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-custom p-4">
                    <h3 class="mb-4" style="color: #ff5e00;">Agregar Nuevo Contacto</h3>
                    <form action="guardar_contacto.php" method="POST">
                        
                        <div class="mb-3">
                            <label>Nombre y Apellido</label>
                            <input type="text" name="nombre" class="form-control form-control-custom" required>
                        </div>

                        <div class="mb-3">
                            <label>Correo</label>
                            <input type="email" name="correo" class="form-control form-control-custom" required>
                        </div>

                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="telefono" class="form-control form-control-custom" placeholder="Ej: 099 999 9999">
                        </div>

                        <div class="mb-3">
                            <label>Ciudad</label>
                            <input type="text" name="ciudad" class="form-control form-control-custom">
                        </div>

                        <!-- Se puede Seleccionar la Categoría -->
                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="categoria_id" class="form-select form-select-custom" required>
                                <?php while ($cat = $categorias->fetch_assoc()): ?>
                                    <option value="<?= (int)$cat['id'] ?>" <?= ((int)$cat['id'] === 5) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-check text-light mb-3">
                            <input class="form-check-input" type="checkbox" name="favorito" value="1" id="favorito">
                            <label class="form-check-label" for="favorito">Marcar como Favorito ⭐</label>
                        </div>

                        <button type="submit" class="btn-orange">Guardar Contacto</button>
                        <a href="agenda.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conexion->close();
?>