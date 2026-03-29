<?php if(isset($_GET['error']) && $_GET['error']=="con_pedidos"){ ?>
<div class="alert alert-danger">
⚠ No se puede eliminar el mueble porque está asociado a pedidos existentes.
</div>
<?php } ?><?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

/* Permitir Recepción (2) y Admin (1) */
if (!isset($_SESSION["id_usuario"]) || 
   ($_SESSION["id_rol"] != 2 && $_SESSION["id_rol"] != 1)) {

    header("Location: ../../public/login.php");
    exit();
}

$buscar = $_GET['buscar'] ?? '';

if($buscar != ""){
    $sql = "SELECT * FROM muebles
            WHERE nombre LIKE '%$buscar%'
            ORDER BY nombre";
}else{
    $sql = "SELECT * FROM muebles
            ORDER BY nombre";
}
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogo de Muebles</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>🪑 Catálogo de Muebles</h2>

    <a href="nuevo.php" class="btn btn-primary">
        ➕ Nuevo Mueble
    </a>
</div>

<input type="text" id="buscador" class="form-control mb-3" placeholder="🔎 Buscar mueble...">

<div class="table-responsive">
<table class="table table-hover align-middle shadow-sm">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Precio</th>
<th>Características</th>
<th class="text-center">Acciones</th>
</tr>
</thead>

<tbody id="tablaMuebles">

<?php while($row = mysqli_fetch_assoc($resultado)) { ?>

<tr>
<td><?php echo $row['id_mueble']; ?></td>

<td class="fw-semibold">
<?php echo $row['nombre']; ?>
</td>

<td><?php echo $row['descripcion']; ?></td>

<td class="text-success fw-bold">
$<?php echo number_format($row['precio_base'],2); ?>
</td>

<td><?php echo $row['caracteristicas']; ?></td>

<td class="text-center">
<a href="editar.php?id=<?php echo $row['id_mueble']; ?>" 
   class="btn btn-warning btn-sm me-1">
   ✏️
</a>

<a href="eliminar.php?id=<?php echo $row['id_mueble']; ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Eliminar este mueble?')">
   🗑
</a>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>

<a href="../dashboard.php" class="btn btn-secondary mt-3">
⬅ Volver al panel
</a>

</div>