<?php
session_start(); // Inicia la sesión

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Código de error no autorizado
    echo json_encode(['error' => 'No autorizado']);
    exit;
}



require_once __DIR__ . '/../controller/PedidoController.php';

try {
    $pedidoController = new PedidoController();
    $lastPedido = $pedidoController->getLastPedido();

    if ($lastPedido) {
        echo json_encode(['success' => true, 'id' => intval($lastPedido['id'])+1]);
    } else {
        echo json_encode(['success' => true, 'id' => 1]); // Si no hay pedidos, retornar ID 0
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
