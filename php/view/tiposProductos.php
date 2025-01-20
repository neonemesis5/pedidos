<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
require_once __DIR__ . '/../controller/TipoProductoController.php';

// Instanciar el controlador
$tipoProductoController = new TipoProductoController();

try {
    // Obtener los tipos de productos como un array
    $tiposProductos = $tipoProductoController->getAllTiposProductos();

    // Generar botones para cada tipo de producto
    foreach ($tiposProductos as $tipo) {
        echo "<button data-id='{$tipo['id']}'>{$tipo['nombre']}</button><br>";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los tipos de productos: " . $e->getMessage();
}
