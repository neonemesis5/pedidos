<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) ) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/RepkardexController.php';

// Inicializar el controlador
$controller = new RepkardexController();

// Valores predeterminados para los filtros
$idOper = !empty($_GET['idOper']) ? (int) $_GET['idOper'] : null; // Verifica si hay un valor válido para idOper
$fechaInicio = !empty($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null; // Verifica si hay un valor válido para fechaInicio
$fechaFin = !empty($_GET['fechaFin']) ? $_GET['fechaFin'] : null; // Verifica si hay un valor válido para fechaFin
$idProducto = !empty($_GET['idProducto']) ? (int) $_GET['idProducto'] : null; // Verifica si hay un valor válido para idProducto
$reporte = [];

// Si se envían filtros, llamar al controlador
if (isset($_GET['filtrar'])) {
    $filters = [
        'idOper' => $idOper,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'idProducto' => $idProducto,
    ];
    $reporte = $controller->getFiltered($filters);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Kardex</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        button,
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover,
        input[type="submit"]:hover {
            background-color: #218838;
        }

        .filter-container {
            margin-bottom: 20px;
        }

        .filter-container label {
            display: block;
            margin-bottom: 5px;
        }

        .filter-container input,
        .filter-container select {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
        }

        .filter-container .row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-container .row .col {
            flex: 1;
            min-width: 200px;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function logout() {
            window.location.href = "logout.php";
        }
    </script>
</head>

<body>
    <table>
        <tr>
            <td style="width: 70%;">
                <h1>Reporte Kardex</h1>
            </td>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="header-actions">
                                <button id="btnvolver" onclick="window.location.href='compras.php'">Cargar Compras</button>
                            </div>
                        </td>
                        <td>
                            <div class="header-actions">
                                <button id="btnvolver" onclick="window.location.href='kardex.php'">Volver al Kardex</button>
                                <button id="btnlogout" class="btn-logout"  onclick="logout()">Cerrar Sesión</button>
                            </div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <form method="GET" action="repkardex.php" class="filter-container">
        <div class="row">
            <!-- Tipo de Operación -->
            <div class="col">
                <label for="idOper">Tipo de Operación</label>
                <select name="idOper" id="idOper">
                    <option value="">Seleccionar</option>
                    <option value="1000" <?php echo ($idOper == 1000) ? 'selected' : ''; ?>>Ingreso</option>
                    <option value="1001" <?php echo ($idOper == 1001) ? 'selected' : ''; ?>>Salida</option>
                </select>
            </div>

            <!-- Fecha de Inicio -->
            <div class="col">
                <label for="fechaInicio">Fecha de Inicio</label>
                <input type="date" name="fechaInicio" id="fechaInicio" value="<?php echo htmlspecialchars($fechaInicio ?? ""); ?>">
            </div>

            <!-- Fecha de Fin -->
            <div class="col">
                <label for="fechaFin">Fecha de Fin</label>
                <input type="date" name="fechaFin" id="fechaFin" value="<?php echo htmlspecialchars($fechaFin ?? ""); ?>">
            </div>

            <!-- ID del Producto -->
            <div class="col">
                <label for="idProducto">ID del Producto</label>
                <input type="number" name="idProducto" id="idProducto" value="<?php echo htmlspecialchars($idProducto ?? ""); ?>">
            </div>
        </div>

        <input type="submit" name="filtrar" value="Filtrar">
    </form>


    <?php if (!empty($reporte)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Diario</th>
                    <th>Fecha</th>
                    <th>Tipo de Operación</th>
                    <th>ID Producto</th>
                    <th>Nombre del Producto</th>
                    <th>Unidad de Medida</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reporte as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['idpro'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['nompro'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['nomunidad'] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row['qty'] ?? ""); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php if (isset($_GET['filtrar'])): ?>
            <p>No se encontraron registros para los filtros aplicados.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>