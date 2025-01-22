<?php

require_once __DIR__ . '/../model/TasaModel.php';
require_once __DIR__ . '/BaseController.php';

class TasaController extends BaseController {
    private $tasaModel;

    public function __construct() {
        $this->tasaModel = new TasaModel();
    }

    /**
     * Obtiene todas las tasas.
     */
    public function getAllTasas() {
        try {
            $tasas = $this->tasaModel->getAllTasas();
            $this->jsonResponse($tasas);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getCurrentRates(){
        try {
            $tasas = $this->tasaModel->getCurrentRates();
            $this->jsonResponse($tasas);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function getCurrentRates2(){
        try {
            $res=$this->tasaModel->getCurrentRates();
            $listCurrent=[];
            foreach ($res as $key => $value) 
                $listCurrent[$value['nombre']]=$value['monto'];
            return $listCurrent;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function getCurrentRates3(){
        try {
            return $this->tasaModel->getCurrentMonedasTasas();
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function insertTasa($data=[]){
        return $this->tasaModel->insertTasa($data['id'],$data['monto']);
    }
    /**
     * Obtiene una tasa por su ID.
     *
     * @param int $id ID de la tasa.
     */
    public function getTasaById($id) {
        if (!$id) {
            $this->errorResponse("El ID de la tasa es requerido.", 400);
        }

        try {
            $tasa = $this->tasaModel->getTasaById($id);
            if ($tasa) {
                $this->jsonResponse($tasa);
            } else {
                $this->errorResponse("Tasa no encontrada.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
