<?php

require_once __DIR__ . '/../controller/FormaPagoController.php';

// Instanciar el controlador
$formaPagoController = new FormaPagoController();

try {
    // Obtener todas las formas de pago
    ob_start();
    $formaPagoController->getAllFormasPago();
    $response = ob_get_clean();

    // Decodificar la respuesta JSON
    $formasPago = json_decode($response, true);

    // Mostrar las formas de pago en una lista
    echo "<ul>";
    foreach ($formasPago as $formaPago) {
        echo "<li>ID: {$formaPago['id']} - Nombre: {$formaPago['nombre']}</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener las formas de pago: " . $e->getMessage();
}
