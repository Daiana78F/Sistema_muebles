<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $email = trim($_POST["email"]);
    $fecha_registro = date("Y-m-d");

    /* 🔍 VALIDAR EMAIL */
    if (!empty($email)) {
        $check_email = mysqli_query($conexion,
            "SELECT * FROM clientes WHERE email = '$email'");

        if (mysqli_num_rows($check_email) > 0) {
            $mensaje = "⚠️ Ya existe un cliente con ese email.";
        }
    }

    /* 🔍 VALIDAR TELEFONO */
    if (empty($mensaje)) {
        $check_tel = mysqli_query($conexion,
            "SELECT * FROM clientes WHERE telefono = '$telefono'");

        if (mysqli_num_rows($check_tel) > 0) {
            $mensaje = "⚠️ Ya existe un cliente con ese teléfono.";
        }
    }

    /* SI TODO OK → INSERTAR */
    if (empty($mensaje)) {

        $sql = "INSERT INTO clientes (nombre, apellido, telefono, email, fecha_registro)
                VALUES ('$nombre', '$apellido', '$telefono', '$email', '$fecha_registro')";

        if (mysqli_query($conexion, $sql)) {
            header("Location: listar.php");
            exit();
        } else {
            $mensaje = "❌ Error al guardar el cliente";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Cliente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="mb-4">👤 Nuevo Cliente</h2>

<div class="card shadow-sm">
<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Nombre</label>
<input type="text" name="nombre" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Apellido</label>
<input type="text" name="apellido" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Teléfono</label>
<input type="text" name="telefono" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Email (opcional)</label>
<input type="email" name="email" class="form-control">
</div>

<button type="submit" class="btn btn-success">
💾 Guardar Cliente
</button>

<a href="listar.php" class="btn btn-secondary">
⬅ Volver
</a>

</form>

<?php if(!empty($mensaje)){ ?>
<div class="alert alert-danger mt-3">
<?php echo $mensaje; ?>
</div>
<?php } ?>

</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>