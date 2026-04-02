<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 2) {
    header("Location: ../../public/login.php");
    exit();
}

/* 🔥 AHORA USAMOS id_detalle */
$id_detalle = $_GET["id"];

/* Obtener estado actual del DETALLE */
$res = mysqli_query($conexion, "
    SELECT id_estado 
    FROM detalle_pedido 
    WHERE id_detalle = $id_detalle
");

$detalle = mysqli_fetch_assoc($res);

$estado_actual = $detalle['id_estado'];

/* 🚫 NO avanzar si ya está en Retirado (4) */
if ($estado_actual < 4) {

    $nuevo_estado = $estado_actual + 1;

    mysqli_query($conexion, "
        UPDATE detalle_pedido 
        SET id_estado = $nuevo_estado 
        WHERE id_detalle = $id_detalle
    ");
}

header("Location: listar.php");
exit();