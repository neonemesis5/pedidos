<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
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

    // Asignar tasas de cambio
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
            <td><input id="ecop" data-moneda="COP" data-forma-id="1" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Efectivo Dólares</td>
            <td><input id="eusd" data-moneda="USD" data-forma-id="2" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Efectivo Bolívares</td>
            <td><input id="ebss" data-moneda="BSS" data-forma-id="3" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Bancolombia</td>
            <td><input id="bcop" data-moneda="COP" data-forma-id="4" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Zelle</td>
            <td><input id="zusd" data-moneda="USD" data-forma-id="5" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Binance</td>
            <td><input id="busd" data-moneda="USD" data-forma-id="6" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Pago Móvil</td>
            <td><input id="bbss" data-moneda="BSS" data-forma-id="7" type="number" step="0.01" /></td>
        </tr>
        <tr>
            <td>Punto de Venta</td>
            <td><input id="tbss" data-moneda="BSS" data-forma-id="8" type="number" step="0.01" /></td>
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
        <label><input type="checkbox" id="pendienteCobrar"> Pendiente x Cobrar Cliente</label>
        <label><input type="checkbox" id="valeEmpleados"> Vale Empleados</label>
        <label><input type="checkbox" id="organismos"> Organismos</label>
    </div>

    <button id="guardarPago">Guardar Pago</button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tasaUSD_COP = <?php echo $tasaUSD_COP; ?>;
            const tasaCOP_BSS = <?php echo $tasaCOP_BSS; ?>;
            const tasaUSD_BSS = <?php echo $tasaUSD_BSS; ?>;
            const totalPedidoCOP = <?php echo $AMT_Pedido; ?>;

            const inputs = document.querySelectorAll("input[type='number']");
            const totalPagadoCOPElement = document.getElementById("sumpagado");
            const sobranteFaltanteElement = document.getElementById("diferencia");

            const pendienteCobrar = document.getElementById("pendienteCobrar");
            const valeEmpleados = document.getElementById("valeEmpleados");
            const organismos = document.getElementById("organismos");
            const pagoRecibidoBtn = document.querySelector("#guardarPago");

            function calcularTotalPagado() {
                let totalPagadoCOP = 0;

                inputs.forEach(input => {
                    const moneda = input.dataset.moneda;
                    let monto = parseFloat(input.value) || 0;

                    if (moneda === "USD") {
                        totalPagadoCOP += monto * tasaUSD_COP;
                    } else if (moneda === "BSS") {
                        totalPagadoCOP += monto / tasaCOP_BSS;
                    } else {
                        totalPagadoCOP += monto;
                    }
                });

                totalPagadoCOPElement.textContent = totalPagadoCOP.toFixed(2);
                sobranteFaltanteElement.textContent = (totalPagadoCOP - totalPedidoCOP).toFixed(2);

                // Validar el estado del botón
                if (totalPagadoCOP >= totalPedidoCOP || pendienteCobrar.checked || valeEmpleados.checked || organismos.checked) {
                    pagoRecibidoBtn.disabled = false; // Habilitar el botón si se cumplen las condiciones
                } else {
                    pagoRecibidoBtn.disabled = true; // Deshabilitar si no se cumplen las condiciones
                }
            }

            // Recalcular cuando se cambia el valor de los inputs
            inputs.forEach(input => {
                input.addEventListener("input", calcularTotalPagado);
            });

            // Recalcular cuando se cambia el estado de los checkboxes
            pendienteCobrar.addEventListener("change", calcularTotalPagado);
            valeEmpleados.addEventListener("change", calcularTotalPagado);
            organismos.addEventListener("change", calcularTotalPagado);

            // Acción para el botón de "Guardar Pago"
            pagoRecibidoBtn.addEventListener("click", () => {
                const pagos = Array.from(inputs).map(input => ({
                    forma_pago_id: parseInt(input.dataset.formaId),
                    moneda: input.dataset.moneda,
                    monto: parseFloat(input.value) || 0,
                }));

                const data = {
                    pedido_id: <?php echo json_encode($pedidoId); ?>,
                    pagos,
                    pendienteCobrar: pendienteCobrar.checked,
                    valeEmpleados: valeEmpleados.checked,
                    organismos: organismos.checked
                };

                console.log("Datos enviados:", data);

                fetch("registrar_pago.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert("Pago registrado correctamente");
                            window.location.href = "../../index.php";
                        } else {
                            alert("Error al registrar el pago: " + result.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Hubo un error al procesar el pago.");
                    });
            });

            calcularTotalPagado();
        });
    </script>
</body>

</html>