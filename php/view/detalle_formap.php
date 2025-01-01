<?php

require_once __DIR__ . '/../controller/DetalleFormaPagoController.php';

// Validar que se reciba el ID del pedido
$pedido_id = $_GET['pedido_id'] ?? null;

if (!$pedido_id) {
    http_response_code(400);
    echo "El ID del pedido es requerido.";
    exit;
}

// Instanciar el controlador
$detalleFormaPagoController = new DetalleFormaPagoController();

try {
    // Obtener los detalles de forma de pago
    ob_start();
    $detalleFormaPagoController->getDetallesByPedidoId($pedido_id);
    $response = ob_get_clean();

    // Decodificar la respuesta JSON
    $detalles = json_decode($response, true);

    // Mostrar los detalles en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Forma Pago ID</th><th>Moneda ID</th><th>Pedido ID</th><th>Fecha</th><th>Monto</th><th>Nro Bauche</th><th>Status</th></tr>";
    foreach ($detalles as $detalle) {
        echo "<tr>";
        echo "<td>{$detalle['id']}</td>";
        echo "<td>{$detalle['formapago_id']}</td>";
        echo "<td>{$detalle['moneda_id']}</td>";
        echo "<td>{$detalle['pedido_id']}</td>";
        echo "<td>{$detalle['fecha']}</td>";
        echo "<td>{$detalle['monto']}</td>";
        echo "<td>{$detalle['nrobauche']}</td>";
        echo "<td>{$detalle['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los detalles: " . $e->getMessage();
}
