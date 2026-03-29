<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if ($_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

$resultado = mysqli_query($conexion,"SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">
    <?php if(isset($_GET['ok']) && $_GET['ok']=="eliminado"){ ?>
<div id="alerta-ok" class="alert alert-success">
✅ Usuario eliminado correctamente.
</div>
<?php } ?>

<div class="d-flex justify-content-between mb-3">
<h2>👥 Gestión de Usuarios</h2>

<a href="nuevo.php" class="btn btn-primary">
➕ Nuevo Usuario
</a>
</div>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-hover">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Usuario</th>
<th>Rol</th>
<th>Acciones</th>
</tr>
</thead>

<tbody>
<?php while($u = mysqli_fetch_assoc($resultado)) { ?>
<tr>

<td><?= $u['id_usuario'] ?></td>

<td>
<strong><?= $u['usuario'] ?></strong>
</td>

<td>
<?= ($u['id_rol']==1)
? '<span class="badge bg-dark">Administrador</span>'
: '<span class="badge bg-primary">Recepción</span>' ?>
</td>

<td>

<?php if($u['id_usuario'] != $_SESSION['id_usuario']) { ?>

<a href="eliminar.php?id=<?= $u['id_usuario'] ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('¿Eliminar este usuario?');">
Eliminar
</a>

<?php } else { ?>

<span class="text-muted">Usuario actual</span>

<?php } ?>

</td>

</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>
<a href="/sistema_muebles/admin/dashboard.php"
   class="btn btn-secondary mt-3">
⬅ Volver al panel
</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
setTimeout(function() {
    let alerta = document.getElementById("alerta-ok");
    if(alerta){
        alerta.style.transition = "opacity 0.5s";
        alerta.style.opacity = "0";
        setTimeout(() => alerta.remove(), 500);
    }
}, 3000);
</script>
</body>
</html>