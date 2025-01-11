<?php

require_once __DIR__ . '/../model/MovDiarioModel.php';
require_once __DIR__ . '/BaseController.php';

class MovDiarioController extends BaseController {
    private $movDiarioModel;

    public function __construct() {
        $this->movDiarioModel = new MovDiarioModel();
    }

    /**
     * Obtiene todos los movimientos del diario.
     */
    public function getAllMovDiario() {
        try {
            $movimientos = $this->movDiarioModel->getAllMovDiario();
            $this->jsonResponse($movimientos);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un movimiento del diario por su ID.
     *
     * @param int $id ID del movimiento del diario.
     */
    public function getMovDiarioById($id) {
        if (!$id) {
            $this->errorResponse("El ID del movimiento del diario es requerido.", 400);
        }

        try {
            $movDiario = $this->movDiarioModel->getMovDiarioById($id);
            if ($movDiario) {
                $this->jsonResponse($movDiario);
            } else {
                $this->errorResponse("Movimiento del diario no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function createMovDiario($data) {
        try {
            return $this->movDiarioModel->addMovDiario($data);
        } catch (Exception $e) {
            throw new Exception("Error al crear el movimiento del diario: " . $e->getMessage());
        }
    }
    
}
