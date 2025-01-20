<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(["error" => "No autorizado"]);
        exit;
    } else {
        header("Location: /pedidos/php/view/login.php");
        exit;
    }
}

require_once __DIR__ . '/../controller/RepAdminController.php';
require_once __DIR__ . '/../controller/PedidoController.php';

$controller = new RepAdminController();
$pedidoController = new PedidoController();

// Obtener la fecha desde GET (siempre la recibe desde `repgeneral.php`)
$fecha = $_GET['fecha'] ?? date('Y-m-d');

// Llamar al método para generar el reporte
$res = $controller->getDiarioCaja($fecha);
?>

<h1>REPORTE DIARIO DINERO</h1>

<!-- Tabla de Reporte Diario de Caja -->
<table>
    <thead>
        <tr>
            <th>FECHA</th>
            <th>FORMA DE PAGO</th>
            <th>MONTO</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($res)) : ?>
            <?php foreach ($res as $row) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($fecha); ?></td>
                    <td><?php echo htmlspecialchars($row['forma_pago'] . ' ' . $row['moneda']); ?></td>
                    <td>$<?php echo number_format($row['total'], 2, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="3">No se encontraron datos para la fecha seleccionada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
