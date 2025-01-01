<?php

require_once __DIR__ . '/../model/DetalleFormaPagoModel.php';
require_once __DIR__ . '/BaseController.php';

class DetalleFormaPagoController extends BaseController {
    private $detalleFormaPagoModel;

    public function __construct() {
        $this->detalleFormaPagoModel = new DetalleFormaPagoModel();
    }

    /**
     * Obtiene todos los detalles de forma de pago.
     */
    public function getAllDetallesFormaPago() {
        try {
            $detalles = $this->detalleFormaPagoModel->getAllDetallesFormaPago();
            $this->jsonResponse($detalles);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene detalles de forma de pago por pedido ID.
     *
     * @param int $pedido_id ID del pedido.
     */
    public function getDetallesByPedidoId($pedido_id) {
        if (!$pedido_id) {
            $this->errorResponse("El ID del pedido es requerido.", 400);
        }

        try {
            $detalles = $this->detalleFormaPagoModel->getDetallesByPedidoId($pedido_id);
            $this->jsonResponse($detalles);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
