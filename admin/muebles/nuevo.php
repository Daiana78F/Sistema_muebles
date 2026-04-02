<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

/* CONEXIÓN */
$conexion = mysqli_connect("localhost", "root", "", "sistema_muebles");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio_base = trim($_POST["precio_base"]);
    $caracteristicas = trim($_POST["caracteristicas"]);

    /* VALIDACIÓN */
    if (empty($nombre) || empty($descripcion) || empty($precio_base)) {
        $mensaje = "Todos los campos obligatorios deben completarse";
    } else {

        /* 🔥 VALIDAR DUPLICADO EXACTO */
        $stmt_check = $conexion->prepare("
            SELECT id_mueble 
            FROM muebles 
            WHERE nombre = ? 
            AND descripcion = ? 
            AND caracteristicas = ?
        ");
        $stmt_check->bind_param("sss", $nombre, $descripcion, $caracteristicas);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();

        if ($resultado_check->num_rows > 0) {
            $mensaje = "⚠️ Ya existe un mueble con los mismos datos";
        } else {

            /* INSERT */
            $stmt = $conexion->prepare("
                INSERT INTO muebles (nombre, descripcion, precio_base, caracteristicas) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("ssds", $nombre, $descripcion, $precio_base, $caracteristicas);

            if ($stmt->execute()) {
                header("Location: listar.php");
                exit();
            } else {
                $mensaje = "Error al guardar el mueble";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Mueble</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h1 class="mb-4">🪑 Nuevo Mueble</h1>

<?php if($mensaje): ?>
<div class="alert alert-danger">
<?= $mensaje ?>
</div>
<?php endif; ?>

<form method="POST" class="card p-4">

<label class="form-label">Nombre</label>
<input type="text" name="nombre" class="form-control" required>

<label class="form-label mt-3">Descripción</label>
<textarea name="descripcion" class="form-control" required></textarea>

<label class="form-label mt-3">Precio Base</label>
<input type="number" step="0.01" name="precio_base" class="form-control" required>

<label class="form-label mt-3">Características</label>
<textarea name="caracteristicas" class="form-control"></textarea>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-success">
        💾 Guardar Mueble
    </button>

    <a href="listar.php" class="btn btn-secondary">
        ⬅ Volver
    </a>
</div>

</form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>