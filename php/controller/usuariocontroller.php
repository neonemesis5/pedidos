<?php

require_once __DIR__ . '/../model/UsuarioModel.php';
require_once __DIR__ . '/BaseController.php';

class UsuarioController extends BaseController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Obtiene todos los usuarios.
     */
    public function getAllUsuarios() {
        try {
            $usuarios = $this->usuarioModel->getAllUsuarios();
            $this->jsonResponse($usuarios);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getAllUsuariosRol() {
        try {
            $usuarios = $this->usuarioModel->getUsuariosRol();
            return $usuarios;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    } 
    /**
     * Obtiene un usuario por su ID.
     */
    public function getUsuarioById($id) {
        if (!$id) {
            $this->errorResponse("El ID del usuario es requerido.", 400);
        }

        try {
            $usuario = $this->usuarioModel->getUsuarioById($id);
            if ($usuario) {
                $this->jsonResponse($usuario);
            } else {
                $this->errorResponse("Usuario no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Inserta un nuevo usuario.
     */
    public function addUsuario($data) {
        try {
            // Encriptar la contraseña antes de insertar
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            $result = $this->usuarioModel->addUsuario($data);

            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Usuario agregado correctamente."]);
            } else {
                $this->errorResponse("Error al agregar el usuario.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un usuario existente.
     */
    public function updateUsuario($id, $data) {
        try {
            if (isset($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }

            $result = $this->usuarioModel->updateUsuario($id, $data);
            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Usuario actualizado correctamente."]);
            } else {
                $this->errorResponse("Error al actualizar el usuario.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina un usuario por su ID.
     */
    public function deleteUsuario($id) {
        try {
            $result = $this->usuarioModel->deleteUsuario($id);
            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Usuario eliminado correctamente."]);
            } else {
                $this->errorResponse("Error al eliminar el usuario.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
