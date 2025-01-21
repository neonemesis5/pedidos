<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/FacturaCompraController.php';
require_once __DIR__ . '/../controller/DetalleCompraController.php';

$facturaController = new FacturaCompraController();
$detalleController = new DetalleCompraController();

// Si se solicita el detalle de una factura específica
if (isset($_GET['detalleFacturaId'])) {
    $facturaId = $_GET['detalleFacturaId'];
    $detalles = $detalleController->getDetallesByFacturaCompra($facturaId,true);

    if (!empty($detalles)) {
    ?>
    <h3>Detalles de Factura ID: <?php echo htmlspecialchars($facturaId); ?></h3>
    <table class="facturas-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($detalles as $detalle) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($detalle['qty']); ?></td>
                        <td>$<?php echo number_format($detalle['precioc'], 2, ',', '.'); ?></td>
                        <td>$<?php echo number_format($detalle['total'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
<?php 
    } else {
        echo "<p>No se encontraron detalles para esta factura.</p>";
    }
    exit;
} 

// Obtener fecha desde GET
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Obtener facturas de compra según la fecha
$facturas = $facturaController->getAllFacturasCompra(null, $fecha);

?>

<!-- Tabla de Facturas de Compra -->
<h2><b>🛍 Reporte de Compras</b></h2>
<p style="font-style: italic; text-align: center;">Últimos Compras Realizadas</p>
<table class="facturas-table">
    <thead>
        <tr>
            <th>ID Factura</th>
            <th>Proveedor</th>
            <th>Moneda</th>
            <th>Nro Factura</th>
            <th>Fecha/Hora</th>
            <th>Monto</th>
            <th>Estatus</th>
            <th>Detalle de la Factura</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($facturas)) : ?>
            <?php foreach ($facturas as $factura) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($factura['id']); ?></td>
                    <td><?php echo htmlspecialchars($factura['nomprov']); ?></td>
                    <td><?php echo htmlspecialchars($factura['nommoneda']); ?></td>
                    <td><?php echo htmlspecialchars($factura['nrofactura']); ?></td>
                    <td><?php echo htmlspecialchars($factura['fecha']); ?></td>
                    <td><?php echo number_format($factura['total'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($factura['status']); ?></td>
                    <td>
                        <button class="ver-detalle" data-id="<?php echo $factura['id']; ?>">Ver Detalle</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="8">No se encontraron registros.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Contenedor para mostrar el detalle de la compra -->
<h2>📑 Detalle del Compra</h2>
<div id="detalle-compra">
    <p>Seleccione una factura para ver su detalle.</p>
</div>

<script>
    $(document).ready(function () {
        $(".ver-detalle").click(function () {
            let facturaId = $(this).data("id");
            $("#detalle-compra").html('<p class="loading">Cargando detalle...</p>');
            
            $.ajax({
                url: "repcompras2.php",
                type: "GET",
                data: { detalleFacturaId: facturaId },
                success: function (response) {
                    $("#detalle-compra").html(response);
                },
                error: function () {
                    $("#detalle-compra").html('<p class="loading">Error al cargar el detalle.</p>');
                }
            });
        });
    });
</script>

<style>
    .facturas-table {
        width: 100%;
        border-collapse: collapse;
    }
    .facturas-table th, .facturas-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    .facturas-table th {
        background-color: #f4f4f4;
        font-weight: bold;
    }
    .ver-detalle {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
    }
    .ver-detalle:hover {
        background-color: #0056b3;
    }
    #detalle-compra {
        margin-top: 20px;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
    }
</style>

