<?php

require_once __DIR__ . '/../controller/DeliverysController.php';

// Instanciar el controlador
$deliverysController = new DeliverysController();

try {
    // Obtener todos los registros de deliverys
    ob_start();
    $deliverysController->getAllDeliverys();
    $response = ob_get_clean();

    // Decodificar la respuesta JSON
    $deliverys = json_decode($response, true);

    // Mostrar los registros en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Location ID</th><th>Pedido ID</th><th>Moneda ID</th><th>Fecha</th><th>Monto</th><th>Status</th></tr>";
    foreach ($deliverys as $delivery) {
        echo "<tr>";
        echo "<td>{$delivery['id']}</td>";
        echo "<td>{$delivery['location_id']}</td>";
        echo "<td>{$delivery['pedido_id']}</td>";
        echo "<td>{$delivery['moneda_id']}</td>";
        echo "<td>{$delivery['created_at']}</td>";
        echo "<td>{$delivery['monto']}</td>";
        echo "<td>{$delivery['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener los registros de deliverys: " . $e->getMessage();
}
