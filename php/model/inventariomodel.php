<?php

require_once __DIR__ . '/BaseModel.php';

class InventarioModel extends BaseModel {

    /**
     * Obtiene las compras realizadas antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para las compras.
     * @return array Lista de compras.
     */
    public function getCompras($fecha) {
        $sql = "SELECT pro.id, pro.nombre, SUM(det.qty) AS cantidad_comprada
                FROM detallecompra det
                JOIN factura_compra fac ON fac.id = det.factcompra_id
                JOIN producto pro ON pro.id = det.producto_id
                WHERE fac.fecha::date <= :fecha
                GROUP BY pro.id, pro.nombre";
        
        return $this->customQuery($sql, ['fecha' => $fecha]);
    }

    /**
     * Obtiene los ingresos realizados antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para los ingresos.
     * @return array Lista de ingresos.
     */
    public function getIngresos($fecha) {
        $sql = "SELECT pro.id, pro.nombre, SUM(mov.qty) AS cantidad_ingresada
                FROM movdiario mov
                JOIN producto pro ON pro.id = mov.producto_id
                JOIN diario dia ON dia.id = mov.diario_id
                WHERE dia.fecha::date <= :fecha AND dia.tipooper_id = 1000
                GROUP BY pro.id, pro.nombre";
        
        return $this->customQuery($sql, ['fecha' => $fecha]);
    }

    /**
     * Obtiene los egresos realizados antes de una fecha específica.
     *
     * @param string $fecha Fecha límite para los egresos.
     * @return array Lista de egresos.
     */
    public function getEgresos($fecha) {
        $sql = "SELECT pro.id, pro.nombre, SUM(mov.qty) AS cantidad_egresada
                FROM movdiario mov
                JOIN producto pro ON pro.id = mov.producto_id
                JOIN diario dia ON dia.id = mov.diario_id
                WHERE dia.fecha::date <= :fecha AND dia.tipooper_id = 1001
                GROUP BY pro.id, pro.nombre";
        
        return $this->customQuery($sql, ['fecha' => $fecha]);
    }
}
