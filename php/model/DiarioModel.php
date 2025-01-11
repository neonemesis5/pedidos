<?php

require_once __DIR__ . '/BaseModel.php';

class DiarioModel extends BaseModel {
    private $table = 'diario';

    public function getAllDiario() {
        return $this->getAll($this->table);
    }

    public function getDiarioById($id) {
        return $this->getById($this->table, $id);
    }
    public function addDiario($data) {
        $sql = "INSERT INTO diario (tipooper_id, fecha, status) VALUES (:tipooper_id, :fecha, :status) RETURNING id";
        return $this->customQuery($sql, $data, false)['id']; // Devuelve el ID del registro creado
    }
    
}
