<?php

require_once __DIR__ . '/../model/RepAdminModel.php';
require_once __DIR__ . '/BaseController.php';

class RepAdminController extends BaseController {
    private $repAdminModel;

    public function __construct() {
        $this->repAdminModel = new RepAdminModel();
    }

    /**
     * Genera el reporte diario de caja para una fecha específica.
     *
     * @param string $fecha Fecha en formato 'YYYY-MM-DD'.
     */
    public function getDiarioCaja($fecha) {
        if (!$fecha) {
            $this->errorResponse("La fecha es requerida.", 400);
        }

        try {
            $data = $this->repAdminModel->getDiarioCaja($fecha);
            return $data;//$this->jsonResponse($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Genera el reporte de ventas diarias para una fecha y clase específica.
     *
     * @param string $fecha Fecha en formato 'YYYY-MM-DD'.
     * @param string|null $clase Clase de pedido ('C', 'I', 'P', 'A'). Si es null, no se filtra por clase.
     */
    public function getVentasDiaria($fecha, $clase = null) {
        if (!$fecha) {
            $this->errorResponse("La fecha es requerida.", 400);
        }

        try {
            $data = $this->repAdminModel->getVentasDiaria($fecha, $clase);
            //$this->jsonResponse($data);
            return $data;
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
