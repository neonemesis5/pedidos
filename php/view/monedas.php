<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
require_once __DIR__ . '/../controller/MonedaController.php';

// Instanciar el controlador
$monedaController = new MonedaController();

// Obtener las monedas
try {
    $monedas = $monedaController->getAllMonedas();

    // Generar una lista de monedas
    echo "<ul>";
    foreach ($monedas as $moneda) {
        echo "<li>{$moneda['nombre']}</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener las monedas: " . $e->getMessage();
}
