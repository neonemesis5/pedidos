<?php

require_once __DIR__ . '/../model/DetallePedModel.php';
require_once __DIR__ . '/BaseController.php';

class DetallePedController extends BaseController {
    private $detallePedModel;

    public function __construct() {
        $this->detallePedModel = new DetallePedModel();
    }

    /**
     * Obtiene todos los detalles de pedido.
     */
    public function getAllDetalles() {
        try {
            $detalles = $this->detallePedModel->getAllDetalles();
            $this->jsonResponse($detalles);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene detalles por el ID del pedido.
     *
     * @param int $pedido_id ID del pedido.
     */
    public function getDetallesByPedidoId($pedido_id) {
        if (!$pedido_id) {
            $this->errorResponse("El ID del pedido es requerido.", 400);
        }

        try {
            $detalles = $this->detallePedModel->getDetallesByPedidoId($pedido_id);
            $this->jsonResponse($detalles);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
