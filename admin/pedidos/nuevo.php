<?php
session_start();
require_once(__DIR__ . "/../../config/conexion.php");

if (!isset($_SESSION["id_usuario"])) {
    echo "SIN SESION";
    exit();
}

/* Obtener datos */
$clientes = mysqli_query($conexion,
"SELECT id_cliente, nombre, apellido 
 FROM clientes 
 ORDER BY apellido ASC, nombre ASC");

$muebles = mysqli_query($conexion,
"SELECT id_mueble, nombre FROM muebles");

/* ===== GUARDAR PEDIDO ===== */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_cliente = $_POST["id_cliente"];
    $muebles_seleccionados = $_POST["id_mueble"] ?? [];
    $cantidades = $_POST["cantidad"] ?? [];
    $sena = $_POST["sena"];
    $fecha_estimada = $_POST["fecha_estimada"];

    $fecha_pedido = date("Y-m-d");
    $id_estado = 1;

    $sql = "INSERT INTO pedidos
    (id_cliente, fecha_pedido, fecha_estimada, sena, id_estado)
    VALUES
    ('$id_cliente','$fecha_pedido','$fecha_estimada','$sena','$id_estado')";

    if (!mysqli_query($conexion, $sql)) {
        die("ERROR PEDIDO: " . mysqli_error($conexion));
    }

    $id_pedido = mysqli_insert_id($conexion);

    for ($i = 0; $i < count($muebles_seleccionados); $i++) {

        $id_mueble = $muebles_seleccionados[$i];
        $cantidad = $cantidades[$i];

        if ($cantidad > 0) {

            $sql_detalle = "INSERT INTO detalle_pedido
            (id_pedido, id_mueble, cantidad)
            VALUES
            ('$id_pedido','$id_mueble','$cantidad')";

            mysqli_query($conexion, $sql_detalle);
        }
    }

    header("Location: listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo Pedido</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require_once(__DIR__ . "/../../includes/navbar.php"); ?>

<div class="container mt-4">

<div class="card shadow-lg">
<div class="card-body">

<h3 class="mb-4">➕ Registrar Nuevo Pedido</h3>

<form method="POST">

<!-- CLIENTE -->
<div class="mb-3">
<label class="form-label">Cliente</label>

<input type="text" id="buscadorCliente" class="form-control mb-2" placeholder="🔎 Buscar cliente...">

<select name="id_cliente" id="selectCliente" class="form-select" size="5" required>
<?php while($c = mysqli_fetch_assoc($clientes)) { ?>
<option value="<?php echo $c['id_cliente']; ?>">
<?php echo $c['apellido']." ".$c['nombre']; ?>
</option>
<?php } ?>
</select>
</div>

<hr>

<!-- MUEBLES -->
<h5 class="mt-3">🪑 Muebles del pedido</h5>

<input type="text" id="buscador" class="form-control mb-3" placeholder="🔎 Buscar mueble...">

<div id="resultados" class="list-group mb-3"></div>

<h6>Seleccionados:</h6>
<div id="seleccionados"></div>

<hr>

<!-- SEÑA -->
<div class="mb-3">
<label class="form-label">Seña</label>
<input type="number" name="sena" class="form-control" placeholder="$" required>
</div>

<!-- FECHA -->
<div class="mb-3">
<label class="form-label">Fecha estimada</label>
<input type="date" name="fecha_estimada" class="form-control" required>
</div>

<button class="btn btn-success w-100">
Crear Pedido
</button>

</form>

</div>
</div>

<a href="listar.php" class="btn btn-secondary mt-3">
⬅ Volver
</a>

</div>

<script>
// =========================
// MUEBLES
// =========================
let muebles = [
<?php 
mysqli_data_seek($muebles, 0);
while($m = mysqli_fetch_assoc($muebles)) {
    echo "{id: ".$m['id_mueble'].", nombre: '".$m['nombre']."'},";
}
?>
];

let seleccionados = [];

document.getElementById("buscador").addEventListener("keyup", function() {

    let texto = this.value.toLowerCase();
    let resultados = document.getElementById("resultados");
    resultados.innerHTML = "";

    if(texto.length == 0) return;

    muebles.forEach(m => {
        if(m.nombre.toLowerCase().includes(texto)) {

            let item = document.createElement("button");
            item.className = "list-group-item list-group-item-action";
            item.innerText = m.nombre;

            item.onclick = () => agregarMueble(m);

            resultados.appendChild(item);
        }
    });

});

function agregarMueble(mueble) {

    if(seleccionados.find(m => m.id == mueble.id)) return;

    seleccionados.push({...mueble, cantidad: 1});

    renderSeleccionados();
}

function renderSeleccionados() {

    let contenedor = document.getElementById("seleccionados");
    contenedor.innerHTML = "";

    seleccionados.forEach((m, index) => {

        contenedor.innerHTML += `
        <div class="row mb-2">
            <div class="col-5">${m.nombre}</div>
            <div class="col-4">
                <input type="number" name="cantidad[]" value="${m.cantidad}" min="1" class="form-control">
            </div>
            <div class="col-3">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(${index})">X</button>
            </div>

            <input type="hidden" name="id_mueble[]" value="${m.id}">
        </div>
        `;
    });
}

function eliminar(index) {
    seleccionados.splice(index,1);
    renderSeleccionados();
}

// =========================
// CLIENTES (FIX PRO)
// =========================

let select = document.getElementById("selectCliente");
let buscador = document.getElementById("buscadorCliente");

// Filtrar
buscador.addEventListener("input", function() {

    let texto = this.value.toLowerCase().trim();

    select.style.display = "block";

    let opciones = select.querySelectorAll("option");

    opciones.forEach(op => {

        let nombre = op.textContent.toLowerCase();

        if(nombre.includes(texto)){
            op.style.display = "block";
        } else {
            op.style.display = "none";
        }

    });

});

// Seleccionar
select.addEventListener("change", function() {

    let seleccionado = this.options[this.selectedIndex].text;

    buscador.value = seleccionado;

    select.style.display = "none";
});

// Si borra → volver a mostrar
buscador.addEventListener("keyup", function() {

    if(this.value === ""){
        select.style.display = "block";

        let opciones = select.querySelectorAll("option");
        opciones.forEach(op => op.style.display = "block");
    }

});
</script>

</body>
</html>