<?php
session_start(); // Inicia la sesión

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php"); // Redirige al login si no está autenticado
    exit;
}

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

        #detallePedido {
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
    <script src="<?php echo $baseUrl; ?>/js/listadopedido.js" defer></script>
</head>

<body>
    <div class="header-container">
        <h1>Listado de Pedidos</h1>
        <div class="header-actions">
            <button id="btnvolver" onclick="window.location.href='/pedidos/index.php'">Volver al Inicio</button>
            <button id="btnlogout" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <table id="principal">
        <tr>
            <td id="col1">Últimos Pedidos</td>
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
                <!-- Botón Realizar Pago -->
                <a id="realizarPagoButton" href="#">Realizar Pago</a>
            </td>
        </tr>
    </table>
</body>
</html>