<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /pedidos/php/view/login.php");
    exit;
}
require_once __DIR__ . '/../controller/UsuarioController.php';
require_once __DIR__ . '/../controller/rollController.php';

$usuarioController = new UsuarioController();
$rolController = new RolController();

$usuarios = $usuarioController->getAllUsuariosRol();
$roles = $rolController->getAllRoles();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1, h2 {
            text-align: center;
        }

        .contenedor {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .tabla-container, .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .btn {
            padding: 6px 12px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-edit { background-color: #007bff; color: white; }
        .btn-edit:hover { background-color: #0056b3; }

        .btn-password { background-color: #28a745; color: white; }
        .btn-password:hover { background-color: #218838; }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-save {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            margin-top: 15px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-save:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h1>Gestión de Usuarios</h1>

    <div class="contenedor">
        <!-- Tabla de Usuarios -->
        <div class="tabla-container">
            <h2>Lista de Usuarios</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Perfil</th>
                        <th>Login</th>
                        <th>Password</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                            <td><button class="btn btn-password" data-id="<?php echo $usuario['id']; ?>">Cambiar</button></td>
                            <td><button class="btn btn-edit"
                                    data-id="<?php echo $usuario['id']; ?>"
                                    data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                                    data-perfil="<?php echo htmlspecialchars($usuario['rol']); ?>">
                                    Editar
                                </button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulario de Edición -->
        <div class="form-container" id="actualizarUsuarioForm" style="display: none;">
            <h2>Actualizar Usuario</h2>
            <form id="editarUsuarioForm">
                <input type="hidden" id="usuarioId" name="id">
                <label for="editNombre">Nombre:</label>
                <input type="text" id="editNombre" name="nombre">

                <label for="editPerfil">Perfil:</label>
                <select id="editPerfil" name="rol_id">
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="editPassword">Nueva Contraseña (Opcional):</label>
                <input type="password" id="editPassword" name="password">

                <button type="submit" class="btn-save">Guardar</button>
            </form>
        </div>
    </div>

    <div class="form-container">
        <h2>Insertar Nuevo Usuario</h2>
        <form id="nuevoUsuarioForm">
            <label for="newNombre">Nombre:</label>
            <input type="text" id="newNombre" name="nombre" required>

            <label for="newLogin">Login Usuario:</label>
            <input type="text" id="newLogin" name="username" required>

            <label for="newPassword">Password:</label>
            <input type="password" id="newPassword" name="password" required>

            <label for="newPerfil">Perfil:</label>
            <select id="newPerfil" name="rol_id">
                <?php foreach ($roles as $rol): ?>
                    <option value="<?php echo $rol['id']; ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-save">Guardar Nuevo Usuario</button>
        </form>
    </div>

    <script>
        // Mostrar formulario de edición con datos pre-cargados
        $(document).on("click", ".btn-edit", function() {
            let usuarioId = $(this).data("id");
            let nombre = $(this).data("nombre");
            let perfil = $(this).data("perfil");

            $("#usuarioId").val(usuarioId);
            $("#editNombre").val(nombre);

            // Auto-seleccionar perfil en el dropdown
            $("#editPerfil option").each(function() {
                if ($(this).text() === perfil) {
                    $(this).prop("selected", true);
                }
            });

            $("#editPassword").val(""); // Vaciar el campo de contraseña
            $("#actualizarUsuarioForm").show();
        });

        // Guardar edición de usuario
        $("#editarUsuarioForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "update_usuario.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert("Usuario actualizado correctamente.");
                    location.reload();
                },
                error: function() {
                    alert("Error al actualizar el usuario.");
                }
            });
        });

        // Insertar nuevo usuario
        $("#nuevoUsuarioForm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "insert_usuario.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    alert("Usuario agregado correctamente.");
                    location.reload();
                },
                error: function() {
                    alert("Error al agregar el usuario.");
                }
            });
        });
    </script>

</body>
</html>
