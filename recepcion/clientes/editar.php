<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

/* VALIDAR ID */
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id = $_GET["id"];
$mensaje = "";

/* OBTENER CLIENTE */
$stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$cliente = $resultado->fetch_assoc();

if (!$cliente) {
    header("Location: listar.php");
    exit();
}

/* ACTUALIZAR */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $telefono = trim($_POST["telefono"]);
    $email = trim($_POST["email"]);

    if (empty($nombre) || empty($apellido) || empty($telefono)) {
        $mensaje = "Todos los campos obligatorios deben completarse";
    } else {

        /* 🔒 VALIDAR EMAIL DUPLICADO */
        $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE email = ? AND id_cliente != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        $resEmail = $stmt->get_result();

        if ($resEmail->num_rows > 0) {
            $mensaje = "⚠️ Ese email ya está registrado en otro cliente";
        } else {

            /* 🔒 VALIDAR TELÉFONO DUPLICADO */
            $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE telefono = ? AND id_cliente != ?");
            $stmt->bind_param("si", $telefono, $id);
            $stmt->execute();
            $resTel = $stmt->get_result();

            if ($resTel->num_rows > 0) {
                $mensaje = "⚠️ Ese teléfono ya está registrado en otro cliente";
            } else {

                /* UPDATE SEGURO */
                $stmt = $conexion->prepare("UPDATE clientes 
                    SET nombre=?, apellido=?, telefono=?, email=? 
                    WHERE id_cliente=?");

                $stmt->bind_param("ssssi", $nombre, $apellido, $telefono, $email, $id);

                if ($stmt->execute()) {
                    header("Location: listar.php");
                    exit();
                } else {
                    $mensaje = "Error al actualizar cliente";
                }
            }
        }
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

<h2 class="mb-4">✏️ Editar Cliente</h2>

<div class="card shadow-sm">
<div class="card-body">

<?php if(!empty($mensaje)): ?>
<div class="alert alert-danger">
    <?= $mensaje ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Nombre</label>
<input type="text" name="nombre"
       class="form-control"
       value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Apellido</label>
<input type="text" name="apellido"
       class="form-control"
       value="<?= htmlspecialchars($cliente['apellido']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Teléfono</label>
<input type="text" name="telefono"
       class="form-control"
       value="<?= htmlspecialchars($cliente['telefono']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email"
       class="form-control"
       value="<?= htmlspecialchars($cliente['email']) ?>">
</div>

<button type="submit" class="btn btn-success">
💾 Guardar Cambios
</button>

<a href="listar.php" class="btn btn-secondary">
⬅ Volver
</a>

</form>

</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>