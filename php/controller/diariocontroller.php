<?php

require_once __DIR__ . '/../model/DiarioModel.php';
require_once __DIR__ . '/BaseController.php';

class DiarioController extends BaseController {
    private $diarioModel;

    public function __construct() {
        $this->diarioModel = new DiarioModel();
    }

    /**
     * Obtiene todos los registros del diario.
     */
    public function getAllDiario() {
        try {
            $diarios = $this->diarioModel->getAllDiario();
            $this->jsonResponse($diarios);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un registro del diario por su ID.
     *
     * @param int $id ID del registro del diario.
     */
    public function getDiarioById($id) {
        if (!$id) {
            $this->errorResponse("El ID del diario es requerido.", 400);
        }

        try {
            $diario = $this->diarioModel->getDiarioById($id);
            if ($diario) {
                $this->jsonResponse($diario);
            } else {
                $this->errorResponse("Registro del diario no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function createDiario($data) {
        try {
            return $this->diarioModel->addDiario($data);
        } catch (Exception $e) {
            throw new Exception("Error al crear el diario: " . $e->getMessage());
        }
    }
    
}
