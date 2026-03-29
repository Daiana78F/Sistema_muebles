<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 1) {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id_eliminar = $_GET["id"];
$id_actual = $_SESSION["id_usuario"];

/* Evitar que el admin se elimine a sí mismo */
if ($id_eliminar == $id_actual) {
    header("Location: listar.php?error=autoeliminacion");
    exit();
}

/* Eliminar usuario */
mysqli_query($conexion,
    "DELETE FROM usuarios WHERE id_usuario = $id_eliminar");

header("Location: listar.php?ok=eliminado");
exit();