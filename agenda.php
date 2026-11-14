<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$buscar = trim($_GET['buscar'] ?? '');
$orden  = $_GET['orden'] ?? 'nombre_asc';
$solo_favoritos = isset($_GET['favoritos']) ? true : false;

$id_usuario_actual = $_SESSION['usuario_id'];

$sql = "SELECT * FROM contactos WHERE id_usuario = ?";
$parametros = [$id_usuario_actual];
$tipos = "i"; 

if ($buscar !== '') {
    $sql .= " AND (nombre LIKE ? OR correo LIKE ? OR ciudad LIKE ?)";
    $buscar_param = "%$buscar%";
    $parametros[] = $buscar_param;
    $parametros[] = $buscar_param;
    $parametros[] = $buscar_param;
    $tipos .= "sss"; 
}

if ($solo_favoritos) {
    $sql .= " AND favorito = 1";
}

if ($orden == 'nombre_desc') $sql .= " ORDER BY nombre DESC";
elseif ($orden == 'ciudad_asc') $sql .= " ORDER BY ciudad ASC";
elseif ($orden == 'fecha_desc') $sql .= " ORDER BY fecha_creacion DESC";
else $sql .= " ORDER BY nombre ASC";

$stmt = $conexion->prepare($sql);
if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 style="color: #ff5e00; margin-bottom: 0;">📒 Agenda de <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h2>
                <small class="text-light"><?= htmlspecialchars($_SESSION['usuario_correo'] ?? '') ?></small>
            </div>
            <div>
                <!-- Solo queda el botón de Cerrar Sesión -->
                <a href="logout.php" class="btn btn-outline-danger">Cerrar Sesión</a>
            </div>
        </div>

        <div class="card-custom p-3 mb-4">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="buscar" class="form-control form-control-custom" placeholder="Buscar nombre, correo o ciudad..." value="<?= htmlspecialchars($buscar) ?>">
                </div>
                <div class="col-md-3">
                    <select name="orden" class="form-select form-select-custom">
                        <option value="nombre_asc" <?= $orden == 'nombre_asc' ? 'selected' : '' ?>>Nombre (A-Z)</option>
                        <option value="nombre_desc" <?= $orden == 'nombre_desc' ? 'selected' : '' ?>>Nombre (Z-A)</option>
                        <option value="ciudad_asc" <?= $orden == 'ciudad_asc' ? 'selected' : '' ?>>Ciudad (A-Z)</option>
                        <option value="fecha_desc" <?= $orden == 'fecha_desc' ? 'selected' : '' ?>>Más recientes</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check text-light">
                        <input class="form-check-input" type="checkbox" name="favoritos" id="favoritos" <?= $solo_favoritos ? 'checked' : '' ?>>
                        <label class="form-check-label" for="favoritos">
                            Solo Favoritos ⭐
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-orange w-100" style="margin-top: 0; padding: 8px;">Filtrar</button>
                </div>
            </form>
        </div>

        <div class="mb-3">
            <a href="registrar_contacto.html" class="btn btn-success">+ Agregar Contacto</a>
        </div>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>⭐</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Ciudad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <a href="toggle_favorito.php?id=<?= $fila['id'] ?>" style="text-decoration: none; font-size: 20px;">
                                <?= $fila['favorito'] ? '⭐' : '☆' ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['correo']) ?></td>
                        <td><?= htmlspecialchars($fila['telefono']) ?></td>
                        <td><?= htmlspecialchars($fila['ciudad']) ?></td>
                        <td>
                            <a href="editar_contacto.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="borrar_contacto.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar contacto?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($resultado->num_rows === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted">No se encontraron contactos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$stmt->close();
$conexion->close();
?>