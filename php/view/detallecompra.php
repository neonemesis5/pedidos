<?php
// session_start();

// // Verificar sesión y rol del usuario
// if (!isset($_SESSION['user_id']) ) {
//     header("Location: /pedidos/php/view/login.php");
//     exit;
// }

require_once __DIR__ . '/../controller/DetalleCompraController.php';

// Validar que se reciba el ID del pedido
$factura_id = $_GET['factcompra_id'] ?? null;

if (!$factura_id) {
    http_response_code(400);
    echo json_encode(["error" => "El ID de la compra es requerido."]);
    exit;
}

// Instanciar el controlador
$detalleFactController = new DetalleCompraController();

try {
    // Obtener los detalles del pedido utilizando el método público del controlador
    $detalles = $detalleFactController->getDetallesByFacturaCompra($factura_id);

    // Verificar si la respuesta contiene datos válidos
    if (!is_array($detalles) || empty($detalles)) {
        throw new Exception("No se encontraron detalles para la Factura.");
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($detalles);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener los detalles: " . $e->getMessage()]);
}
