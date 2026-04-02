<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

$id_pedido = $_GET["id"];

/* Obtener estado actual */
$res = mysqli_query($conexion, "SELECT id_estado FROM pedidos WHERE id_pedido = $id_pedido");
$pedido = mysqli_fetch_assoc($res);

$estado_actual = $pedido['id_estado'];

/* 🚫 NO avanzar si ya está en Retirado (4) */
if ($estado_actual < 4) {

    $nuevo_estado = $estado_actual + 1;

    mysqli_query($conexion,
        "UPDATE pedidos 
         SET id_estado = $nuevo_estado 
         WHERE id_pedido = $id_pedido");
}

header("Location: listar.php");
exit();