<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/DetallePedController.php';

// Validar que se reciba el ID del pedido
$pedido_id = $_GET['pedido_id'] ?? null;

if (!$pedido_id) {
    http_response_code(400);
    echo "El ID del pedido es requerido.";
    exit;
}

// Instanciar el controlador
$detallePedController = new DetallePedController();

try {
    // Obtener los detalles del pedido utilizando el método público del controlador
    ob_start();
    $detallePedController->getDetallesByPedidoId($pedido_id);
    $response = ob_get_clean();

    // Decodificar la respuesta JSON
    $detalles = json_decode($response, true);

    // Verificar si la respuesta contiene datos válidos
    if (!is_array($detalles)) {
        throw new Exception("No se pudieron obtener los detalles del pedido.");
    }

    // Mostrar los detalles en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th><th>Status</th></tr>";
    foreach ($detalles as $detalle) {
        $total = $detalle['qty'] * $detalle['preciov'];
        echo "<tr>";
        echo "<td>{$detalle['id']}</td>";
        echo "<td>{$detalle['producto_id']}</td>";
        echo "<td>{$detalle['qty']}</td>";
        echo "<td>{$detalle['preciov']}</td>";
        echo "<td>{$total}</td>";
        echo "<td>{$detalle['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los detalles: " . $e->getMessage();
}
