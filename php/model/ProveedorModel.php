<?php

require_once __DIR__ . '/BaseModel.php';

class ProveedorModel extends BaseModel {
    private $table = 'proveedor';

    /**
     * Obtiene todos los proveedores.
     *
     * @return array Lista de todos los proveedores.
     */
    public function getAllProveedores() {
        return $this->getAll($this->table);
    }

    public function getAllProveedoresID()
    {
        $sql = "
           select id,concat(id,'-',nombre) as nombre from proveedor where status =?
        ";

        return $this->customQuery($sql, ['A']);
    }

    /**
     * Obtiene un proveedor por su ID.
     *
     * @param int $id ID del proveedor.
     * @return array|false Proveedor encontrado o false si no existe.
     */
    public function getProveedorById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo proveedor.
     *
     * @param array $data Datos del proveedor (clave => valor).
     * @return int ID del proveedor creado.
     */
    public function addProveedor($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un proveedor existente.
     *
     * @param array $data Datos actualizados (clave => valor).
     * @param int $id ID del proveedor a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateProveedor($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un proveedor por su ID.
     *
     * @param int $id ID del proveedor.
     * @return bool Resultado de la operación.
     */
    public function deleteProveedor($id) {
        return $this->delete($this->table, $id);
    }
}
