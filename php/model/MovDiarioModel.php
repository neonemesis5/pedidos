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
}
