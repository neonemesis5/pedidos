<?php
require_once __DIR__ . '/../controller/RepAdminController.php';

$controller = new RepAdminController();

// Obtener la fecha desde una solicitud GET o POST
// $fecha = $_GET['fecha'] ?? null;

// Llamar al método para generar el reporte
$res=$controller->getVentasDiaria('2025-01-11','C');
echo '<pre>';
    print_r($res);
echo '</pre>';
?>