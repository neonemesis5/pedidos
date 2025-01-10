<?php
require_once __DIR__ . '/../controller/FormaPagoController.php';
require_once __DIR__ . '/../controller/PedidoController.php';
require_once __DIR__ . '/../controller/TasaController.php';
// Validar el ID del pedido recibido por GET
$pedidoId = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : null;

if (!$pedidoId) {
    die("ID del pedido no proporcionado.");
}

// Instanciar el controlador
$formaPagoController = new FormaPagoController();
$tasaController = new TasaController();
try {
    // Obtener las formas de pago como array
    $formasPago = $formaPagoController->getAllFormasPagoArray();

    if (!is_array($formasPago) || empty($formasPago)) {
        throw new Exception("No se encontraron formas de pago.");
    }
} catch (Exception $e) {
    die("Error al cargar las formas de pago: " . $e->getMessage());
}

try {
    // Obtener las tasas actuales
    $tasas = $tasaController->getCurrentRates2();
    $tasaUSD = $tasas['USD_COP'] ?? 1; // Ejemplo: Tasa USD a COP
    $tasaVES = $tasas['COP_BSS'] ?? 1; // Ejemplo: Tasa VES a COP
} catch (Exception $e) {
    die("Error al cargar las tasas: " . $e->getMessage());
}
try {
    $Pedido = new PedidoController();
    $AMT_Pedido=$Pedido->getMontoTotalByID($pedidoId);
    if (!is_numeric($AMT_Pedido)) {
        $AMT_Pedido = 0; // Asignamos 0 si no es numérico
    }
} catch (\Throwable $th) {
    die("Error al cargar total de Pedido de pago: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Formas de Pago</title>
    <script>
        // Pasar el ID del pedido al script JS
        const pedidoId = <?php echo json_encode($pedidoId); ?>;
    </script>
    <script src="../../js/forma_pago.js" defer></script>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <h1>Registrar Formas de Pago</h1>
    <h3>Pedido NRO: <?php echo htmlspecialchars($pedidoId); ?></h3>
    <form id="formaPagoForm">
        <table>
            <thead>
                <tr>
                    <th>Forma de Pago</th>
                    <th>COP</th>
                    <th>USD</th>
                    <th>VES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($formasPago as $forma): ?>
                <tr>
                    <td><?php echo htmlspecialchars($forma['nombre']); ?></td>
                    <td><input type="number" step="0.01" class="pago-input" data-moneda="COP" data-forma-id="<?php echo $forma['id']; ?>"></td>
                    <td><input type="number" step="0.01" class="pago-input" data-moneda="USD" data-forma-id="<?php echo $forma['id']; ?>"></td>
                    <td><input type="number" step="0.01" class="pago-input" data-moneda="BSS" data-forma-id="<?php echo $forma['id']; ?>"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>TOTAL COMPRA</strong></td>
                    <td id="totalCompraCOP"><?php echo htmlspecialchars(number_format($AMT_Pedido,2)); ?></td>
                    <td id="totalCompraUSD"><?php echo htmlspecialchars(number_format($AMT_Pedido / $tasaUSD,2)); ?></td>
                    <td id="totalCompraVES"><?php echo htmlspecialchars(number_format($AMT_Pedido * $tasaVES,2)); ?></td>
                </tr>
                <tr>
                    <td><strong>PAGADO</strong></td>
                    <td id="pagadoCOP">0</td>
                    <td id="pagadoUSD">0</td>
                    <td id="pagadoVES">0</td>
                </tr>
                <tr>
                    <td><strong>POR PAGAR</strong></td>
                    <td id="porPagarCOP">0</td>
                    <td id="porPagarUSD">0</td>
                    <td id="porPagarVES">0</td>
                </tr>
            </tfoot>
        </table>

        <div id="extras">
            <label>
                <input type="checkbox" id="pendienteCobrar"> Pendiente x Cobrar Cliente
            </label>
            <label>
                <input type="checkbox" id="valeEmpleados"> Vale Empleados
            </label>
            <label>
                <input type="checkbox" id="organismos"> Organismos
            </label>
        </div>

        <button type="button" id="pagoRecibido" disabled>PAGO RECIBIDO</button>
    </form>
</body>
</html>
