<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id = $_GET["id"];
$mensaje = "";

// Obtener datos actuales
$sql = "SELECT * FROM muebles WHERE id_mueble = $id";
$resultado = mysqli_query($conexion, $sql);
$mueble = mysqli_fetch_assoc($resultado);

// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio_base = trim($_POST["precio_base"]);
    $caracteristicas = trim($_POST["caracteristicas"]);

    $sqlUpdate = "UPDATE muebles SET
        nombre='$nombre',
        descripcion='$descripcion',
        precio_base='$precio_base',
        caracteristicas='$caracteristicas'
        WHERE id_mueble=$id";

    if (mysqli_query($conexion, $sqlUpdate)) {
        header("Location: listar.php");
        exit();
    } else {
        $mensaje = "Error al actualizar el mueble";
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

<h1 class="mb-4">Editar Mueble</h1>

<form method="POST" class="card p-4">

    <label class="form-label">Nombre</label>
    <input type="text" name="nombre"
           value="<?php echo $mueble['nombre']; ?>"
           class="form-control" required>

    <label class="form-label mt-3">Descripción</label>
    <textarea name="descripcion" class="form-control" required><?php echo $mueble['descripcion']; ?></textarea>

    <label class="form-label mt-3">Precio Base</label>
    <input type="number" step="0.01" name="precio_base"
           value="<?php echo $mueble['precio_base']; ?>"
           class="form-control" required>

    <label class="form-label mt-3">Características</label>
    <textarea name="caracteristicas" class="form-control"><?php echo $mueble['caracteristicas']; ?></textarea>

    <button type="submit" class="btn btn-success mt-4">
        Guardar cambios
    </button>

</form>

<p class="text-danger mt-3"><?php echo $mensaje; ?></p>

<a href="listar.php" class="btn btn-secondary mt-3">
⬅ Volver al listado
</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>