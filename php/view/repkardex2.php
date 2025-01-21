<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/RepkardexController.php';

$controller = new RepkardexController();

// Obtener filtros de la solicitud GET
$fechaInicio = $_GET['fechaInicio'] ?? date('Y-m-d');
$fechaFin = $_GET['fechaFin'] ?? date('Y-m-d');
$idOper = $_GET['idOper'] ?? null;
$idProducto = $_GET['idProducto'] ?? null;

// Construir array de filtros
$filters = [
    'idOper' => $idOper,
    'fechaInicio' => $fechaInicio,
    'fechaFin' => $fechaFin,
    'idProducto' => $idProducto
];

// Obtener datos filtrados del Kardex
$data = $controller->getFiltered($filters);
// echo '<pre>';
// print_r($data);
// echo '</pre>';
?>

<!-- Formulario de filtros -->
<div class="filter-container">
    <h2>📦 Filtros de Entradas y Salidas</h2>
    <label for="fechaInicio">Fecha Inicio:</label>
    <input type="date" id="fechaInicio" value="<?php echo htmlspecialchars($fechaInicio); ?>">

    <label for="fechaFin">Fecha Fin:</label>
    <input type="date" id="fechaFin" value="<?php echo htmlspecialchars($fechaFin); ?>">

    <label for="idOper">Tipo de Operación:</label>
    <select id="idOper">
        <option value="">Todos</option>
        <option value="1000" <?php echo ($idOper == '1000') ? 'selected' : ''; ?>>Ingreso</option>
        <option value="1001" <?php echo ($idOper == '1001') ? 'selected' : ''; ?>>Salida</option>
    </select>

    <label for="idProducto">ID Producto:</label>
    <input type="number" id="idProducto" placeholder="Ingrese ID de producto" value="<?php echo htmlspecialchars($idProducto ?? ''); ?>">

    <button id="btnFiltrar">🔍 Filtrar</button>
</div>

<!-- Tabla de Resultados -->
<table class="kardex-table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Operación</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Unidades Medida</th>
        </tr>
    </thead>
    <tbody id="kardexResults">
        <?php if (!empty($data)) : ?>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['fecha'] ?? 'N/A'); ?></td>
                    <td><?php echo isset($row['idoper']) ? (($row['idoper'] == 1000) ? 'Ingreso' : 'Salida') : 'Desconocido'; ?></td>
                    <td><?php echo htmlspecialchars($row['nompro'] ?? 'Sin Información'); ?></td>
                    <td><?php echo htmlspecialchars($row['qty'] ?? '0'); ?></td>
                    <td><?php echo htmlspecialchars($row['nomunidad'] ?? '0'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">No se encontraron registros.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    document.getElementById("btnFiltrar").addEventListener("click", function () {
        const fechaInicio = document.getElementById("fechaInicio").value;
        const fechaFin = document.getElementById("fechaFin").value;
        const idOper = document.getElementById("idOper").value;
        const idProducto = document.getElementById("idProducto").value;

        let url = `repkardex2.php?fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;

        if (idOper) {
            url += `&idOper=${idOper}`;
        }
        if (idProducto) {
            url += `&idProducto=${idProducto}`;
        }

        $("#showinfo").html('<p class="loading">Cargando reporte...</p>');
        $("#showinfo").load(url);
    });
</script>

<style>
    .filter-container {
        margin-bottom: 20px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
    }

    .filter-container label {
        margin-right: 5px;
        font-weight: bold;
    }

    .filter-container input, .filter-container select {
        margin-right: 10px;
        padding: 5px;
    }

    .filter-container button {
        background-color: #3498DB;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
    }

    .filter-container button:hover {
        background-color: #1ABC9C;
    }

    .kardex-table {
        width: 100%;
        border-collapse: collapse;
    }

    .kardex-table th, .kardex-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .kardex-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }
</style>
