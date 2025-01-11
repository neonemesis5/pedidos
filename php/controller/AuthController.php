<?php

require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function login($username, $password) {
        // Buscar al usuario por nombre de usuario
        $user = $this->userModel->getUserByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Usuario o contraseña incorrectos.");
        }

        // Iniciar sesión
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['rol_id'] = $user['rol_id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['status'] = $user['status'];
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /login.php");
        exit;
    }
}
