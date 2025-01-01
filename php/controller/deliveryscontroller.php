<?php

require_once __DIR__ . '/../model/DeliverysModel.php';
require_once __DIR__ . '/BaseController.php';

class DeliverysController extends BaseController {
    private $deliverysModel;

    public function __construct() {
        $this->deliverysModel = new DeliverysModel();
    }

    /**
     * Obtiene todos los registros de deliverys.
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
     * Obtiene un registro por su ID.
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
}
