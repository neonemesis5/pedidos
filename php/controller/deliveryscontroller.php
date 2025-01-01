<?php

require_once __DIR__ . '/../model/DeliverysModel.php';
require_once __DIR__ . '/BaseController.php';

class DeliverysController extends BaseController {
    private $deliverysModel;

    public function __construct() {
        $this->deliverysModel = new DeliverysModel();
    }

    /**
     * Obtiene todos los registros de deliverys con información asociada.
     */
    public function getAllDeliverys() {
        try {
            $deliverys = $this->deliverysModel->getAllDeliverys();
            $this->jsonResponse($deliverys);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un registro de delivery con información asociada por su ID.
     *
     * @param int $id ID del registro.
     */
    public function getDeliveryById($id) {
        if (!$id) {
            $this->errorResponse("El ID del delivery es requerido.", 400);
        }

        try {
            $delivery = $this->deliverysModel->getDeliveryById($id);
            if ($delivery) {
                $this->jsonResponse($delivery);
            } else {
                $this->errorResponse("Delivery no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Inserta un nuevo registro en la tabla deliverys.
     */
    public function addDelivery() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['location_id'], $data['pedido_id'], $data['moneda_id'], $data['monto'], $data['status'])) {
                $this->errorResponse("Datos incompletos para el registro de delivery.", 400);
            }

            $this->deliverysModel->addDelivery($data);
            $this->jsonResponse("Delivery agregado exitosamente.");
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
