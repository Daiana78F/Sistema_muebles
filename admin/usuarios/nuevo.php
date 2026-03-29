<?php 
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if ($_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

$error = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $usuario = $_POST["usuario"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $id_rol = $_POST["id_rol"];

    mysqli_query($conexion,
    "INSERT INTO usuarios(usuario,password,id_rol)
    VALUES('$usuario','$password','$id_rol')");

    header("Location: listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nuevo Usuario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<h1>Nuevo Usuario</h1>

<?php if($error != ""): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="card p-4">

<input name="usuario" class="form-control mb-3" placeholder="Usuario" required>

<input name="password" type="password" class="form-control mb-3" placeholder="Contraseña" required>

<select name="id_rol" class="form-select mb-3">
<option value="1">Administrador</option>
<option value="2">Recepción</option>
</select>

<button class="btn btn-success">Crear</button>

</form>

</div>

</body>
</html>