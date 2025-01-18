<?php

require_once __DIR__ . '/../model/ProveedorModel.php';
require_once __DIR__ . '/BaseController.php';

class ProveedorController extends BaseController {
    private $proveedorModel;

    public function __construct() {
        $this->proveedorModel = new ProveedorModel();
    }

    /**
     * Obtiene todos los proveedores.
     */
    public function getAllProveedores() {
        try {
            $proveedores = $this->proveedorModel->getAllProveedoresID();
            // $this->jsonResponse($proveedores);
            return $proveedores;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un proveedor por su ID.
     *
     * @param int $id ID del proveedor.
     */
    public function getProveedorById($id) {
        if (!$id) {
            $this->errorResponse("El ID del proveedor es requerido.", 400);
        }

        try {
            $proveedor = $this->proveedorModel->getProveedorById($id);
            if ($proveedor) {
                return $proveedor;//$this->jsonResponse($proveedor);
            } else {
                $this->errorResponse("Proveedor no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Crea un nuevo proveedor.
     *
     * @param array $data Datos del proveedor.
     */
    public function addProveedor($data) {
        try {
            $result = $this->proveedorModel->addProveedor($data);
            $this->jsonResponse(['message' => 'Proveedor creado exitosamente', 'id' => $result]);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un proveedor existente.
     *
     * @param int $id ID del proveedor.
     * @param array $data Datos actualizados.
     */
    public function updateProveedor($id, $data) {
        if (!$id) {
            $this->errorResponse("El ID del proveedor es requerido.", 400);
        }

        try {
            $this->proveedorModel->updateProveedor($data, $id);
            $this->jsonResponse(['message' => 'Proveedor actualizado exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina un proveedor por su ID.
     *
     * @param int $id ID del proveedor.
     */
    public function deleteProveedor($id) {
        if (!$id) {
            $this->errorResponse("El ID del proveedor es requerido.", 400);
        }

        try {
            $this->proveedorModel->deleteProveedor($id);
            $this->jsonResponse(['message' => 'Proveedor eliminado exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
