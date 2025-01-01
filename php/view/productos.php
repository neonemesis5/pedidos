<?php

require_once __DIR__ . '/../controller/ProductoController.php';

// Obtener el ID del tipo de producto desde la solicitud
$tipoProductoId = $_GET['tipoProductoId'] ?? null;

// Validar que el ID del tipo de producto se haya proporcionado
if (!$tipoProductoId) {
    http_response_code(400);
    echo "El ID del tipo de producto es requerido.";
    exit;
}

// Instanciar el controlador
$productoController = new ProductoController();

// Obtener los productos por tipo de producto
try {
    $productos = $productoController->getProductosByTipoProducto($tipoProductoId);

    // Generar botones para cada producto
    foreach ($productos as $producto) {
        echo "<div class='product-item'>";
        echo "<button class='product-button' data-id='{$producto['id']}'>
                {$producto['nombre']} - [{$producto['preciov']} COP]
              </button>";
        echo "</div>";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los productos: " . $e->getMessage();
}
