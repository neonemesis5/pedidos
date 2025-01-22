<?php
session_start();
require_once __DIR__ . '/../controller/TasaController.php';

$tasaController = new TasaController();
$tasas = $tasaController->getCurrentRates3();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tasas de Cambio</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .contenedor {
            display: flex;
            justify-content: space-between;
        }

        .tabla-container {
            width: 60%;
            border: 1px solid black;
            padding: 15px;
        }

        .form-container {
            width: 35%;
            border: 1px solid black;
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .nueva-tasa-btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .nueva-tasa-btn:hover {
            background-color: #0056b3;
        }

        #nuevaTasaForm {
            display: none;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }

        .guardar-btn {
            width: 100%;
            margin-top: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .guardar-btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h1>Tasas de Cambio de las Monedas</h1>
    <div class="contenedor">
        <div class="tabla-container">
            <h2>Listado de tasas actuales</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Moneda Base</th>
                        <th>Valor</th>
                        <th>Moneda Destino</th>
                        <th>Valor</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasas as $tasa) : ?>
                        <tr>
                            <td><?php echo $tasa['id']; ?></td>
                            <td><?php echo $tasa['fecha']; ?></td>
                            <td><?php echo htmlspecialchars($tasa['nom1']); ?></td>
                            <td>1</td>
                            <td><?php echo htmlspecialchars($tasa['nom2']); ?></td>
                            <td><?php
                                    if($tasa['nom1'] === 'COP' && $tasa['nom2'] === 'BSS')
                                        echo number_format($tasa['monto'], 4, ',', '.'); 
                                    else
                                        echo number_format($tasa['monto'], 0, ',', '.'); 
                                ?>
                            </td>
                            <td>
                                <button class="nueva-tasa-btn"
                                    data-id="<?php echo htmlspecialchars($tasa['id']); ?>"
                                    data-base="<?php echo htmlspecialchars($tasa['nom1']); ?>"
                                    data-destino="<?php echo htmlspecialchars($tasa['nom2']); ?>">
                                    Nueva Tasa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="form-container">
            <h2>Nueva Tasa</h2>
            <form id="nuevaTasaForm">
                <label for="id">ID Actualizar</label>
                <input type="text" id="id" name="id" readonly>
                
                <label for="monedaBase">Moneda Base:</label>
                <input type="text" id="monedaBase" name="monedaBase" readonly>

                <label for="monedaDestino">Moneda Destino:</label>
                <input type="text" id="monedaDestino" name="monedaDestino" readonly>

                <label for="factor">Factor:</label>
                <input type="number" step="0.000001" id="factor" name="factor" required>

                <button type="submit" class="guardar-btn">Guardar Nueva Tasa</button>
            </form>
        </div>
    </div>

    <script>
        $(document).on("click", ".nueva-tasa-btn", function() {
            $("#id").val($(this).data("id"));
            $("#monedaBase").val($(this).data("base"));
            $("#monedaDestino").val($(this).data("destino"));
            $("#factor").val("");
            $("#nuevaTasaForm").show();
        });

        $("#nuevaTasaForm").submit(function(event) {
            event.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: "inserttasa.php",
                type: "POST",
                data: formData,
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert("Tasa insertada correctamente.");
                        location.reload();
                    } else {
                        alert("Error: " + res.error);
                    }
                },
                error: function() {
                    alert("Error al insertar la tasa.");
                }
            });
        });
    </script>
</body>

</html>