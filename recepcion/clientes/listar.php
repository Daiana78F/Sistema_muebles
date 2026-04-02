<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

/* Permitir Admin y Recepción */
if (!isset($_SESSION["id_usuario"]) || 
   ($_SESSION["id_rol"] != 2 && $_SESSION["id_rol"] != 1)) {

    header("Location: ../../public/login.php");
    exit();
}

$sql = "SELECT * FROM clientes ORDER BY apellido, nombre";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Clientes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h1 class="mb-4">Listado de Clientes</h1>

<!-- MENSAJE -->
<?php if(isset($_GET['error']) && $_GET['error']=="con_pedidos"){ ?>
<div class="alert alert-danger">
⚠ No se puede eliminar el cliente porque tiene pedidos asociados.
</div>
<?php } ?>

<!-- BOTÓN -->
<?php if($_SESSION["id_rol"] == 2){ ?>
<a href="nuevo.php" class="btn btn-primary mb-3">
➕ Nuevo Cliente
</a>
<?php } ?>

<!-- 🔎 BUSCADOR -->
<input type="text" id="buscador" class="form-control mb-3" placeholder="🔎 Buscar cliente...">

<table class="table table-striped table-bordered" id="tablaClientes">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Apellido</th>
<th>Teléfono</th>
<th>Email</th>
<th>Fecha registro</th>

<?php if($_SESSION["id_rol"] == 2){ ?>
<th>Acciones</th>
<?php } ?>

</tr>
</thead>

<tbody>

<?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
<tr>

<td><?= $fila["id_cliente"]; ?></td>

<td class="nombre"><?= $fila["nombre"]; ?></td>

<td class="apellido"><?= $fila["apellido"]; ?></td>

<td><?= $fila["telefono"]; ?></td>

<td><?= $fila["email"]; ?></td>

<td><?= $fila["fecha_registro"]; ?></td>

<?php if($_SESSION["id_rol"] == 2){ ?>
<td>

<a href="editar.php?id=<?= $fila['id_cliente']; ?>"
   class="btn btn-warning btn-sm">
Editar
</a>

<a href="eliminar.php?id=<?= $fila['id_cliente']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Seguro que querés eliminar este cliente?');">
Eliminar
</a>

</td>
<?php } ?>

</tr>
<?php } ?>

</tbody>
</table>

<!-- VOLVER -->
<?php if($_SESSION["id_rol"] == 1){ ?>
<a href="/sistema_muebles/admin/dashboard.php"
   class="btn btn-secondary mt-3">
⬅ Volver al panel
</a>
<?php } else { ?>
<a href="/sistema_muebles/recepcion/dashboard.php"
   class="btn btn-secondary mt-3">
⬅ Volver al panel
</a>
<?php } ?>

</div>

<!-- 🔥 SCRIPT BUSCADOR -->
<script>
document.getElementById("buscador").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaClientes tbody tr");

    filas.forEach(function(fila) {
        let nombre = fila.querySelector(".nombre").textContent.toLowerCase();
        let apellido = fila.querySelector(".apellido").textContent.toLowerCase();

        if(nombre.includes(filtro) || apellido.includes(filtro)){
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
});
</script>

</body>
</html>