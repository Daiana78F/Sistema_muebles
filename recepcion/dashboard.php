<?php
session_start();
require_once(__DIR__ . "/../config/conexion.php");

// 🔐 VALIDAR SESIÓN (sin restringir rol)
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../public/login.php");
    exit();
}

/* Contadores */
function contar($conexion, $estado) {
    $sql = "SELECT COUNT(*) AS total FROM pedidos WHERE id_estado = $estado";
    $res = mysqli_query($conexion, $sql);
    $fila = mysqli_fetch_assoc($res);
    return $fila['total'];
}

$pendientes = contar($conexion,1);
$produccion = contar($conexion,2);
$terminados = contar($conexion,3);
$entregados = contar($conexion,4);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Recepción</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="mb-4">📊 Panel de Recepción</h2>

<div class="row">

<div class="col-md-3">
<div class="card text-white bg-primary mb-3 shadow-sm">
<div class="card-body text-center">
<h6>Pendientes</h6>
<h2><?php echo $pendientes; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-dark bg-warning mb-3 shadow-sm">
<div class="card-body text-center">
<h6>En Producción</h6>
<h2><?php echo $produccion; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-white bg-success mb-3 shadow-sm">
<div class="card-body text-center">
<h6>Terminados</h6>
<h2><?php echo $terminados; ?></h2>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card text-white bg-dark mb-3 shadow-sm">
<div class="card-body text-center">
<h6>Entregados</h6>
<h2><?php echo $entregados; ?></h2>
</div>
</div>
</div>

</div>

<hr>

<div class="mt-3 d-flex gap-2 flex-wrap">

<a href="clientes/listar.php" class="btn btn-outline-primary">
👥 Clientes
</a>

<a href="muebles/listar.php" class="btn btn-outline-success">
🪑 Catálogo
</a>

<a href="pedidos/listar.php" class="btn btn-outline-dark">
📦 Pedidos
</a>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>