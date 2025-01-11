<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gestión de Pedidos</title>
  <script src="js/main.js" defer></script>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <div class="header-container">
    <h1>Sistema de Gestión de Pedidos</h1>
    <div class="header-actions">
      <h3>Pedido NRO: <span id="pedidoNro">Cargando...</span></h3>
      <button id="btnlogout" onclick="logout()">Cerrar Sesión</button>
    </div>
  </div>

  <table>
    <tr>
      <!-- Tipos de productos -->
      <td id="c00" class="container row1">Cargando tipos de productos...</td>
      <!-- Productos según tipo -->
      <td id="c01" class="container row1">
        Selecciona un tipo de producto
      </td>
    </tr>
    <tr>
      <!-- Tabla de productos seleccionados -->
      <td id="c10" class="container">
        <p>El carrito está vacío.</p>
      </td>
      <!-- Métodos de pago -->
      <td id="c11" class="container"><button id="saveOrder">Guardar Pedido</button></td>
    </tr>
  </table>

  <div id="asadoModal" class="modal" style="display: none;">
    <div class="modal-content">
      <span id="closeModal" class="close">&times;</span>
      <h2>Seleccionar Gramos</h2>
      <label for="gramosInput">Gramos:</label>
      <input type="number" id="gramosInput" placeholder="Ingrese gramos" min="1" style="margin-right: 10px;" />
      <button id="addAsadoButton">Agregar</button>
    </div>
  </div>

</body>

</html>
