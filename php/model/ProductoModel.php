<?php

require_once __DIR__ . '/BaseModel.php';

class ProductoModel extends BaseModel {
    private $table = 'producto';

    /**
     * Obtiene todos los productos.
     *
     * @return array Lista de productos.
     */
    public function getAllProductos() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return array|false Producto encontrado o false si no existe.
     */
    public function getProductoById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo producto.
     *
     * @param array $data Datos del producto (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addProducto($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param array $data Datos del producto a actualizar (clave => valor).
     * @param int $id ID del producto a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateProducto($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return bool Resultado de la operación.
     */
    public function deleteProducto($id) {
        return $this->delete($this->table, $id);
    }

    /**
     * Obtiene productos por tipo de producto.
     *
     * @param int $tipoProductoId ID del tipo de producto.
     * @return array Lista de productos filtrados.
     */
    public function getProductosByTipoProducto($tipoProductoId) {
        $sql = "SELECT * FROM {$this->table} WHERE tipoproducto_id = :tipoproducto_id AND status = 'A'";
        return $this->db->query($sql, ['tipoproducto_id' => $tipoProductoId]);
    }
}
