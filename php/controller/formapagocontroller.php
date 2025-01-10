<?php

require_once __DIR__ . '/../model/FormaPagoModel.php';
require_once __DIR__ . '/BaseController.php';

class FormaPagoController extends BaseController {
    private $formaPagoModel;

    public function __construct() {
        $this->formaPagoModel = new FormaPagoModel();
    }

    /**
     * Obtiene todas las formas de pago.
     */
    // public function getAllFormasPago() {
    //     try {
    //         $formasPago = $this->formaPagoModel->getAllFormasPago();
    //         $this->jsonResponse($formasPago);
    //     } catch (Exception $e) {
    //         $this->errorResponse($e->getMessage(), 500);
    //     }
    // }

    public function getAllFormasPagoArray() {
        try {
            return $this->formaPagoModel->getAllFormasPago();
        } catch (Exception $e) {
            throw new Exception("Error al obtener las formas de pago: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene una forma de pago por su ID.
     *
     * @param int $id ID de la forma de pago.
     */
    public function getFormaPagoById($id) {
        if (!$id) {
            $this->errorResponse("El ID de la forma de pago es requerido.", 400);
        }

        try {
            $formaPago = $this->formaPagoModel->getFormaPagoById($id);
            if ($formaPago) {
                $this->jsonResponse($formaPago);
            } else {
                $this->errorResponse("Forma de pago no encontrada.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
