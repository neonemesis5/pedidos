<?php
require_once __DIR__ . '/../controller/FacturaCompraController.php';
require_once __DIR__ . '/../controller/DetalleCompraController.php';

$data = json_decode(file_get_contents('php://input'), true);

// Validar si los datos existen
if (!$data || !isset($data['header'], $data['tablaproductos'], $data['total'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

$facturaCompraController = new FacturaCompraController();
$detalleCompraController = new DetalleCompraController();

try {
    // ✅ Obtener datos del header correctamente
    $proveedor_id = $data['header']['LISTPROVEEDORES'];
    $nro_factura = $data['header']['numfactura'];
    $moneda_id = $data['header']['moneda']; // 🔹 Se incluyó `moneda_id`
    $fecha = $data['header']['fecha']; // Ya viene en formato "yyyy-MM-dd"
    $total = $data['total'];

    // Validaciones necesarias antes de guardar
    if (empty($proveedor_id) || empty($nro_factura) || empty($moneda_id) || empty($fecha)) {
        throw new Exception("Todos los campos de la cabecera son obligatorios.");
    }

    // ✅ Guardar cabecera de la factura de compra (incluyendo moneda_id)
    $headerData = [
        'proveedor_id' => $proveedor_id,
        'moneda_id' => $moneda_id,  // 🔹 Se agregó `moneda_id`
        'nrofactura' => $nro_factura,
        'fecha' => $fecha,
        'total' => $total,
        'status' => 'A',
    ];

    $facturaID = $facturaCompraController->addFacturaCompra($headerData);

    if (!$facturaID) {
        throw new Exception("Error al guardar la factura de compra.");
    }

    // 📝 Registrar en el log
    file_put_contents("debug.log", "Factura ID generada: $facturaID\n", FILE_APPEND);

    // ✅ Verificar si hay productos antes de insertarlos
    if (empty($data['tablaproductos'])) {
        throw new Exception("Debe agregar al menos un producto.");
    }
// echo '<pre>';
// print_r($data['tablaproductos'] );
// echo '</pre>';die;

    // ✅ Guardar detalles de la factura de compra
    foreach ($data['tablaproductos'] as $producto) {
        if (empty($producto['id']) || empty($producto['cantidad']) || empty($producto['precio'])) {
            throw new Exception("Todos los campos de cada producto son obligatorios.");
        }

        $detalleData = [
            'producto_id' => $producto['id'],
            'factcompra_id' => $facturaID, // Usar el ID generado
            'qty' => $producto['cantidad'],
            'precioc' => $producto['precio'],
            'status' => 'A',
        ];

        // 📝 Registrar en el log
        file_put_contents("debug.log", "Insertando detalle: " . print_r($detalleData, true) . "\n", FILE_APPEND);

        $detalleResult = $detalleCompraController->addDetalleCompra($detalleData);

        if (!$detalleResult) {
            throw new Exception("Error al guardar el detalle del producto ID {$producto['id']}.");
        }
    }

    // ✅ Respuesta JSON de éxito
    echo json_encode(['success' => true, 'factura_id' => $facturaID]);
} catch (Exception $e) {
    // 📝 Log de errores
    file_put_contents("debug.log", "Error: " . $e->getMessage() . "\n", FILE_APPEND);

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
