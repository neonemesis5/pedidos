<?php
session_start(); // Inicia la sesión

// Verifica si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php"); // Redirige al login si no está autenticado
    exit;
}
?>
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
    </div>
    <div class="header-actions">
      <button id="btnlistadop" onclick="window.location.href='php/view/listadopedido.php'">Listado de Pedidos del Día</button>
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
