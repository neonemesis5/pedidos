<?php

require_once __DIR__ . '/../model/TipoProductoModel.php';
require_once __DIR__ . '/BaseController.php';

class TipoProductoController extends BaseController {
    private $tipoProductoModel;

    public function __construct() {
        $this->tipoProductoModel = new TipoProductoModel();
    }

    /**
     * Obtiene todos los tipos de producto.
     */
    // public function getAllTiposProductos() {
    //     try {
    //         $tiposProductos = $this->tipoProductoModel->getAllTiposProductos();
    //         $this->jsonResponse($tiposProductos);
    //     } catch (Exception $e) {
    //         $this->errorResponse($e->getMessage(), 500);
    //     }
    // }
    public function getAllTiposProductos() {
        try {
            return $this->tipoProductoModel->getAllTiposProductos2(); // Devuelve un array
        } catch (Exception $e) {
            throw new Exception("Error al obtener los tipos de productos: " . $e->getMessage());
        }
    }
    

    /**
     * Obtiene un tipo de producto por su ID.
     *
     * @param int $id ID del tipo de producto.
     */
    public function getTipoProductoById($id) {
        if (!$id) {
            $this->errorResponse("El ID del tipo de producto es requerido.", 400);
        }

        try {
            $tipoProducto = $this->tipoProductoModel->getTipoProductoById($id);
            if ($tipoProducto) {
                $this->jsonResponse($tipoProducto);
            } else {
                $this->errorResponse("Tipo de producto no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
