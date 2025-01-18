<?php

require_once __DIR__ . '/../model/FacturaCompraModel.php';
require_once __DIR__ . '/BaseController.php';

class FacturaCompraController extends BaseController {
    private $facturaCompraModel;

    public function __construct() {
        $this->facturaCompraModel = new FacturaCompraModel();
    }

    /**
     * Obtiene todas las facturas de compra.
     */
    public function getAllFacturasCompra() {
        try {
            $facturas = $this->facturaCompraModel->getAllFacturasCompra();
            $this->jsonResponse($facturas);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene una factura de compra por su ID.
     *
     * @param int $id ID de la factura.
     */
    public function getFacturaCompraById($id) {
        if (!$id) {
            $this->errorResponse("El ID de la factura es requerido.", 400);
        }

        try {
            $factura = $this->facturaCompraModel->getFacturaCompraById($id);
            if ($factura) {
                $this->jsonResponse($factura);
            } else {
                $this->errorResponse("Factura no encontrada.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Crea una nueva factura de compra.
     *
     * @param array $data Datos de la factura.
     */

    public function addFacturaCompra($data) {
        try {
            $facturaID = $this->facturaCompraModel->addFacturaCompra($data);
    
            // Log del ID generado
            file_put_contents("debug.log", "Factura creada exitosamente - ID generado: $facturaID\n", FILE_APPEND);
    
            if ($facturaID) {
                return $facturaID; // Retorna el ID del pedido recién creado
            } else {
                throw new Exception("Error al crear la Factura de compra.");
            }
        } catch (Exception $e) {
            file_put_contents("debug.log", "addFacturaCompra - Error: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception($e->getMessage());
        }
    }
    


    /**
     * Actualiza una factura de compra existente.
     *
     * @param int $id ID de la factura.
     * @param array $data Datos actualizados.
     */
    public function updateFacturaCompra($id, $data) {
        if (!$id) {
            $this->errorResponse("El ID de la factura es requerido.", 400);
        }

        try {
            $this->facturaCompraModel->updateFacturaCompra($data, $id);
            $this->jsonResponse(['message' => 'Factura actualizada exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina una factura de compra por su ID.
     *
     * @param int $id ID de la factura.
     */
    public function deleteFacturaCompra($id) {
        if (!$id) {
            $this->errorResponse("El ID de la factura es requerido.", 400);
        }

        try {
            $this->facturaCompraModel->deleteFacturaCompra($id);
            $this->jsonResponse(['message' => 'Factura eliminada exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
