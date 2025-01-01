<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tabla 2x2 - AJAX</title>
  <script src="js/main.js" defer></script>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td {
      border: 1px solid #ccc;
      padding: 10px;
      vertical-align: top;
    }
    .container {
      height: 200px;
      overflow-y: auto;
    }
  </style>
</head>
<body>
  <h1>Tabla 2x2 - AJAX</h1>
  <table>
    <tr>
      <!-- Tipos de productos -->
      <td id="c00" class="container">Cargando tipos de productos...</td>
      <!-- Productos según tipo -->
      <td id="c01" class="container">Selecciona un tipo de producto</td>
    </tr>
    <tr>
      <!-- Tabla de productos seleccionados -->
      <td id="c10" class="container">Productos seleccionados aparecerán aquí</td>
      <!-- Métodos de pago -->
      <td id="c11" class="container">Métodos de pago y guardar pedido</td>
    </tr>
  </table>
</body>
</html>
