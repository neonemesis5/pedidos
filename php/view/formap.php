<?php 
session_start(); // Inicia la sesión

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/view/login.php"); // Redirige al login si no está autenticado
    exit;
}

require_once __DIR__ . '/../controller/FormaPagoController.php';
require_once __DIR__ . '/../controller/PedidoController.php';
require_once __DIR__ . '/../controller/TasaController.php';
// Validar el ID del pedido recibido por GET
$pedidoId = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : null;

if (!$pedidoId) {
    die("ID del pedido no proporcionado.");
}

// Instanciar el controlador
$formaPagoController = new FormaPagoController();
$tasaController = new TasaController();
try {
    // Obtener las formas de pago como array
    $formasPago = $formaPagoController->getAllFormasPagoArray();

    if (!is_array($formasPago) || empty($formasPago)) {
        throw new Exception("No se encontraron formas de pago.");
    }
} catch (Exception $e) {
    die("Error al cargar las formas de pago: " . $e->getMessage());
}

try {
    // Obtener las tasas actuales
    $tasas = $tasaController->getCurrentRates2();
    $tasaUSD = $tasas['USD_COP'] ?? 1; // Ejemplo: Tasa USD a COP
    $tasaVES = $tasas['COP_BSS'] ?? 1; // Ejemplo: Tasa VES a COP
} catch (Exception $e) {
    die("Error al cargar las tasas: " . $e->getMessage());
}
try {
    $Pedido = new PedidoController();
    $AMT_Pedido=$Pedido->getMontoTotalByID($pedidoId);
    if (!is_numeric($AMT_Pedido)) {
        $AMT_Pedido = 0; // Asignamos 0 si no es numérico
    }
} catch (\Throwable $th) {
    die("Error al cargar total de Pedido de pago: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Tabla de Formas de Pago</title>
</head>

<body>
    <table>
        <tr>
            <td>Efectivo Pesos</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Efectivo Dolares</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Efectivo Bolivares</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Bancolombia</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Zelle</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Binance</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Pago Movil</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Punto de Venta</td>
            <td><input type="textfield" /></td>
        </tr>
        <tr>
            <td>Otros</td>
            <td><input type="textfield" /></td>
        </tr>
    </table>

</body>

</html>