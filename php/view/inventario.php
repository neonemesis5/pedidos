<?php
require_once __DIR__ . '/../controller/InventarioController.php';
require_once __DIR__ . '/../controller/ProductoController.php';

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;

$productosController = new ProductoController();
$inventarioController = new InventarioController();

$productos = $productosController->getAllProductos();
$compras = $inventarioController->getCompras($fecha);
$ingresos = $inventarioController->getIngresos($fecha);
$salidas = $inventarioController->getEgresos($fecha);

$listprod = [];

// Inicializar productos con valores en 0
foreach ($productos as $value) {
    $listprod[$value['id']] = [
        'nombre' => $value['nombre'],
        'invinicial'=> 0,
        'compras'  => 0,
        'entradas' => 0,
        'salidas'  => 0,
        'qty'      => $value['qty'],
        'status'   => $value['status'],
    ];
}

// Asignar valores de compras, ingresos y egresos si existen
foreach ($compras as $value) {
    if (isset($listprod[$value['id']])) {
        $listprod[$value['id']]['compras'] = $value['cantidad_comprada'] ?? 0;
    }
}
foreach ($ingresos as $value) {
    if (isset($listprod[$value['id']])) {
        $listprod[$value['id']]['entradas'] = $value['cantidad_ingresada'] ?? 0;
    }
}
foreach ($salidas as $value) {
    if (isset($listprod[$value['id']])) {
        $listprod[$value['id']]['salidas'] = $value['cantidad_egresada'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-positive {
            color: green;
            font-weight: bold;
        }

        .total-negative {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>Inventario de Productos</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Inv Inicial</th>
                <th>Compras</th>
                <th>Entradas</th>
                <th>Salidas</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listprod as $key => $value) { 
                $total = ($value['compras'] - $value['salidas'] + $value['entradas']+$value['invinicial']);
                $class = $total >= 0 ? "total-positive" : "total-negative";
            ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo htmlspecialchars($value['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($value['invinicial']); ?></td>
                    <td><?php echo number_format($value['compras'], 2); ?></td>
                    <td><?php echo number_format($value['entradas'], 2); ?></td>
                    <td><?php echo number_format($value['salidas'], 2); ?></td>
                    <td class="<?php echo $class; ?>"><?php echo number_format($total, 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>

</html>
