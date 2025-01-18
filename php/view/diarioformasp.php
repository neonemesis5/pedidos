<?php
require_once __DIR__ . '/../controller/RepAdminController.php';
require_once __DIR__ . '/../controller/PedidoController.php';

$controller = new RepAdminController();
$pedidoController = new PedidoController();

// Obtener la fecha desde una solicitud GET o POST
$fecha = $_GET['fecha'] ?? null;

// Llamar al método para generar el reporte
$res = $controller->getDiarioCaja($fecha);
$pedidosVales = $pedidoController->getListPedidos('I');
$pedidosOrganismos = $pedidoController->getListPedidos('O');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Dinero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .subtotal {
            text-align: right;
            font-weight: bold;
            padding-right: 10px;
        }

        .total {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            padding-top: 10px;
        }

        .consultar-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .consultar-container input[type="date"] {
            padding: 5px;
            font-size: 16px;
        }

        .consultar-container button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .consultar-container button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h1>REPORTE DIARIO DINERO</h1>
    <div class="consultar-container">
        <label for="fechaInput">Fecha:</label>
        <input type="date" id="fechaInput" value="<?php echo $fecha ?? ''; ?>">
        <button id="consultarButton">Consultar</button>
    </div>

    <!-- Tabla de Reporte Diario de Caja -->
    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>FORMA DE PAGO</th>
                <th>MONTO</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($res)) : ?>
                <?php foreach ($res as $row) : ?>
                    <tr>
                        <td><?php echo $fecha ?? ''; ?></td>
                        <td><?php echo $row['forma_pago'] . ' ' . $row['moneda']; ?></td>
                        <td>$<?php echo number_format($row['total'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No se encontraron datos para la fecha seleccionada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tabla de Vales Internos -->
    <h2>VALES INTERNOS</h2>
    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>TICKET NRO</th>
                <th>MONTO</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pedidosVales)) : ?>
                <?php 
                $totalVales = 0; 
                foreach ($pedidosVales as $pedido) : 
                    $totalVales += $pedido['total'];
                ?>
                    <tr>
                        <td><?php echo $pedido['fecha']; ?></td>
                        <td><?php echo $pedido['id']; ?></td>
                        <td>$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" class="subtotal">TOTAL VALES INTERNOS</td>
                    <td>$<?php echo number_format($totalVales, 2, ',', '.'); ?></td>
                </tr>
            <?php else : ?>
                <tr>
                    <td colspan="3">No se encontraron vales internos para la fecha seleccionada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        document.querySelector("#consultarButton").addEventListener("click", () => {
            const fecha = document.querySelector("#fechaInput").value;
            if (!fecha) {
                alert("Por favor, seleccione una fecha.");
                return;
            }
            window.location.href = `?fecha=${fecha}`;
        });
    </script>
</body>

</html>
