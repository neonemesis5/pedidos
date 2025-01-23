<?php

require_once __DIR__ . '/BaseModel.php';

class UsuarioModel extends BaseModel {
    private $table = 'usuarios';

    /**
     * Obtiene todos los usuarios.
     */
    public function getAllUsuarios() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un usuario por su ID.
     */
    public function getUsuarioById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo usuario.
     */
    public function addUsuario($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un usuario existente.
     */
    public function updateUsuario($id, $data) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un usuario por su ID.
     */
    public function deleteUsuario($id) {
        return $this->delete($this->table, $id);
    }

    /**
     * Obtiene un usuario por su `username`.
     */
    public function getUsuarioByUsername($username) {
        return $this->getWhere($this->table, ['username' => $username], false);
    }
    public function getUsuariosRol(){
        return $this->customQuery('select * from usuariosrol',[]);
    }
}
