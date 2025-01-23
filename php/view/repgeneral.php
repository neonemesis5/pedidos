<?php
session_start();

// Verificar sesión y rol del usuario
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes del Sistema</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        /* Menú lateral */
        .sidebar {
            width: 220px;
            background: #2C3E50;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .sidebar button {
            background: #3498DB;
            color: white;
            border: none;
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar button:hover {
            background: #1ABC9C;
        }

        .logout-btn {
            background: #E74C3C !important;
        }

        .logout-btn:hover {
            background: #C0392B !important;
        }

        /* Contenedor de reportes */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background: #ECF0F1;
            display: flex;
            flex-direction: column;
        }

        #showinfo {
            flex-grow: 1;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: calc(100vh - 100px);
        }

        .loading {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #666;
        }

        /* Selector de Fecha */
        .date-container {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-container label {
            font-weight: bold;
        }

        .date-container input {
            padding: 5px;
            font-size: 14px;
        }
        td {
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- Menú lateral -->
    <div class="sidebar">
        <h2>📊 Reportes del Sistema</h2>
        <button onclick="loadReport('diarioformasp.php')">📅 Diario de Dinero</button>
        <button onclick="loadReport('diarioventasprod.php')">🛒 Ventas de Mercancía</button>
        <button onclick="loadReport('repkardex2.php')">📦 Entradas y Salidas</button>
        <button onclick="loadReport('repcompras2.php')">🛍 Compras Mercancía</button>
        <button onclick="loadReport('inventario.php')">📑 Inventario</button>
        <button class="logout-btn" onclick="logout()">🚪 Cerrar Sesión</button>
    </div>

    <!-- Contenido Principal -->
    <div class="main-content">
        <h1>📊 Visualización de Reportes</h1>

        <!-- Selector de Fecha -->
        <div class="date-container">
            <label for="reportDate">📅 Seleccionar Fecha:</label>
            <input type="date" id="reportDate" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <div id="showinfo">
            <p class="loading">Seleccione un reporte para visualizarlo aquí.</p>
        </div>
    </div>

    <script>
        let currentReport = ""; // Variable global para almacenar el reporte seleccionado

        function logout() {
            window.location.href = "logout.php";
        }

        function loadReport(url) {
            const selectedDate = $("#reportDate").val(); // Obtener la fecha seleccionada
            currentReport = url; // Guardar el último reporte cargado

            $("#showinfo").html('<p class="loading">Cargando reporte...</p>');

            $.ajax({
                url: url + "?fecha=" + selectedDate,
                type: "GET",
                success: function(response) {
                    $("#showinfo").html(response);
                },
                error: function() {
                    $("#showinfo").html('<p class="loading">Error al cargar el reporte.</p>');
                }
            });
        }

        // Evento para cambiar la fecha y actualizar el reporte actualmente visible
        $("#reportDate").change(function() {
            if (currentReport) {
                loadReport(currentReport);
            }
        });
    </script>

</body>

</html>
