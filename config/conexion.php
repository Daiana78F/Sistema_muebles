<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sistema_muebles";

$conexion = new mysqli("localhost", "root", "", "sistema_muebles");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>