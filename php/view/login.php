<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: /pedidos/index.php");
    exit;
}

// Generar token CSRF (en login.php)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Manejo del formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../controller/AuthController.php'; // Ruta corregida

    $authController = new AuthController();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar token CSRF
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido.");
    }

    try {
        // Intentar el login
        $authController->login($username, $password);
        if ($_SESSION['rol_id'] === 1)
            header("Location: /pedidos/index.php"); // Redirigir al sistema principal
        if ($_SESSION['rol_id'] === 2)
            header("Location: repgeneral.php"); // Redirigir al sistema principal
        if ($_SESSION['rol_id'] === 3)
            header("Location: kardex.php"); // Redirigir al sistema principal
        if ($_SESSION['rol_id'] === 4)
            header("Location: masters.php"); // Redirigir al sistema principal
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/pedidos/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="width:20%;"></td>
            <td style="width:20%;">
                <div style="margin-top: 100px; ">
                    <h1>Iniciar Sesión</h1>
                </div>

            </td>
            <td style="width:20%;">

                <form method="POST" action="login.php">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                    <button type="submit">Iniciar Sesión</button>
                    <?php if (isset($error)): ?>
                        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                </form>
            </td>
            <td style="width: 30%;"></td>
        </tr>
    </table>

</body>

</html>