<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id = $_GET["id"];

/* Verificar si el mueble está en pedidos */
$sql = "SELECT COUNT(*) as total FROM detalle_pedido WHERE id_mueble = $id";
$res = mysqli_query($conexion, $sql);
$fila = mysqli_fetch_assoc($res);

if ($fila["total"] > 0) {
    $error = "No se puede eliminar el mueble porque está en pedidos.";
} else {

    mysqli_query($conexion, "DELETE FROM muebles WHERE id_mueble = $id");
    header("Location: listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Error</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow text-center">
<div class="card-body">

<h4 class="text-danger mb-3">⚠️ Error</h4>

<p class="mb-4"><?php echo $error; ?></p>

<a href="listar.php" class="btn btn-primary">
⬅ Volver al catálogo
</a>

</div>
</div>

</div>

</body>
</html>