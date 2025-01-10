<?php
require_once __DIR__ . '/../controller/PedidoController.php';
require_once __DIR__ . '/../controller/detallepedcontroller.php';

$res = new PedidoController();
$listado = $res->getListPedidos();
$resdet = new DetallePedController();

// Base URL dinámica para evitar problemas con rutas
$baseUrl = '/pedidos'; // Ajusta esto si el proyecto tiene un subdirectorio distinto
?>
<!DOCTYPE html>
<html>

<head>
    <title>Listado de Últimos Pedidos Procesados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #principal {
            width: 100%;
            table-layout: fixed;
        }

        #c00,
        #c01 {
            vertical-align: top;
        }

        #c00 #col1{
            width: 60%;
        }

        #c01 #col2{
            width: 40%;
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

        #detallePedido {
            visibility: hidden;
        }

        /* Sombrado al pasar el cursor por la fila */
        /* tr:hover {
            background-color: #f0f8ff;
        } */
    </style>
    <script src="<?php echo $baseUrl; ?>/js/listadopedido.js" defer></script>
</head>

<body>
    <h1>Lista de Pedidos</h1>
    <table id="principal">
        <tr>
            <td id="col1">Ultimos Pedidos</td>
            <td id="col2">Detalle del Pedido</td>
        </tr>
        <tr>
            <td id="c00">
                <table id="pedidos">
                    <thead>
                        <tr>
                            <th>ID PEDIDO</th>
                            <th>Fecha/Hora</th>
                            <th>Monto</th>
                            <th>Estatus</th>
                            <th>Detalle del pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listado as $value) { ?>
                            <tr>
                                <td><?php echo $value['id']; ?></td>
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
                <table id="detallePedido">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio COP</th>
                            <th>Cantidad</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los detalles se cargarán aquí vía AJAX -->
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td id="c10"></td>
            <td id="c11"></td>
        </tr>
    </table>
</body>

</html>