<?php
session_start(); // Inicia la sesión

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    // http_response_code(401); // Código de error no autorizado
    // echo json_encode(['error' => 'No autorizado']);
    header("Location: /pedidos/php/view/login.php");
    exit;
}


require_once __DIR__ . '/../controller/TasaController.php';

// Instanciar el controlador
$tasaController = new TasaController();

// Obtener las tasas
try {
    $tasas = $tasaController->getCurrentRates();//$tasaController->getAllTasas();

    // Generar una tabla con las tasas
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Moneda 1</th><th>Moneda 2</th><th>Fecha</th><th>Monto</th><th>Status</th></tr>";
    foreach ($tasas as $tasa) {
        echo "<tr>";
        echo "<td>{$tasa['id']}</td>";
        // echo "<td>{$tasa['moneda_id1']}</td>";
        // echo "<td>{$tasa['moneda_id2']}</td>";
        echo "<td>{$tasa['nombre']}</td>";
        // echo "<td>{$tasa['fecha']}</td>";
        echo "<td>{$tasa['monto']}</td>";
        // echo "<td>{$tasa['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error al obtener las tasas: " . $e->getMessage();
}
