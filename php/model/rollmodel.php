<?php

require_once __DIR__ . '/BaseModel.php';

class RolModel extends BaseModel {
    private $table = 'rol';

    /**
     * Obtiene todos los roles.
     */
    public function getAllRoles() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un rol por su ID.
     */
    public function getRolById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo rol.
     */
    public function addRol($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un rol existente.
     */
    public function updateRol($id, $data) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un rol por su ID.
     */
    public function deleteRol($id) {
        return $this->delete($this->table, $id);
    }
}
