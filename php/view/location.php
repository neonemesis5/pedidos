<?php

require_once __DIR__ . '/../controller/LocationController.php';

// Instanciar el controlador
$locationController = new LocationController();

try {
    // Obtener todas las ubicaciones
    ob_start();
    $locationController->getAllLocations();
    $response = ob_get_clean();

    // Decodificar la respuesta JSON
    $locations = json_decode($response, true);

    // Mostrar las ubicaciones en una tabla
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th></tr>";
    foreach ($locations as $location) {
        echo "<tr>";
        echo "<td>{$location['id']}</td>";
        echo "<td>{$location['nombre']}</td>";
        echo "<td>{$location['preciov']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener las ubicaciones: " . $e->getMessage();
}
