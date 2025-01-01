<?php

require_once __DIR__ . '/../model/DetalleFormaPagoModel.php';
require_once __DIR__ . '/BaseController.php';

class DetalleFormaPagoController extends BaseController {
    private $detalleFormaPagoModel;

    public function __construct() {
        $this->detalleFormaPagoModel = new DetalleFormaPagoModel();
    }

    /**
     * Obtiene todos los detalles de forma de pago con información asociada.
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
     * Obtiene detalles de forma de pago para un pedido específico con información asociada.
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

    /**
     * Inserta un nuevo detalle de forma de pago.
     */
    public function addDetalleFormaPago() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['pedido_id'], $data['formapago_id'], $data['moneda_id'], $data['monto'], $data['status'])) {
                $this->errorResponse("Datos incompletos para el detalle de forma de pago.", 400);
            }

            $this->detalleFormaPagoModel->addDetalleFormaPago($data);
            $this->jsonResponse("Detalle de forma de pago agregado exitosamente.");
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
