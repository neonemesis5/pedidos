<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Maestros</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }

        /* Menú lateral */
        .menu {
            background-color: lightgreen;
            width: 220px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .menu h2 {
            margin-bottom: 20px;
            font-size: 18px;
            text-align: center;
        }

        .menu button {
            background: #3498DB;
            color: white;
            border: none;
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
            text-align: left;
        }

        .menu button:hover {
            background: #1ABC9C;
        }

        .logout-btn {
            background: #E74C3C !important;
        }

        .logout-btn:hover {
            background: #C0392B !important;
        }

        /* Contenedor de contenido */
        .contenido {
            flex-grow: 1;
            padding: 20px;
            background: #ECF0F1;
            overflow-y: auto;
            height: calc(100vh - 40px);
            border-left: 2px solid #ccc;
        }

        .loading {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- Menú lateral -->
    <div class="menu">
        <h2>📋 Menú de Maestros</h2>
        <button onclick="loadPage('productosmaster.php')">📦 Productos</button>
        <button onclick="loadPage('tasasmaster.php')">💱 Tasas de Cambio</button>
        <button onclick="loadPage('usuariosmaster.php')">👥 Usuarios</button>
        <button onclick="loadPage('ingredientesmaster.php')">📊 Estructura de Costo</button>
        <button class="logout-btn" onclick="logout()">🚪 Cerrar Sesión</button>
    </div>

    <!-- Contenido Principal -->
    <div class="contenido" id="contenido">
        <h1>📌 Bienvenido al Panel de Maestros</h1>
        <p>Selecciona una opción del menú para comenzar.</p>
    </div>

    <script>
        function logout() {
            window.location.href = "logout.php";
        }

        function loadPage(page) {
            $("#contenido").html('<p class="loading">Cargando contenido...</p>');

            $.ajax({
                url: page,
                type: "GET",
                success: function(response) {
                    $("#contenido").html(response);
                },
                error: function() {
                    $("#contenido").html('<p class="loading">Error al cargar la página.</p>');
                }
            });
        }
    </script>

</body>
</html>
