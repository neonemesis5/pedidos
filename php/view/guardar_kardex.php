<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

require_once __DIR__ . '/../controller/DiarioController.php';
require_once __DIR__ . '/../controller/MovDiarioController.php';

// Leer los datos enviados desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Registrar en debug.log los datos recibidos
error_log("Datos recibidos en guardar_kardex.php: " . print_r($data, true), 3, __DIR__ . '/debug.log');

if (!$data || !isset($data['tipoProductoId']) || !isset($data['tipoOperacion']) || !isset($data['productos'])) {
    http_response_code(400);
    error_log("Error: Datos inválidos.\n", 3, __DIR__ . '/debug.log');
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$tipoProductoId = $data['tipoProductoId'];
$tipoOperacion = $data['tipoOperacion'];
$productos = $data['productos'];

// Validar que el tipoOperacion es válido
$validTipoOperaciones = [1000, 1001]; // IDs válidos en tipooper
if (!in_array($tipoOperacion, $validTipoOperaciones)) {
    error_log("Error: Tipo de operación no válido: $tipoOperacion\n", 3, __DIR__ . '/debug.log');
    throw new Exception("Tipo de operación no válido: $tipoOperacion");
}

// Determinar el ID del tipo de operación
$tipoOperId = $tipoOperacion; // El ID ya se envía correctamente desde el frontend

// Guardar la cabecera en la tabla "diario"
$diarioController = new DiarioController();
$movDiarioController = new MovDiarioController();

try {
    // Registrar en la tabla "diario"
    $diarioId = $diarioController->createDiario([
        'tipooper_id' => $tipoOperId,
        'fecha' => date('Y-m-d H:i:s'),
        'status' => 'A', // Activo
    ]);

    // Registrar en debug.log el ID generado para la cabecera
    error_log("Diario creado con ID: $diarioId\n", 3, __DIR__ . '/debug.log');

    // Registrar los detalles en la tabla "movdiario"
    foreach ($productos as $producto) {
        if (!isset($producto['producto_id']) || !isset($producto['cantidad'])) {
            throw new Exception("Faltan datos en el detalle del producto: 'producto_id' o 'cantidad' ausentes.");
        }

        $movDiarioData = [
            'producto_id' => $producto['producto_id'],
            'diario_id' => $diarioId,
            'qty' => $producto['cantidad'],
            'observacion' => 'Registro desde Kardex',
            'status' => 'A', // Activo
        ];

        // Registrar en debug.log los datos del movimiento
        error_log("Datos de movimiento: " . print_r($movDiarioData, true), 3, __DIR__ . '/debug.log');

        $movDiarioController->createMovDiario($movDiarioData);
    }

    echo json_encode(['success' => true, 'message' => 'Registros guardados correctamente.']);
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error al guardar registros: " . $e->getMessage() . "\n", 3, __DIR__ . '/debug.log');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
