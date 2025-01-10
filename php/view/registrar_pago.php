<?php
require_once __DIR__ . '/../controller/DetalleFormaPagoController.php';
require_once __DIR__ . '/../controller/MonedaController.php';
require_once __DIR__ . '/../controller/PedidoController.php';

header("Content-Type: application/json");

try {
    // Leer los datos enviados desde el cliente
    $input = json_decode(file_get_contents('php://input'), true);

    // Registrar los datos recibidos en el debug.log
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Datos recibidos: " . print_r($input, true) . PHP_EOL, FILE_APPEND);

    // Verificar datos básicos
    if (!isset($input['pedido_id'], $input['pagos']) || !is_array($input['pagos'])) {
        throw new Exception("Datos incompletos o inválidos.");
    }

    $pedidoId = intval($input['pedido_id']);
    $pagos = $input['pagos'];

    $pendienteCobrar = isset($input['pendienteCobrar']) ? (bool) $input['pendienteCobrar'] : false;
    $valeEmpleados = isset($input['valeEmpleados']) ? (bool) $input['valeEmpleados'] : false;
    $organismos = isset($input['organismos']) ? (bool) $input['organismos'] : false;

    // Inicializar controladores
    $detalleFormaPagoController = new DetalleFormaPagoController();
    $monedaController = new MonedaController();
    $pedidoController = new PedidoController();

    // Obtener todas las monedas y mapear nombres a IDs
    $monedas = $monedaController->getAllMonedas2();
    $monedaMap = [];
    foreach ($monedas as $moneda) {
        $monedaMap[strtoupper($moneda['nombre'])] = $moneda['id'];
    }

    // Registrar las monedas obtenidas en el debug.log
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Mapeo de monedas: " . print_r($monedaMap, true) . PHP_EOL, FILE_APPEND);

    // Procesar cada pago
    foreach ($pagos as $pago) {
        // Registrar el pago actual en el debug.log
        file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Procesando pago: " . print_r($pago, true) . PHP_EOL, FILE_APPEND);

        // Validar datos de cada pago
        if (!isset($pago['forma_pago_id'], $pago['moneda'], $pago['monto'])) {
            throw new Exception("Datos de pago incompletos.");
        }

        $formaPagoId = intval($pago['forma_pago_id']);
        $moneda = strtoupper($pago['moneda']);
        $monto = floatval($pago['monto']);

        // Verificar si la moneda existe en el mapeo
        if (!isset($monedaMap[$moneda])) {
            throw new Exception("Moneda no válida: $moneda.");
        }
        $monedaId = $monedaMap[$moneda];

        // Preparar datos para insertar en la tabla `detalle_formap`
        $detalle = [
            'pedido_id' => $pedidoId,
            'formapago_id' => $formaPagoId,
            'moneda_id' => $monedaId,
            'fecha' => date('Y-m-d H:i:s'),
            'monto' => $monto,
            'status' => 'E' // 'A' para activo
        ];

        // Registrar el detalle que se va a insertar en el debug.log
        file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Insertando detalle: " . print_r($detalle, true) . PHP_EOL, FILE_APPEND);

        // Insertar el detalle
        $detalleFormaPagoController->addDetalleFormaPago($detalle);
    }

    // Determinar el estado del pedido basado en las opciones seleccionadas
    $nuevoEstado = null;

    if ($pendienteCobrar) {
        $nuevoEstado = 'P'; // Pendiente x Cobrar Cliente
    } elseif ($valeEmpleados) {
        $nuevoEstado = 'I'; // Vale Empleados
    } elseif ($organismos) {
        $nuevoEstado = 'O'; // Organismos
    } elseif (!empty($pagos)) {
        $nuevoEstado = 'C'; // Pago completo
    }

    // Actualizar el estado del pedido si se determina uno
    if ($nuevoEstado) {
        $pedidoController->updatePedido($pedidoId, ['status' => $nuevoEstado]);
        file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Estado del pedido actualizado a: $nuevoEstado para el pedido ID: $pedidoId" . PHP_EOL, FILE_APPEND);
    }

    // Responder con éxito
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Pagos registrados exitosamente." . PHP_EOL, FILE_APPEND);
    echo json_encode(['success' => true, 'message' => 'Pagos registrados y estado del pedido actualizado exitosamente.']);
} catch (Exception $e) {
    // Registrar el error en el debug.log
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);

    // Responder con error
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
