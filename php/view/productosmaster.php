<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
require_once __DIR__ . '/../controller/ProductoController.php';
require_once __DIR__ . '/../controller/TipoProductoController.php';

$tipoProductoController = new TipoProductoController();
$productoController = new ProductoController();

$tiposProductos = $tipoProductoController->getAllTiposProductos();
$tipoProductoId = $_GET['tipoProductoId'] ?? null;
$productos = $tipoProductoId ? $productoController->getProductosByTipoProducto($tipoProductoId) : [];
// echo '<pre>';
// print_r($productos);
// echo '</pre>';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .tipo-producto-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tipo-producto-button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .tipo-producto-button:hover {
            background-color: #1abc9c;
        }

        .productos-container {
            width: 65%;
            float: left;
        }

        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .productos-table th,
        .productos-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .productos-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .editar-producto {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .editar-producto:hover {
            background-color: #0056b3;
        }

        #formulario-edicion {
            width: 30%;
            float: right;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #formulario-edicion label {
            display: block;
            margin: 10px 0 5px;
        }

        #formulario-edicion input,
        #formulario-edicion select,
        #formulario-edicion button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #formulario-edicion button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }

        #formulario-edicion button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <!-- Tipos de productos -->
    <div class="tipo-producto-container">
        <?php foreach ($tiposProductos as $tipo) : ?>
            <button class="tipo-producto-button" data-id="<?php echo $tipo['id']; ?>">
                <?php echo htmlspecialchars($tipo['nombre']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Listado de productos -->
    <div class="productos-container">
        <h3>Listado de Productos</h3>
        <table class="productos-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Producto</th>
                    <th>Precio de Venta</th>
                    <th>Estado</th>
                    <th>Status Kardex</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody id="productos-list">
                <?php foreach ($productos as $producto) : ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td><?php echo number_format($producto['preciov'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($producto['status']); ?></td>
                        <td><?php echo htmlspecialchars($producto['status_interno']); ?></td>

                        <td>
                            <button class="editar-producto"
                                data-id="<?php echo $producto['id']; ?>"
                                data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                data-preciov="<?php echo $producto['preciov']; ?>"
                                data-status="<?php echo $producto['status']; ?>"
                                data-status_kardex="<?php echo $producto['status_interno']; ?>">
                                Editar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Formulario de edición -->
    <div id="formulario-edicion">
        <h3>Editar Producto</h3>
        <form id="editarProductoForm">
            <input type="hidden" id="productoId" name="id">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">
            <label for="tipoproducto">Tipo de Productos:</label>
            <?php echo '<select id="idtipo" name="tipos_productos">';

            foreach ($tiposProductos as $tipoProducto) {
                echo '<option value="' . $tipoProducto['id'] . '">' . $tipoProducto['nombre'] . '</option>';
            }

            echo '</select>';
            ?>
            <label for="preciov">Precio de Venta:</label>
            <input type="number" step="0.01" id="preciov" name="preciov">
            <label for="status">Estado:</label>
            <select id="status" name="status">
                <option value="A">Activo</option>
                <option value="I">Inactivo</option>
            </select>
            <label for="status_kardex">Status Kardex:</label>
            <select id="status_kardex" name="status_interno">
                <option value="A">Activo</option>
                <option value="O">Oculto</option>
            </select>
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>

    <script>
        // Manejar la selección de tipo de producto
        $(document).on("click", ".tipo-producto-button", function() {
            const tipoProductoId = $(this).data("id");

            $.ajax({
                url: "productosmaster.php",
                type: "GET",
                data: {
                    tipoProductoId
                },
                success: function(response) {
                    $("#productos-list").html($(response).find("#productos-list").html());
                },
                error: function() {
                    alert("Error al cargar los productos.");
                }
            });
        });

        // Mostrar formulario de edición con datos del producto
        $(document).on("click", ".editar-producto", function() {
            const productoId = $(this).data("id");
            const idtipo =  $(this).data("idtipo");
            const nombre = $(this).data("nombre");
            const preciov = $(this).data("preciov");
            const status = $(this).data("status");
            const status_kardex = $(this).data("status_kardex");
           

            $("#productoId").val(productoId);
            $("#nombre").val(nombre);
            $("#idtipo").val(idtipo);
            $("#preciov").val(preciov);
            $("#status").val(status);
            $("#status_kardex").val($(this).data("status_kardex"));
        });

        // Guardar cambios del producto
        // Guardar cambios del producto
        $("#editarProductoForm").submit(function(event) {
            event.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: "updateproducto.php",
                type: "POST",
                data: formData, // Enviar datos correctamente formateados
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert(res.message);
                        location.reload(); // Recargar la página para reflejar los cambios
                    } else {
                        alert("Error: " + res.error);
                    }
                },
                error: function(xhr) {
                    const res = JSON.parse(xhr.responseText);
                    alert("Error al actualizar el producto: " + (res.error || "Error desconocido"));
                }
            });
        });
    </script>
</body>

</html>