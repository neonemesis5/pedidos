<?php
 session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/PedidoController.php';
require_once __DIR__ . '/../controller/DetallePedController.php'; // Asegúrate de incluir este archivo

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['cliente'], $data['carrito'], $data['total'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$pedidoController = new PedidoController();
$detallePedController = new DetallePedController(); // Instancia del controlador de detalles

try {
    // Guardar cabecera del pedido
    $pedidoData = [
        'fecha' => date('Y-m-d H:i:s'),
        'nombre' => $data['cliente']['nombre'],
        'apellido' => $data['cliente']['apellido'],
        'total' => $data['total'],
        'status' => 'A',
    ];

    // Guardar el pedido y obtener su ID
    $pedidoId = $pedidoController->addPedido($pedidoData);

    // Verificar si el ID del pedido se genera correctamente
    if (!$pedidoId) {
        throw new Exception("Error al guardar el pedido. El ID del pedido no fue generado.");
    }

    // Mostrar el ID generado en el log (debugging)
    file_put_contents("debug.log", "Pedido ID generado: $pedidoId\n", FILE_APPEND);

    // Guardar los detalles del pedido
    foreach ($data['carrito'] as $producto) {
        $detalleData = [
            'pedido_id' => $pedidoId,
            'producto_id' => $producto['id'],
            'qty' => $producto['cantidad'],
            'preciov' => $producto['precio'],
            'status' => 'A',
        ];
    
        // Log para depuración
        file_put_contents("debug.log", "guardar_pedido--Detalle a insertar: " . print_r($detalleData, true) . "\n", FILE_APPEND);
    
        // Intentar insertar el detalle
        $detalleResult = $detallePedController->addDetalle($detalleData);
        if (!$detalleResult) {
            file_put_contents("debug.log", "guardar_pedido--Fallo al insertar detalle para producto_id: {$producto['id']}\n", FILE_APPEND);
            throw new Exception("Error al guardar el detalle del producto ID {$producto['id']}.");
        }
    }
    

    // Respuesta exitosa en formato JSON
    echo json_encode(['success' => true, 'pedido_id' => $pedidoId]);
} catch (Exception $e) {
    // Log de errores
    file_put_contents("debug.log", "Error: " . $e->getMessage() . "\n", FILE_APPEND);

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
