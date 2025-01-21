<?php

require_once __DIR__ . '/BaseModel.php';

class RepkardexModel extends BaseModel {
    private $table = 'repkardex'; // La vista creada

    /**
     * Obtiene todos los registros del reporte de kardex.
     *
     * @return array Lista de registros.
     */
    public function getAllRepkardex() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql);
    }

    /**
     * Obtiene registros por tipo de operación.
     *
     * @param int $idOper ID del tipo de operación (1000 = ingreso, 1001 = salida, etc.).
     * @return array Lista de registros filtrados por tipo de operación.
     */
    public function getByTipoOperacion($idOper) {
        $sql = "SELECT * FROM {$this->table} WHERE idoper = :idoper";
        return $this->db->query($sql, ['idoper' => $idOper]);
    }

    /**
     * Obtiene registros por rango de fechas.
     *
     * @param string $fechaInicio Fecha de inicio en formato 'YYYY-MM-DD'.
     * @param string $fechaFin Fecha de fin en formato 'YYYY-MM-DD'.
     * @return array Lista de registros filtrados por rango de fechas.
     */
    public function getByFecha($fechaInicio, $fechaFin) {
        $sql = "SELECT * FROM {$this->table} WHERE fecha BETWEEN :fechaInicio AND :fechaFin";
        return $this->db->query($sql, [
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
        ]);
    }

    /**
     * Obtiene registros por ID de producto.
     *
     * @param int $idProducto ID del producto.
     * @return array Lista de registros filtrados por producto.
     */
    public function getByProducto($idProducto) {
        $sql = "SELECT * FROM {$this->table} WHERE idpro = :idpro";
        return $this->db->query($sql, ['idpro' => $idProducto]);
    }

    /**
     * Obtiene registros con múltiples filtros: operación, fechas y producto.
     *
     * @param int|null $idOper ID del tipo de operación.
     * @param string|null $fechaInicio Fecha de inicio.
     * @param string|null $fechaFin Fecha de fin.
     * @param int|null $idProducto ID del producto.
     * @return array Lista de registros filtrados.
     */
    public function getFiltered($idOper = null, $fechaInicio = null, $fechaFin = null, $idProducto = null) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1"; // Query base
        $params = [];
    
        if (!empty($idOper)) { // Verifica si $idOper no está vacío
            $sql .= " AND idoper = :idoper";
            $params['idoper'] = $idOper;
        }
    
        if (!empty($fechaInicio) && !empty($fechaFin)) { // Verifica si ambas fechas no están vacías
            $sql .= " AND fecha :: date BETWEEN :fechaInicio AND :fechaFin";
            $params['fechaInicio'] = $fechaInicio;
            $params['fechaFin'] = $fechaFin;
        }
    
        if (!empty($idProducto)) { // Verifica si $idProducto no está vacío
            $sql .= " AND idpro = :idpro";
            $params['idpro'] = $idProducto;
        }
    // print_r(array($sql,$params));
        return $this->db->query($sql, $params);
    }
    
}
