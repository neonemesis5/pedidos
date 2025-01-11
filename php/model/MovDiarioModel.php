<?php

require_once __DIR__ . '/BaseModel.php';

class MovDiarioModel extends BaseModel {
    private $table = 'movdiario';

    public function getAllMovDiario() {
        return $this->getAll($this->table);
    }

    public function getMovDiarioById($id) {
        return $this->getById($this->table, $id);
    }
    public function addMovDiario($data) {
        $sql = "INSERT INTO movdiario (producto_id, diario_id, qty, observacion, status) 
                VALUES (:producto_id, :diario_id, :qty, :observacion, :status)";
        return $this->customQuery($sql, $data);
    }
    
}
