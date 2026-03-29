<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">

<a class="navbar-brand" href="#">
Sistema Muebles
</a>

<button class="navbar-toggler" type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav me-auto">

<?php if ($_SESSION["id_rol"] == 2) { ?>
<li class="nav-item">
<a class="nav-link"
href="/sistema_muebles/recepcion/clientes/listar.php">
Clientes
</a>
</li>
<?php } ?>

<?php if ($_SESSION["id_rol"] == 1) { ?>
<li class="nav-item">
<a class="nav-link"
href="/sistema_muebles/admin/dashboard.php">
Administración
</a>
</li>

<li class="nav-item">
<a class="nav-link"
href="/sistema_muebles/admin/usuarios/listar.php">
Usuarios
</a>
</li>
<?php } ?>

</ul>

<span class="navbar-text text-white me-3">
👤 <?php echo $_SESSION["usuario"]; ?>
</span>

<a class="btn btn-outline-light btn-sm"
href="/sistema_muebles/public/logout.php">
Cerrar sesión
</a>

</div>
</div>
</nav>