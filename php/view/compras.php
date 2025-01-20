<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id']) || $_SESSION['rol_id'] != 3) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}

// Incluir los controladores directamente en el archivo
require_once __DIR__ . '/../controller/TipoProductoController.php';
require_once __DIR__ . '/../controller/ProductoController.php';
require_once __DIR__ . '/../controller/ProveedorController.php';
require_once __DIR__ . '/../controller/MonedaController.php';

// Instanciar los controladores
$tipoProductoController = new TipoProductoController();
$productoController = new ProductoController();
$monedas = new MonedaController();
$proveedores = new ProveedorController();

try {
    // Obtener todos los tipos de productos
    $tiposProductos = $tipoProductoController->getAllTiposProductos();

    // Variable para almacenar los productos por tipo
    $productosPorTipo = [];
    foreach ($tiposProductos as $tipo) {
        $productosPorTipo[$tipo['id']] = $productoController->getProductosByTipoProducto($tipo['id']);
    }

    // Listado de monedas
    $listmonedas = $monedas->getAllMonedasID();

    // Listado de proveedores
    $listproveedores = $proveedores->getAllProveedores();
} catch (Exception $e) {
    echo "Error al cargar los datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compra</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            padding: 20px;
        }

        h2 {
            color: #333;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        th {
            background: #007bff;
            color: #fff;
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }

        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        select,
        input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
        }

        #guardar {
            background: #dc3545;
            font-weight: bold;
        }

        #guardar:hover {
            background: #c82333;
        }

        #agregarFila {
            background: #007bff;
        }

        #agregarFila:hover {
            background: #0056b3;
        }

        input[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .total {
            font-weight: bold;
            color: #28a745;
        }

        #TOTALFACTURA {
            font-size: 18px;
            font-weight: bold;
            background: #ffeb3b;
            color: #333;
        }
    </style>
</head>
<body>
    <div>
        <table border="0" style="border-collapse: collapse;">
            <tr>
                <td style="width: 60%;">
                    <h2>Factura de Compra</h2>
                </td>
                <td>
                    <table>
                        <tr style="text-align: right;">
                            <td>
                                <button type="button" id="listacomp" onclick="repcompras()">Ver Lista Facturas Compras </button>
                            </td>
                            <td>
                                <button type="button" id="guardar">PROCESAR FACTURA</button>
                            </td>
                        </tr>
                        <tr>
                            <td> <button id="btnkardex"  onclick="kardex()">Entradas y Salidas</button></td>
                            <td>
                                <button id="btnlogout" class="btn-logout" onclick="logout()">Cerrar Sesión</button>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </div>

    <form id="factura-form">
        <table id="header">
            <tr>
                <td>Seleccione Proveedor</td>
                <td>
                    <select id="LISTPROVEEDORES">
                        <option value="">Selecciona un proveedor</option>
                        <?php foreach ($listproveedores as $proveedor): ?>
                            <option value="<?= $proveedor['id'] ?>"><?= $proveedor['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td colspan="2">&nbsp;</td>
                <td>Número Factura</td>
                <td><input id="numfactura" type="text" /></td>
            </tr>
            <tr>
                <td>Seleccione Moneda</td>
                <td>
                    <select id="LISTMONEDAS">
                        <option value="">Selecciona una Moneda</option>
                        <?php foreach ($listmonedas as $moneda): ?>
                            <option value="<?= $moneda['id'] ?>"><?= $moneda['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td colspan="2">&nbsp;</td>
                <td>Fecha Compra</td>
                <td><input id="calendar" type="date" /></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:right;"><strong>Total Factura</strong></td>
                <td><input id="TOTALFACTURA" type="text" readonly /></td>
            </tr>
        </table>

        <button type="button" id="agregarFila">Agregar Fila</button>

        <table id="tablaproductos">
            <thead>
                <tr>
                    <th>Tipo de Producto</th>
                    <th>Nombre Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Precio Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </form>

    <script>
        $(function() {
            $("#calendar").datepicker({
                dateFormat: "yy-mm-dd"
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const tabla = document.querySelector('#tablaproductos tbody');
            const totalFacturaInput = document.getElementById('TOTALFACTURA');
            const form = document.getElementById('factura-form');

            function limpiarFormulario() {
                form.reset();
                tabla.innerHTML = "";
                totalFacturaInput.value = "0.00";
            }

            function calcularTotalFactura() {
                let total = 0;
                document.querySelectorAll('.total').forEach(cell => {
                    total += parseFloat(cell.textContent) || 0;
                });
                totalFacturaInput.value = total.toFixed(2);
            }

            document.getElementById('agregarFila').addEventListener('click', function() {
                const nuevaFila = document.createElement('tr');

                nuevaFila.innerHTML = `
                    <td>
                        <select class="tipoProducto">
                            <option value="">Seleccione un tipo</option>
                            <?php foreach ($tiposProductos as $tipo): ?>
                                <option value="<?= $tipo['id'] ?>"><?= $tipo['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="producto">
                            <option value="">Seleccione un producto</option>
                        </select>
                    </td>
                    <td><input type="number" class="cantidad" min="1" value="1"></td>
                    <td><input type="number" class="precioUnitario" min="0" step="0.01" value=""></td>
                    <td class="total">0.00</td>
                `;

                tabla.appendChild(nuevaFila);

                const selectTipo = nuevaFila.querySelector('.tipoProducto');
                const selectProducto = nuevaFila.querySelector('.producto');

                selectTipo.addEventListener('change', function() {
                    selectProducto.innerHTML = '<option value="">Seleccione un producto</option>';
                    let productos = <?= json_encode($productosPorTipo) ?>;
                    if (productos[selectTipo.value]) {
                        productos[selectTipo.value].forEach(prod => {
                            const option = document.createElement('option');
                            option.value = prod.id;
                            option.textContent = prod.nombre;
                            selectProducto.appendChild(option);
                        });
                    }
                });

                nuevaFila.querySelector('.cantidad').addEventListener('input', calcularTotal);
                nuevaFila.querySelector('.precioUnitario').addEventListener('input', calcularTotal);

                function calcularTotal() {
                    let cantidad = parseFloat(nuevaFila.querySelector('.cantidad').value) || 0;
                    let precio = parseFloat(nuevaFila.querySelector('.precioUnitario').value) || 0;
                    let total = cantidad * precio;
                    nuevaFila.querySelector('.total').textContent = total.toFixed(2);
                    calcularTotalFactura();
                }
            });

            document.getElementById('guardar').addEventListener('click', function() {
                const data = {
                    header: {
                        LISTPROVEEDORES: document.getElementById('LISTPROVEEDORES').value,
                        numfactura: document.getElementById('numfactura').value,
                        moneda: document.getElementById('LISTMONEDAS').value,
                        fecha: document.getElementById('calendar').value,
                    },
                    tablaproductos: [],
                    total: totalFacturaInput.value
                };

                document.querySelectorAll('#tablaproductos tbody tr').forEach(row => {
                    data.tablaproductos.push({
                        id: row.querySelector('.producto').value,
                        cantidad: row.querySelector('.cantidad').value,
                        precio: row.querySelector('.precioUnitario').value
                    });
                });

                fetch('savefactcompra.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('✅ Factura guardada correctamente');
                            limpiarFormulario(); // ✅ Limpiar formulario después de guardar
                        } else {
                            alert('❌ Error al guardar la factura: ' + result.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('⚠️ Ocurrió un error al procesar la factura.');
                    });
            });
        });

        function logout() {
            // Redirige al logout.php para cerrar sesión
            window.location.href = "logout.php";
        }
        function repcompras() {
            // Redirige al logout.php para cerrar sesión
            window.location.href = "repcompras.php";
        }
        function kardex() {
            // Redirige al logout.php para cerrar sesión
            window.location.href = "kardex.php";
        }
    </script>
</body>

</html>