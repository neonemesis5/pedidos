<?php

require_once __DIR__ . '/../model/RepkardexModel.php';
require_once __DIR__ . '/BaseController.php';

class RepkardexController extends BaseController {
    private $repkardexModel;

    public function __construct() {
        $this->repkardexModel = new RepkardexModel();
    }

    /**
     * Obtiene todos los registros del reporte de kardex.
     */
    public function getAllRepkardex() {
        try {
            $data = $this->repkardexModel->getAllRepkardex();
            $this->jsonResponse($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Filtra registros por tipo de operación.
     *
     * @param int $idOper ID del tipo de operación.
     */
    public function getByTipoOperacion($idOper) {
        if (!$idOper) {
            $this->errorResponse("El tipo de operación es requerido.", 400);
        }

        try {
            $data = $this->repkardexModel->getByTipoOperacion($idOper);
            $this->jsonResponse($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Filtra registros por rango de fechas.
     *
     * @param string $fechaInicio Fecha de inicio (YYYY-MM-DD).
     * @param string $fechaFin Fecha de fin (YYYY-MM-DD).
     */
    public function getByFecha($fechaInicio, $fechaFin) {
        if (!$fechaInicio || !$fechaFin) {
            $this->errorResponse("Las fechas de inicio y fin son requeridas.", 400);
        }

        try {
            $data = $this->repkardexModel->getByFecha($fechaInicio, $fechaFin);
            $this->jsonResponse($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Filtra registros por producto.
     *
     * @param int $idProducto ID del producto.
     */
    public function getByProducto($idProducto) {
        if (!$idProducto) {
            $this->errorResponse("El ID del producto es requerido.", 400);
        }

        try {
            $data = $this->repkardexModel->getByProducto($idProducto);
            $this->jsonResponse($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Filtra registros por múltiples criterios (tipo operación, fechas, producto).
     *
     * @param array $filters Filtros: tipo operación, fechas, producto.
     */
    public function getFiltered($filters) {
        try {
            $idOper = $filters['idOper'] ?? null;
            $fechaInicio = $filters['fechaInicio'] ?? null;
            $fechaFin = $filters['fechaFin'] ?? null;
            $idProducto = $filters['idProducto'] ?? null;
            $data = $this->repkardexModel->getFiltered($idOper, $fechaInicio, $fechaFin, $idProducto);
            return $data;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
