<?php

require_once __DIR__ . '/../model/InventarioModel.php';
require_once __DIR__ . '/BaseController.php';

class InventarioController extends BaseController {
    private $inventarioModel;

    public function __construct() {
        $this->inventarioModel = new InventarioModel();
    }

    /**
     * Obtiene las compras realizadas antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para las compras.
     * @return array list compras
     */
    public function getCompras($fecha) {
        return  $this->inventarioModel->getCompras($fecha);
    }

    /**
     * Obtiene los ingresos realizados antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para los ingresos.
     * @return array ingresos
     */
    public function getIngresos($fecha) {
        return $this->inventarioModel->getIngresos($fecha);
    }

    /**
     * Obtiene los egresos realizados antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para los egresos.
     * @return array list salidas
     */
    public function getEgresos($fecha) {
        return $this->inventarioModel->getEgresos($fecha);
    }
}
