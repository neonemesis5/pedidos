<?php
session_start();
require_once __DIR__ . '/../controller/FormaPagoController.php';
require_once __DIR__ . '/../controller/PedidoController.php';
require_once __DIR__ . '/../controller/TasaController.php';

$pedidoId = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : null;
if (!$pedidoId) {
    die("ID del pedido no proporcionado.");
}

// Instanciar los controladores
$formaPagoController = new FormaPagoController();
$tasaController = new TasaController();
$pedidoController = new PedidoController();

try {
    // Obtener formas de pago y tasas
    $formasPago = $formaPagoController->getAllFormasPagoArray();
    $tasas = $tasaController->getCurrentRates2();
    $AMT_Pedido = $pedidoController->getMontoTotalByID($pedidoId) ?? 0;

    echo '<pre>';
        print_r($tasas);
    echo '</pre>';
    $tasaUSD_COP = $tasas['USD_COP'] ?? 1;
    $tasaCOP_BSS = $tasas['COP_BSS'] ?? 1;
    $tasaUSD_BSS = $tasas['USD_BSS'] ?? 1;
} catch (Exception $e) {
    die("Error al cargar datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Formas de Pago</title>
</head>

<body>
    <h1>Registrar Formas de Pago</h1>
    <h3>Pedido NRO: <?php echo htmlspecialchars($pedidoId); ?></h3>
    <table>
        <tr>
            <th>Forma de Pago</th>
            <th>Monto</th>
        </tr>
        <tr>
            <td>Efectivo Pesos</td>
            <td><input id="ecop" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Efectivo Dólares</td>
            <td><input id="eusd" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Efectivo Bolívares</td>
            <td><input id="ebss" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Bancolombia</td>
            <td><input id="bcop" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Zelle</td>
            <td><input id="zusd" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Binance</td>
            <td><input id="busd" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Pago Móvil</td>
            <td><input id="bbss" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Punto de Venta</td>
            <td><input id="tbss" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td><strong>Total Pedido en COP</strong></td>
            <td><span id="totalPedido"><?php echo number_format($AMT_Pedido, 2); ?></span></td>
        </tr>
        <tr>
            <td><strong>Total Pagado en COP</strong></td>
            <td><span id="sumpagado">0.00</span></td>
        </tr>
        <tr>
            <td><strong>Sobrante o Faltante en COP</strong></td>
            <td><span id="diferencia">0.00</span></td>
        </tr>
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
    <button id="guardarPago">Guardar Pago</button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tasas obtenidas desde PHP
            const tasaUSD_COP = <?php echo $tasaUSD_COP; ?>;
            const tasaCOP_BSS = <?php echo $tasaCOP_BSS; ?>;
            const tasaUSD_BSS = <?php echo $tasaUSD_BSS; ?>;
            const totalPedidoCOP = <?php echo $AMT_Pedido; ?>;

            // Inputs
            const inputs = {
                ecop: document.getElementById("ecop"),
                eusd: document.getElementById("eusd"),
                ebss: document.getElementById("ebss"),
                bcop: document.getElementById("bcop"),
                zusd: document.getElementById("zusd"),
                busd: document.getElementById("busd"),
                bbss: document.getElementById("bbss"),
                tbss: document.getElementById("tbss"),
            };

            // Elementos de salida
            const totalPagadoCOPElement = document.getElementById("sumpagado");
            const sobranteFaltanteElement = document.getElementById("diferencia");

            function calcularTotalPagado() {
                let totalPagadoCOP = 0;

                totalPagadoCOP += parseFloat(inputs.ecop.value) || 0;
                totalPagadoCOP += (parseFloat(inputs.eusd.value) || 0) * tasaUSD_COP;
                totalPagadoCOP += (parseFloat(inputs.ebss.value) || 0) / tasaCOP_BSS;
                totalPagadoCOP += parseFloat(inputs.bcop.value) || 0;
                totalPagadoCOP += (parseFloat(inputs.zusd.value) || 0) * tasaUSD_COP;
                totalPagadoCOP += (parseFloat(inputs.busd.value) || 0) * tasaUSD_COP;
                totalPagadoCOP += (parseFloat(inputs.bbss.value) || 0) / tasaCOP_BSS;
                totalPagadoCOP += (parseFloat(inputs.tbss.value) || 0) / tasaCOP_BSS;

                totalPagadoCOPElement.textContent = totalPagadoCOP.toFixed(2);
                sobranteFaltanteElement.textContent = (totalPagadoCOP - totalPedidoCOP).toFixed(2);
            }

            Object.values(inputs).forEach(input => {
                input.addEventListener("input", calcularTotalPagado);
            });

            document.getElementById("guardarPago").addEventListener("click", function() {
                const data = {
                    pedido_id: <?php echo json_encode($pedidoId); ?>,
                    pagos: {
                        ecop: parseFloat(inputs.ecop.value) || 0,
                        eusd: parseFloat(inputs.eusd.value) || 0,
                        ebss: parseFloat(inputs.ebss.value) || 0,
                        bcop: parseFloat(inputs.bcop.value) || 0,
                        zusd: parseFloat(inputs.zusd.value) || 0,
                        busd: parseFloat(inputs.busd.value) || 0,
                        bbss: parseFloat(inputs.bbss.value) || 0,
                        tbss: parseFloat(inputs.tbss.value) || 0,
                    },
                    total_pagado: parseFloat(totalPagadoCOPElement.textContent),
                    sobrante_faltante: parseFloat(sobranteFaltanteElement.textContent),
                };

                fetch("registrar_pago.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert("Pago registrado correctamente");
                        } else {
                            alert("Error al guardar el pago: " + result.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });

            calcularTotalPagado();
        });
    </script>
</body>

</html>