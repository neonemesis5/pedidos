<?php

require_once __DIR__ . '/../controller/DetallePedController.php';

// Obtener el ID del pedido desde la solicitud
$pedido_id = $_GET['pedido_id'] ?? null;

// Validar que el ID del pedido se haya proporcionado
if (!$pedido_id) {
    http_response_code(400);
    echo "El ID del pedido es requerido.";
    exit;
}

// Instanciar el controlador
$detallePedController = new DetallePedController();

try {
    // Obtener los detalles del pedido
    $detalles = $detallePedController->getDetallesByPedidoId($pedido_id);

    // Mostrar los detalles en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Pedido ID</th><th>Producto ID</th><th>Cantidad</th><th>Precio</th><th>Status</th></tr>";
    foreach ($detalles as $detalle) {
        echo "<tr>";
        echo "<td>{$detalle['id']}</td>";
        echo "<td>{$detalle['pedido_id']}</td>";
        echo "<td>{$detalle['producto_id']}</td>";
        echo "<td>{$detalle['qty']}</td>";
        echo "<td>{$detalle['preciov']}</td>";
        echo "<td>{$detalle['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los detalles: " . $e->getMessage();
}
