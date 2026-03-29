<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../../public/login.php");
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_muebles");

$sql = "SELECT 
p.id_pedido,
p.id_estado,
c.nombre,
c.apellido,
m.nombre AS mueble,
dp.cantidad,
p.fecha_pedido,
p.fecha_estimada,
p.sena,
e.nombre_estado AS estado

FROM pedidos p

JOIN clientes c ON p.id_cliente = c.id_cliente
JOIN estados_pedido e ON p.id_estado = e.id_estado
LEFT JOIN detalle_pedido dp ON p.id_pedido = dp.id_pedido
LEFT JOIN muebles m ON dp.id_mueble = m.id_mueble

ORDER BY p.id_pedido DESC";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pedidos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.badge-pendiente { background:#0d6efd; }
.badge-fabricacion { background:#ffc107; color:black; }
.badge-listo { background:#198754; }
.badge-retirado { background:#212529; }
.badge-cancelado { background:#dc3545; }
</style>

</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="mb-3">📦 Listado de Pedidos</h2>

<div class="d-flex justify-content-between mb-3">
<input type="text" id="buscador" class="form-control w-50" placeholder="🔎 Buscar cliente...">

<?php if($_SESSION["id_rol"] == 2): ?>
<a href="nuevo.php" class="btn btn-primary">
➕ Nuevo Pedido
</a>
<?php endif; ?>
</div>

<table class="table table-hover table-bordered" id="tablaPedidos">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Cliente</th>
<th>Mueble</th>
<th>Cantidad</th>
<th>Seña</th>
<th>Fecha</th>
<th>Entrega</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($resultado)) { ?>

<tr>

<td><?= $row['id_pedido'] ?></td>

<td class="cliente">
<?= $row['apellido']." ".$row['nombre'] ?>
</td>

<td>
<?php echo $row['mueble'] ?? "Sin muebles"; ?>
</td>

<td>
<?php echo $row['cantidad'] ?? "-"; ?>
</td>

<td>$<?= $row['sena'] ?></td>
<td><?= $row['fecha_pedido'] ?></td>
<td><?= $row['fecha_estimada'] ?></td>

<td>
<?php
$estado = $row['estado'];
$clase = "";

if($estado=="Pendiente") $clase="badge-pendiente";
if($estado=="En fabricación") $clase="badge-fabricacion";
if($estado=="Listo para retirar") $clase="badge-listo";
if($estado=="Retirado") $clase="badge-retirado";
if($estado=="Cancelado") $clase="badge-cancelado";
?>

<span class="badge <?= $clase ?>">
<?= $estado ?>
</span>
</td>

<td>

<?php if($_SESSION["id_rol"] == 2): ?>

<?php if($row['id_estado'] < 4): ?>

<a href="cambiar_estado.php?id=<?= $row['id_pedido'] ?>"
   class="btn btn-success btn-sm">
Avanzar Estado
</a>

<a href="cancelar.php?id=<?= $row['id_pedido'] ?>"
   class="btn btn-danger btn-sm">
Cancelar
</a>

<?php endif; ?>

<?php endif; ?>

</td>

</tr>

<?php } ?>

</tbody>
</table>

<a href="../dashboard.php" class="btn btn-secondary mt-3">
⬅ Volver
</a>

</div>

<!-- 🔎 BUSCADOR -->
<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaPedidos tbody tr");

    filas.forEach(function(fila) {
        let cliente = fila.querySelector(".cliente").textContent.toLowerCase();

        fila.style.display = cliente.includes(filtro) ? "" : "none";
    });
});
</script>

</body>
</html>