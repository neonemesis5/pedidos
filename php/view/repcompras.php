<?php
// session_start(); // Inicia la sesión

// // Verifica si el usuario no está autenticado
// if (!isset($_SESSION['user_id'])) {
//     header("Location: /pedidos/php/view/login.php"); // Redirige al login si no está autenticado
//     exit;
// }

require_once __DIR__ . '/../controller/FacturaCompraController.php';
require_once __DIR__ . '/../controller/DetalleCompraController.php';

$res = new FacturaCompraController();
$listado = $res->getAllFacturasCompra();
$resdet = new DetalleCompraController();

// Base URL dinámica para evitar problemas con rutas
$baseUrl = '/pedidos'; // Ajusta esto si el proyecto tiene un subdirectorio distinto
?>
<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        button {
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        #detalleCompra {
            visibility: hidden;
        }

        #realizarPagoButton {
            display: none; /* Ocultar el botón por defecto */
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        #realizarPagoButton:hover {
            background-color: #218838;
        }
    </style>
    <script src="<?php echo $baseUrl; ?>/js/listadocompras.js" defer></script>
</head>

<body>

    <h1>Reporte de Compras</h1>

    <table id="principal">
        <tr>
            <td id="col1">Últimos Compras Realizadas</td>
            <td id="col2">Detalle del Compra</td>
        </tr>
        <tr>
            <td id="c00">
                <table id="pedidos">
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
                        <?php foreach ($listado as $value) { ?>
                            <tr>
                                <td><?php echo $value['id']; ?></td>
                                <td><?php echo $value['nomprov']; ?></td>
                                <td><?php echo $value['nommoneda']; ?></td>
                                <td><?php echo $value['nrofactura']; ?></td>
                                <td><?php echo $value['fecha']; ?></td>
                                <td><?php echo $value['total']; ?></td>
                                <td><?php echo $value['status']; ?></td>
                                <td>
                                    <button class="btnDetalle" data-id="<?php echo $value['id']; ?>">Ver Detalle</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </td>
            <td id="c01">
                <table id="detalleCompra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los detalles se cargarán aquí vía AJAX -->
                    </tbody>
                </table>
                <!-- Botón Realizar Pago -->
                <a id="realizarPagoButton" href="#">Realizar Pago</a>
            </td>
        </tr>
    </table>

</body>

</html>