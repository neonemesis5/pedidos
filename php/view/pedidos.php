<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
require_once __DIR__ . '/../controller/PedidoController.php';

// Instanciar el controlador
$pedidoController = new PedidoController();

try {
    // Obtener los pedidos
    $pedidos = $pedidoController->getAllPedidos();

    // Mostrar los pedidos en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Fecha</th><th>Nombre</th><th>Apellido</th><th>Total</th><th>Status</th></tr>";
    foreach ($pedidos as $pedido) {
        echo "<tr>";
        echo "<td>{$pedido['id']}</td>";
        echo "<td>{$pedido['fecha']}</td>";
        echo "<td>{$pedido['nombre']}</td>";
        echo "<td>{$pedido['apellido']}</td>";
        echo "<td>{$pedido['total']}</td>";
        echo "<td>{$pedido['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los pedidos: " . $e->getMessage();
}
