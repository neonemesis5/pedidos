<?php
require_once __DIR__ . '/../controller/RepAdminController.php';
$controller = new RepAdminController();

// Obtener la fecha desde una solicitud GET o POST
$fecha =  $_GET['fecha'] ?? null;
// Llamar al método para generar el reporte

$resC = $controller->getVentasDiaria($fecha, 'C');
$resI = $controller->getVentasDiaria($fecha, 'I');
$resO = $controller->getVentasDiaria($fecha, 'O');
$resP = $controller->getVentasDiaria($fecha, 'P');
$resA = $controller->getVentasDiaria($fecha, 'A');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas Diarias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .reporte-container {
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
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
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const consultarButton = document.querySelector("#consultarButton");

            consultarButton.addEventListener("click", () => {
                const fechaInput = document.querySelector("#fechaInput").value;
                if (!fechaInput) {
                    alert("Por favor, seleccione una fecha.");
                    return;
                }

                // Recargar la página con la fecha seleccionada
                window.location.href = `?fecha=${fechaInput}`;
            });
        });
    </script>
</head>

<body>
    <h1>REPORTE DE VENTAS (MERCANCÍA) MOVIMIENTO DÍA</h1>
    <table>
        <tr>
            <td colspan="4">
                <div class="consultar-container">
                    <label for="fechaInput">Fecha:</label>
                    <input type="date" id="fechaInput" value="<?php echo $fecha ?? ''; ?>">
                    <button id="consultarButton">CONSULTAR</button>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width:25%">

            </td>
            <td style="width:25%">

            </td>
            <td style="width:25%;text-align:center;">
                <h4>Seleccione los Resultados a Mostrar</h4>
                <label><input type="checkbox" class="report-checkbox" data-target="tableC"> Ventas Tipo C</label>
                <label><input type="checkbox" class="report-checkbox" data-target="tableI"> Ventas Tipo I</label>
                <label><input type="checkbox" class="report-checkbox" data-target="tableO"> Ventas Tipo O</label>
                <label><input type="checkbox" class="report-checkbox" data-target="tableP"> Ventas Tipo P</label>
                <label><input type="checkbox" class="report-checkbox" data-target="tableA"> Ventas Tipo A</label>
                </div>
            </td>
            <td style="width:25%">
                <button>Generar Excel</button>
            </td>
        </tr>
    </table>
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
            echo "<div class='reporte-container' id='table$tipo'>";
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
            echo "<tr><td colspan='3' class='subtotal'>SUBTOTAL</td><td>$" . number_format($subtotal, 2, ',', '.') . "</td></tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        }
    }
    ?>
</body>

</html>