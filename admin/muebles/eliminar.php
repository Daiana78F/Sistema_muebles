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
$error = "";

/* VERIFICAR SI ESTÁ EN PEDIDOS */
$stmt = $conexion->prepare("SELECT COUNT(*) as total FROM detalle_pedido WHERE id_mueble = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

if ($fila["total"] > 0) {
    $error = "No se puede eliminar el mueble porque está en pedidos.";
} else {

    /* ELIMINAR */
    $stmt = $conexion->prepare("DELETE FROM muebles WHERE id_mueble = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: listar.php");
        exit();
    } else {
        $error = "Error al eliminar el mueble";
    }
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

<p class="mb-4"><?= $error ?></p>

<a href="listar.php" class="btn btn-primary">
⬅ Volver al catálogo
</a>

</div>
</div>

</div>

</body>
</html>