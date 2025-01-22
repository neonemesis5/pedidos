<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Acceso no autorizado."]);
    exit;
}

require_once __DIR__ . '/../controller/TasaController.php';

$tasaController = new TasaController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = $_POST;
// Validación de datos
if (!isset($data['id'], $data['factor']) || empty($data['factor'])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

try {
    $result = $tasaController->insertTasa([
        'id' => $data['id'],
        'monto' => $data['factor'],
    ]);

    echo json_encode(["success" => true, "message" => "Tasa insertada correctamente"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al insertar la tasa: " . $e->getMessage()]);
}
