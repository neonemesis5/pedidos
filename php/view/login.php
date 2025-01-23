<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: /pedidos/index.php");
    exit;
}

// Manejo del formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../controller/AuthController.php'; // Ruta corregida

    $authController = new AuthController();

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // print_r(array($username,$password));
    try {
        // Intentar el login
        $authController->login($username, $password);
        if( $_SESSION['rol_id'] === 1 )
            header("Location: /pedidos/index.php"); // Redirigir al sistema principal
        if( $_SESSION['rol_id'] === 2 )
            header("Location: repgeneral.php"); // Redirigir al sistema principal
        if( $_SESSION['rol_id'] === 3 )
            header("Location: kardex.php"); // Redirigir al sistema principal
        if( $_SESSION['rol_id'] === 4 )
            header("Location: masters.php"); // Redirigir al sistema principal
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/pedidos/css/styles.css"> <!-- Ruta CSS corregida -->
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="login.php">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Iniciar Sesión</button>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
