<?php

require_once __DIR__ . '/BaseModel.php';

class RepAdminModel extends BaseModel
{
    private $table = 'detalle_formap'; // Tabla principal utilizada en la consulta

    /**
     * Obtiene el reporte diario de caja.
     *
     * @param string $fecha Fecha en formato 'YYYY-MM-DD'.
     * @return array Lista de registros del reporte diario.
     */
    public function getDiarioCaja($fecha)
    {
        $sql = "
            SELECT 
                CONCAT(fp.id, '-', mon.id) AS ID,
                fp.nombre AS forma_pago,
                mon.nombre AS moneda,
                SUM(dfp.monto) AS total
            FROM formapago fp 
            JOIN detalle_formap dfp ON dfp.formapago_id = fp.id
            JOIN moneda mon ON mon.id = dfp.moneda_id
            WHERE dfp.fecha::date = :fecha
            GROUP BY CONCAT(fp.id, '-', mon.id), fp.nombre, mon.nombre
            ORDER BY 2
        ";

        return $this->customQuery($sql, ['fecha' => $fecha]);
    }
    /**
     * Obtiene el reporte diario de caja.
     *
     * @param string $fecha Fecha en formato 'YYYY-MM-DD'.
     * @param string|null $clase Clase de pedido ('C', 'I', 'P', 'A'). Si es null, no se filtra por clase.
     * @return array Lista de registros del reporte diario.
     */
    public function getVentasDiaria($fecha, $clase = null)
    {
            $claseCondition = !empty($clase) ? "ped.status = :clase AND " : "";
        $sql = "
            SELECT 
                tpo.nombre AS tipo_producto,
                pro.nombre AS producto,
                SUM(det.qty) AS qty_total,
                det.preciov AS precio_unitario,
                SUM(det.preciov * det.qty) AS total_venta
            FROM detalleped det
            JOIN producto pro ON pro.id = det.producto_id
            JOIN pedido ped ON ped.id = det.pedido_id
            JOIN tipo_producto tpo ON tpo.id = pro.tipoproducto_id
            WHERE {$claseCondition} ped.fecha::date = :fecha
            GROUP BY tpo.nombre, pro.nombre, det.preciov
        ";
        // Parámetros de la consulta
        $params = ['fecha' => $fecha];
        if (!empty($clase)) 
            $params['clase'] = $clase;
        // Ejecutar la consulta y devolver los resultados
        return $this->customQuery($sql, $params);
    }
}
