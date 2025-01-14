<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

require_once __DIR__ . '/../controller/TipoProductoController.php';
require_once __DIR__ . '/../controller/ProductoController.php';

// Obtener los tipos de productos
$tipoProductoController = new TipoProductoController();
$tiposProductos = $tipoProductoController->getAllTiposProductos();

// Obtener el tipo seleccionado y productos (si aplica)
$tipoSeleccionado = $_GET['tipoProductoId'] ?? null;
$productos = [];

if ($tipoSeleccionado) {
    $productoController = new ProductoController();
    try {
        $productos = $productoController->getProductosConUnidadPorTipo($tipoSeleccionado);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardex - ENTRADAS Y SALIDAS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #218838;
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

        #header {
            margin-bottom: 20px;
        }

        #header table {
            width: 100%;
        }

        #titlerep {
            text-align: left;
        }
    </style>
    <script>
        // Cambiar dinámicamente los productos al seleccionar un tipo
        function cargarProductos(tipoId) {
            const url = `kardex.php?tipoProductoId=${tipoId}`;
            window.location.href = url; // Recargar la página con el tipo seleccionado
        }
        document.addEventListener("DOMContentLoaded", () => {
            const logoutButton = document.getElementById("btnLogout");
            logoutButton.addEventListener("click", () => {
                // Redirige al archivo PHP encargado de cerrar la sesión
                window.location.href = "/pedidos/php/view/logout.php";
            });
            const btnGuardar = document.getElementById("btnGuardar");
            const ingresoCheckbox = document.getElementById("ingreso");
            const salidaCheckbox = document.getElementById("salida");

            // Asegurar que solo uno de los checkboxes pueda estar seleccionado
            ingresoCheckbox.addEventListener("change", () => {
                if (ingresoCheckbox.checked) {
                    salidaCheckbox.checked = false;
                }
            });

            salidaCheckbox.addEventListener("change", () => {
                if (salidaCheckbox.checked) {
                    ingresoCheckbox.checked = false;
                }
            });

            btnGuardar.addEventListener("click", () => {
                const rows = document.querySelectorAll("table tbody tr");
                const productos = [];

                rows.forEach((row) => {
                    const productoId = row.dataset.productoId; // Captura el ID del producto
                    const cantidadInput = row.cells[3]?.querySelector("input"); // Input en la cuarta celda

                    if (productoId && cantidadInput && cantidadInput.value && cantidadInput.value > 0) {
                        productos.push({
                            producto_id: productoId, // ID del producto
                            cantidad: parseFloat(cantidadInput.value), // Cantidad ingresada
                        });
                    }
                });

                if (productos.length === 0) {
                    alert("Por favor, ingrese cantidades antes de guardar.");
                    return;
                }

                // Determinar el tipo de operación (1000 para ingreso, 1001 para salida)
                const tipoOperacion = ingresoCheckbox.checked ? 1000 : 1001;

                // Crear el objeto de datos para enviar al backend
                const data = {
                    tipoProductoId: <?php echo $tipoSeleccionado ? $tipoSeleccionado : 'null'; ?>,
                    tipoOperacion: tipoOperacion,
                    productos: productos,
                };

                // Enviar los datos al backend con fetch
                fetch("/pedidos/php/view/guardar_kardex.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Error al guardar los registros.");
                        }
                        return response.json();
                    })
                    .then((result) => {
                        if (result.success) {
                            alert("Registros guardados correctamente.");
                            window.location.reload(); // Recargar la página
                        } else {
                            alert("Error al guardar registros: " + result.message);
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Hubo un error al guardar los registros.");
                    });
            });
        });
    </script>
</head>

<body>
    <div id="header">
        <table style="width: 100%;">
            <tr>
                <td id="titlerep">
                    <h1>Kardex - ENTRADAS Y SALIDAS</h1>
                </td>
                <td style="text-align: right;">
                    <button id="btnLogout" class="btn-logout">Cerrar Sesión</button>
                </td>
            </tr>
            <tr>
                <td><button onclick="window.location.href='repkardex.php';">Ver Reporte</button></td>
                <td>
                    <label>Tipo Operación:</label>
                    <input type="checkbox" id="ingreso" checked />
                    <label for="ingreso">Ingreso</label>
                    <input type="checkbox" id="salida" />
                    <label for="salida">Salida</label>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td colspan="3">
                <h2>Tipos de Productos</h2>
                <?php foreach ($tiposProductos as $tipo): ?>
                    <button onclick="cargarProductos(<?php echo $tipo['id']; ?>)">
                        <?php echo htmlspecialchars($tipo['nombre']); ?>
                    </button>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <h2>Productos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Id Producto</th>
                            <th>Producto</th>
                            <th>Unidad de Medida</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <tr data-producto-id="<?php echo htmlspecialchars($producto['id']); ?>">
                                    <td><?php echo htmlspecialchars($producto['id']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['producto']); ?></td>
                                    <td><?php echo htmlspecialchars($producto['unidad_medida']); ?></td>
                                    <td><input type="number" step="0.01" min="0"></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Selecciona un tipo de producto para ver los productos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
                <button id="btnGuardar">Guardar Registros</button>
            </td>
        </tr>
    </table>
</body>

</html>