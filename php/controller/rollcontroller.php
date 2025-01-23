<?php

require_once __DIR__ . '/../model/rollmodel.php';
require_once __DIR__ . '/BaseController.php';

class RolController extends BaseController {
    private $rolModel;

    public function __construct() {
        $this->rolModel = new RolModel();
    }

    /**
     * Obtiene todos los roles.
     */
    public function getAllRoles() {
        try {
            $roles = $this->rolModel->getAllRoles();
            // $this->jsonResponse($roles);
            return $roles;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un rol por su ID.
     */
    public function getRolById($id) {
        if (!$id) {
            $this->errorResponse("El ID del rol es requerido.", 400);
        }

        try {
            $rol = $this->rolModel->getRolById($id);
            if ($rol) {
                $this->jsonResponse($rol);
            } else {
                $this->errorResponse("Rol no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Inserta un nuevo rol.
     */
    public function addRol($data) {
        try {
            $result = $this->rolModel->addRol($data);
            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Rol agregado correctamente."]);
            } else {
                $this->errorResponse("Error al agregar el rol.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un rol existente.
     */
    public function updateRol($id, $data) {
        try {
            $result = $this->rolModel->updateRol($id, $data);
            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Rol actualizado correctamente."]);
            } else {
                $this->errorResponse("Error al actualizar el rol.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina un rol por su ID.
     */
    public function deleteRol($id) {
        try {
            $result = $this->rolModel->deleteRol($id);
            if ($result) {
                $this->jsonResponse(["success" => true, "message" => "Rol eliminado correctamente."]);
            } else {
                $this->errorResponse("Error al eliminar el rol.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
