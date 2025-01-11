<?php

require_once __DIR__ . '/BaseModel.php';

class TipoOperModel extends BaseModel {
    private $table = 'tipooper';

    public function getAllTipoOper() {
        return $this->getAll($this->table);
    }

    public function getTipoOperById($id) {
        return $this->getById($this->table, $id);
    }
}
