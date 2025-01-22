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

// Validar los datos enviados
if (!isset($data['id']) || !isset($data['nombre']) || !isset($data['preciov']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$productoId = $data['id'];
$nombre = $data['nombre'];
$precio = $data['preciov'];
$status = $data['status'];

try {
    // Usar el controlador para actualizar el producto
    $productoController->updateProducto($productoId, [
        'nombre' => $nombre,
        'preciov' => $precio,
        'status' => $status
    ]);
    
    echo json_encode(["success" => true, "message" => "Producto actualizado correctamente"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al actualizar el producto: " . $e->getMessage()]);
}
