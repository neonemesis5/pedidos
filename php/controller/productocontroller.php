<?php

require_once __DIR__ . '/../model/ProductoModel.php';
require_once __DIR__ . '/BaseController.php';

class ProductoController extends BaseController {
    private $productoModel;

    public function __construct() {
        $this->productoModel = new ProductoModel();
    }

    /**
     * Obtiene todos los productos.
     */
    public function getAllProductos() {
        try {
            $productos = $this->productoModel->getAllProductos();
            $this->jsonResponse($productos);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene productos por tipo de producto.
     *
     * @param int $tipoProductoId ID del tipo de producto.
     */

    public function getProductosByTipoProducto($tipoProductoId) {
        if (!$tipoProductoId) {
            throw new Exception("El ID del tipo de producto es requerido.");
        }
    
        try {
            return $this->productoModel->getProductosByTipoProducto($tipoProductoId); // Devuelve un array
        } catch (Exception $e) {
            throw new Exception("Error al obtener los productos: " . $e->getMessage());
        }
    }
    public function getProductosConUnidadPorTipo($tipoProductoId) {
        if (!$tipoProductoId) {
            throw new Exception("El ID del tipo de producto es requerido.");
        }
        try {
            return $this->productoModel->getProductosConUnidadMedidaPorTipo($tipoProductoId);
        } catch (Exception $e) {
            throw new Exception("Error al obtener los productos con unidad de medida: " . $e->getMessage());
        }
    }
}
