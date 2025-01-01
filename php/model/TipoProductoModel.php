<?php

require_once __DIR__ . '/BaseModel.php';

class TipoProductoModel extends BaseModel {
    private $table = 'tipo_producto';

    /**
     * Obtiene todos los tipos de producto.
     *
     * @return array Lista de tipos de producto.
     */
    public function getAllTiposProductos() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un tipo de producto por su ID.
     *
     * @param int $id ID del tipo de producto.
     * @return array|false Tipo de producto encontrado o false si no existe.
     */
    public function getTipoProductoById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo tipo de producto.
     *
     * @param array $data Datos del tipo de producto (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addTipoProducto($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un tipo de producto existente.
     *
     * @param array $data Datos del tipo de producto a actualizar (clave => valor).
     * @param int $id ID del tipo de producto a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateTipoProducto($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un tipo de producto por su ID.
     *
     * @param int $id ID del tipo de producto.
     * @return bool Resultado de la operación.
     */
    public function deleteTipoProducto($id) {
        return $this->delete($this->table, $id);
    }
}
