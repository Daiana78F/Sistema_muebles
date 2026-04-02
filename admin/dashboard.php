<?php
session_start();
require_once(__DIR__ . "/../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../public/login.php");
    exit();
}

/* métricas */
$total_usuarios = mysqli_fetch_assoc(
    mysqli_query($conexion,"SELECT COUNT(*) total FROM usuarios")
)['total'];

$total_clientes = mysqli_fetch_assoc(
    mysqli_query($conexion,"SELECT COUNT(*) total FROM clientes")
)['total'];

$total_pedidos = mysqli_fetch_assoc(
    mysqli_query($conexion,"SELECT COUNT(*) total FROM pedidos")
)['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../includes/navbar.php"); ?>

<div class="container mt-4">

<h1 class="mb-4">📊 Panel Administrador</h1>

<div class="row">

<div class="col-md-4">
<div class="card bg-dark text-white mb-3">
<div class="card-body">
<h5>Usuarios</h5>
<h2><?= $total_usuarios ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card bg-primary text-white mb-3">
<div class="card-body">
<h5>Clientes</h5>
<h2><?= $total_clientes ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card bg-success text-white mb-3">
<div class="card-body">
<h5>Pedidos</h5>
<h2><?= $total_pedidos ?></h2>
</div>
</div>
</div>

</div>

<hr>

<h4 class="mb-3">Gestión del Sistema</h4>

<div class="d-flex flex-wrap gap-2">

<a href="/sistema_muebles/admin/usuarios/listar.php"
   class="btn btn-dark">
👥 Gestionar Usuarios
</a>

<!-- CLIENTES se mantiene en RECEPCION -->
<a href="/sistema_muebles/recepcion/clientes/listar.php"
   class="btn btn-primary">
📋 Ver Clientes
</a>

<!-- PEDIDOS ahora correcto -->
<a href="/sistema_muebles/admin/pedidos/listar.php"
   class="btn btn-success">
📦 Ver Pedidos
</a>

<a href="/sistema_muebles/admin/muebles/listar.php"
   class="btn btn-warning">
🪑 Ver Catálogo
</a>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>