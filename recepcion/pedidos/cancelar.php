<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

/* solo recepcionista puede cancelar */
if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

/* verificar id recibido */
if (!isset($_GET["id"])) {
    header("Location: listar.php");
    exit();
}

$id = (int) $_GET["id"];

/* evitar cancelar pedidos ya finalizados */
$check = mysqli_query($conexion,
"SELECT id_estado FROM pedidos WHERE id_pedido = $id");

$pedido = mysqli_fetch_assoc($check);

if ($pedido["id_estado"] == 4 || $pedido["id_estado"] == 5) {
    header("Location: listar.php");
    exit();
}

/* cambiar estado a CANCELADO (5) */
mysqli_query($conexion,
"UPDATE pedidos SET id_estado = 5 WHERE id_pedido = $id");

header("Location: listar.php");
exit();
?>