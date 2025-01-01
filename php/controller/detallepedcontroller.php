<?php

require_once __DIR__ . '/../model/DetallePedModel.php';
require_once __DIR__ . '/BaseController.php';

class DetallePedController extends BaseController {
    private $detallePedModel;

    public function __construct() {
        $this->detallePedModel = new DetallePedModel();
    }

      /**
     * Inserta un nuevo detalle de pedido.
     */
    public function addDetalle() {
        try {
            // Validar entrada
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data || !isset($data['pedido_id'], $data['producto_id'], $data['qty'], $data['status'])) {
                $this->errorResponse("Datos incompletos para el detalle del pedido.", 400);
            }

            // Agregar el detalle
            $this->detallePedModel->addDetalle($data);
            $this->jsonResponse("Detalle agregado exitosamente.");
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
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
