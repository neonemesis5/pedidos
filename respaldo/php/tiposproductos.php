<?php

require_once 'tipoproductodao.php';

$tipoProductoDAO = new TipoProductoDAO();
$tiposProductos = $tipoProductoDAO->getAll();

foreach ($tiposProductos as $tipo) {
    echo "<button data-id='{$tipo['id']}'>{$tipo['nombre']}</button><br>";
}
