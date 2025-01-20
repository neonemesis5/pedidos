<?php
require_once __DIR__ . '/../controller/RepAdminController.php';
$controller = new RepAdminController();

// Obtener la fecha desde GET, si no está definida, usar la fecha de hoy
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Inicializar los reportes por tipo
$resC = $controller->getVentasDiaria($fecha, 'C');
$resI = $controller->getVentasDiaria($fecha, 'I');
$resO = $controller->getVentasDiaria($fecha, 'O');
$resP = $controller->getVentasDiaria($fecha, 'P');
$resA = $controller->getVentasDiaria($fecha, 'A');
?>

<h1>REPORTE DE VENTAS (MERCANCÍA) MOVIMIENTO DÍA</h1>

<!-- Opciones de Filtrado -->
<div style="text-align: center; margin-bottom: 20px;">
    <h4>Seleccione los Resultados a Mostrar</h4>
    <label><input type="checkbox" class="report-checkbox" data-target="tableC" checked> Ventas Tipo C</label>
    <label><input type="checkbox" class="report-checkbox" data-target="tableI" checked> Ventas Tipo I</label>
    <label><input type="checkbox" class="report-checkbox" data-target="tableO" checked> Ventas Tipo O</label>
    <label><input type="checkbox" class="report-checkbox" data-target="tableP" checked> Ventas Tipo P</label>
    <label><input type="checkbox" class="report-checkbox" data-target="tableA" checked> Ventas Tipo A</label>
</div>

<?php
$reportes = [
    'C' => $resC,
    'I' => $resI,
    'O' => $resO,
    'P' => $resP,
    'A' => $resA
];

$_totalGeneral = 0;
foreach ($reportes as $tipo => $reporte) {
    if (!empty($reporte)) {
        $subtotal = array_sum(array_column($reporte, 'total_venta'));
        echo "<div class='reporte-container' id='table$tipo' style='display: block;'>"; // Asegura que esté visible por defecto
        echo "<table>";
        echo "<thead>";
        echo "<tr><th colspan='5'>VENTAS PAGADAS (TIPO $tipo)</th></tr>";
        echo "<tr><th>TIPO DE PRODUCTO</th><th>PRODUCTO</th><th>CANTIDAD</th><th>P UNITARIO</th><th>P TOTAL</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($reporte as $fila) {
            echo "<tr>";
            echo "<td>{$fila['tipo_producto']}</td>";
            echo "<td>{$fila['producto']}</td>";
            echo "<td>{$fila['qty_total']}</td>";
            echo "<td>$" . number_format($fila['precio_unitario'], 2, ',', '.') . "</td>";
            echo "<td>$" . number_format($fila['total_venta'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        $_totalGeneral += $subtotal;
        echo "<tr><td colspan='4' class='subtotal'>SUBTOTAL</td><td>$" . number_format($subtotal, 2, ',', '.') . "</td></tr>";
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    }
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Manejar la visibilidad de los reportes según los checkboxes
    const checkboxes = document.querySelectorAll(".report-checkbox");

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function() {
            const targetTable = document.getElementById(this.dataset.target);
            if (targetTable) {
                targetTable.style.display = this.checked ? "block" : "none";
            }
        });
    });
});
</script>
