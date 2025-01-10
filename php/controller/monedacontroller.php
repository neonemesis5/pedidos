<?php

require_once __DIR__ . '/../model/MonedaModel.php';
require_once __DIR__ . '/BaseController.php';

class MonedaController extends BaseController {
    private $monedaModel;

    public function __construct() {
        $this->monedaModel = new MonedaModel();
    }

    /**
     * Obtiene todas las monedas.
     */
    public function getAllMonedas() {
        try {
            $monedas = $this->monedaModel->getAllMonedas();
            $this->jsonResponse($monedas);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function getAllMonedas2() {
        try {
            $monedas = $this->monedaModel->getAllMonedas();
            return $monedas;
        } catch (Exception $e) {
            throw new Exception("Error al obtener las monedas: " . $e->getMessage());
        }
    }
    
    /**
     * Obtiene una moneda por su ID.
     *
     * @param int $id ID de la moneda.
     */
    public function getMonedaById($id) {
        if (!$id) {
            $this->errorResponse("El ID de la moneda es requerido.", 400);
        }

        try {
            $moneda = $this->monedaModel->getMonedaById($id);
            if ($moneda) {
                $this->jsonResponse($moneda);
            } else {
                $this->errorResponse("Moneda no encontrada.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
