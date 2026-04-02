<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

/* CONEXIÓN */
$conexion = mysqli_connect("localhost", "root", "", "sistema_muebles");

/* VALIDAR ID */
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id = $_GET["id"];
$mensaje = "";

/* OBTENER DATOS */
$stmt = $conexion->prepare("SELECT * FROM muebles WHERE id_mueble = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$mueble = $resultado->fetch_assoc();

if (!$mueble) {
    header("Location: listar.php");
    exit();
}

/* GUARDAR CAMBIOS */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio_base = trim($_POST["precio_base"]);
    $caracteristicas = trim($_POST["caracteristicas"]);

    if (empty($nombre) || empty($descripcion) || empty($precio_base)) {
        $mensaje = "Todos los campos obligatorios deben completarse";
    } else {

        $stmt = $conexion->prepare("UPDATE muebles 
            SET nombre=?, descripcion=?, precio_base=?, caracteristicas=? 
            WHERE id_mueble=?");

        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio_base, $caracteristicas, $id);

        if ($stmt->execute()) {
            header("Location: listar.php");
            exit();
        } else {
            $mensaje = "Error al actualizar el mueble";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mueble</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h1 class="mb-4">✏️ Editar Mueble</h1>

<?php if($mensaje): ?>
<div class="alert alert-danger">
    <?= $mensaje ?>
</div>
<?php endif; ?>

<form method="POST" class="card p-4">

    <label class="form-label">Nombre</label>
    <input type="text" name="nombre"
           value="<?= htmlspecialchars($mueble['nombre']) ?>"
           class="form-control" required>

    <label class="form-label mt-3">Descripción</label>
    <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($mueble['descripcion']) ?></textarea>

    <label class="form-label mt-3">Precio Base</label>
    <input type="number" step="0.01" name="precio_base"
           value="<?= $mueble['precio_base'] ?>"
           class="form-control" required>

    <label class="form-label mt-3">Características</label>
    <textarea name="caracteristicas" class="form-control"><?= htmlspecialchars($mueble['caracteristicas']) ?></textarea>

    <button type="submit" class="btn btn-success mt-4">
        💾 Guardar cambios
    </button>

</form>

<a href="listar.php" class="btn btn-secondary mt-3">
⬅ Volver al listado
</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>