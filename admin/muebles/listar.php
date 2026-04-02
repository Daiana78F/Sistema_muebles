<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

/* CONEXIÓN */
$conexion = mysqli_connect("localhost", "root", "", "sistema_muebles");

/* TRAER MUEBLES */
$sql = "SELECT * FROM muebles ORDER BY id_mueble DESC";
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

<h2 class="mb-3">🪑 Catálogo de Muebles</h2>

<div class="d-flex justify-content-between mb-3">
<input type="text" id="buscador" class="form-control w-50" placeholder="🔎 Buscar mueble...">

<a href="nuevo.php" class="btn btn-success">
➕ Nuevo Mueble
</a>
</div>

<table class="table table-hover table-bordered" id="tablaMuebles">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Precio</th>
<th>Características</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>

<?php if($resultado && mysqli_num_rows($resultado) > 0): ?>

<?php while($row = mysqli_fetch_assoc($resultado)) { ?>

<tr>

<td><?= $row['id_mueble'] ?></td>

<td class="nombre">
<?= htmlspecialchars($row['nombre']) ?>
</td>

<td><?= htmlspecialchars($row['descripcion']) ?></td>

<td>$<?= $row['precio_base'] ?></td>

<td><?= htmlspecialchars($row['caracteristicas']) ?></td>

<td>

<a href="editar.php?id=<?= $row['id_mueble'] ?>" 
   class="btn btn-warning btn-sm">
✏️ Editar
</a>

<a href="eliminar.php?id=<?= $row['id_mueble'] ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Seguro que querés eliminar este mueble?')">
🗑 Eliminar
</a>

</td>

</tr>

<?php } ?>

<?php else: ?>
<tr>
<td colspan="6" class="text-center">No hay muebles cargados</td>
</tr>
<?php endif; ?>

</tbody>
</table>

<a href="../dashboard.php" class="btn btn-secondary mt-3">
⬅ Volver
</a>

</div>

<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaMuebles tbody tr");

    filas.forEach(function(fila) {
        let nombre = fila.querySelector(".nombre")?.textContent.toLowerCase() || "";
        fila.style.display = nombre.includes(filtro) ? "" : "none";
    });
});
</script>

</body>
</html>