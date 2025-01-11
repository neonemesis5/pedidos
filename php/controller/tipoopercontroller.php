<?php

require_once __DIR__ . '/../model/TipoOperModel.php';
require_once __DIR__ . '/BaseController.php';

class TipoOperController extends BaseController {
    private $tipoOperModel;

    public function __construct() {
        $this->tipoOperModel = new TipoOperModel();
    }

    /**
     * Obtiene todos los tipos de operación.
     */
    public function getAllTipoOper() {
        try {
            $tipoOper = $this->tipoOperModel->getAllTipoOper();
            $this->jsonResponse($tipoOper);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un tipo de operación por su ID.
     *
     * @param int $id ID del tipo de operación.
     */
    public function getTipoOperById($id) {
        if (!$id) {
            $this->errorResponse("El ID del tipo de operación es requerido.", 400);
        }

        try {
            $tipoOper = $this->tipoOperModel->getTipoOperById($id);
            if ($tipoOper) {
                $this->jsonResponse($tipoOper);
            } else {
                $this->errorResponse("Tipo de operación no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
