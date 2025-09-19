<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/ProductoController.php';

$productoController = new ProductoController();

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Obtener datos enviados por POST
$data = $_POST;
echo '<pre>';
    print_r(array($data,empty($data['id'])));
echo '</pre>';

// Validar los datos enviados
if (empty($data['id']) || empty($data['nombre']) || empty($data['preciov']) || empty($data['status']) || empty($data['status_interno']) || empty($data['tipoproducto_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$productoId = $data['id'];
$nombre = $data['nombre'];
$precio = $data['preciov'];
$status = $data['status'];
$statusInterno = $data['status_interno'];
$tipoProductoId = $data['tipoproducto_id'];

try {
    // Usar el controlador para actualizar el producto
    $productoController->updateProducto($productoId, [
        'nombre' => $nombre,
        'preciov' => $precio,
        'status' => $status,
        'status_interno' => $statusInterno,
        'tipoproducto_id' => $tipoProductoId
    ]);
    
    echo json_encode(["success" => true, "message" => "Producto actualizado correctamente"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar el producto: " . $e->getMessage()]);
}
