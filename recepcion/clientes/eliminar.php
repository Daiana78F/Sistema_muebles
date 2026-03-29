<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

$id = $_GET["id"];

/* VERIFICAR SI TIENE PEDIDOS */
$check = mysqli_query($conexion,
    "SELECT COUNT(*) AS total FROM pedidos WHERE id_cliente = $id");

$fila = mysqli_fetch_assoc($check);

if($fila['total'] > 0){
    /* tiene pedidos → no borrar */
    header("Location: listar.php?error=con_pedidos");
    exit();
}

/* eliminar si no tiene pedidos */
mysqli_query($conexion,
    "DELETE FROM clientes WHERE id_cliente = $id");

header("Location: listar.php");
exit();
?>