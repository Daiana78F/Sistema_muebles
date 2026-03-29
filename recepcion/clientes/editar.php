<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET["id"];
$mensaje = "";

/* Obtener cliente */
$resultado = mysqli_query($conexion,
    "SELECT * FROM clientes WHERE id_cliente = $id");

$cliente = mysqli_fetch_assoc($resultado);

/* Actualizar datos */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $email = trim($_POST["email"]);

    $sql = "UPDATE clientes SET
            nombre='$nombre',
            apellido='$apellido',
            telefono='$telefono',
            email='$email'
            WHERE id_cliente=$id";

    if(mysqli_query($conexion,$sql)){
        header("Location: listar.php");
        exit();
    } else {
        $mensaje = "Error al actualizar cliente";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Cliente</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h2 class="mb-4">✏ Editar Cliente</h2>

<div class="card shadow-sm">
<div class="card-body">

<form method="POST">

<div class="mb-3">
<label class="form-label">Nombre</label>
<input type="text" name="nombre"
       class="form-control"
       value="<?php echo $cliente['nombre']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Apellido</label>
<input type="text" name="apellido"
       class="form-control"
       value="<?php echo $cliente['apellido']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Teléfono</label>
<input type="text" name="telefono"
       class="form-control"
       value="<?php echo $cliente['telefono']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email"
       class="form-control"
       value="<?php echo $cliente['email']; ?>">
</div>

<button type="submit" class="btn btn-success">
💾 Guardar Cambios
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