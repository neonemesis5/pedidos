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
}
