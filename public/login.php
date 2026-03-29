<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once(__DIR__ . "/../config/conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty($_POST['g-recaptcha-response'])){
        $error = "Por favor verificá que no sos un robot.";
    } else {

        $secret = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";
        $response = $_POST['g-recaptcha-response'];

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
        $captcha_success = json_decode($verify);

        if (!$captcha_success->success) {
            $error = "Error al validar el captcha.";
        } else {

            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
            $resultado = mysqli_query($conexion, $sql);

            if (mysqli_num_rows($resultado) == 1) {

                $fila = mysqli_fetch_assoc($resultado);

                if (password_verify($password, $fila["password"])) {

                    $_SESSION["id_usuario"] = $fila["id_usuario"];
                    $_SESSION["usuario"] = $fila["usuario"];
                    $_SESSION["id_rol"] = $fila["id_rol"];

                    if ($fila["id_rol"] == 1) {
                        header("Location: ../admin/dashboard.php");
                    } else {
                        header("Location: ../recepcion/dashboard.php");
                    }
                    exit();

                } else {
                    $error = "Contraseña incorrecta";
                }

            } else {
                $error = "Usuario no encontrado";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.login-card{
    width:100%;
    max-width:420px;
    border-radius:15px;
}
</style>

</head>

<body>

<div class="card shadow-lg login-card">
<div class="card-body p-4">

<h3 class="text-center mb-2">
El Algarrobo
</h3>

<p class="text-center text-muted mb-4">
Sistema de Gestión de Carpintería
</p>

<form method="POST">

<input type="text" name="usuario" class="form-control mb-3" placeholder="Usuario" required>

<input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>

<div class="g-recaptcha mb-3" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

<button class="btn btn-primary w-100">Ingresar</button>

</form>

<?php if($error != ""): ?>
<div class="alert alert-danger mt-3 text-center"><?php echo $error; ?></div>
<?php endif; ?>

</div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>