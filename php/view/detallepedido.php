<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/DetallePedController.php';

$detallePedController = new DetallePedController();
$pedidoId = $_GET['pedido_id'] ?? null;

if (!$pedidoId) {
    http_response_code(400);
    echo json_encode(['error' => 'ID del pedido no proporcionado.']);
    exit;
}

try {
    $detalles = $detallePedController->getDetallePedido($pedidoId);
    // var_dump($detalles);die;
    echo json_encode($detalles);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
