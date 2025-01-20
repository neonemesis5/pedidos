<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

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
    ob_start();
    $detalleFormaPagoController->getDetallesByPedidoId($pedido_id);
    $response = ob_get_clean();

    $detalles = json_decode($response, true);

    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Pedido</th><th>Forma Pago</th><th>Moneda</th><th>Fecha</th><th>Monto</th><th>Bauche</th><th>Status</th></tr>";
    foreach ($detalles as $detalle) {
        echo "<tr>";
        echo "<td>{$detalle['id']}</td>";
        echo "<td>{$detalle['pedido_id']}</td>";
        echo "<td>{$detalle['formapago_nombre']}</td>";
        echo "<td>{$detalle['moneda_nombre']}</td>";
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
