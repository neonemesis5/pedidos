<?php

require_once __DIR__ . '/BaseDAO.php';

class ProductoDAO extends BaseDAO {
    private $table = 'producto';

    /**
     * Obtiene todos los productos.
     *
     * @return array Lista de todos los productos.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return array|false Producto encontrado o false si no existe.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Obtiene productos por tipo de producto.
     *
     * @param int $tipoProductoId ID del tipo de producto.
     * @return array Lista de productos filtrados por tipo.
     */
    public function getByTipoProducto($tipoProductoId) {
        $sql = "SELECT * FROM {$this->table} WHERE tipoproducto_id = :tipoproducto_id AND status = 'A'";
        return $this->executeQuery($sql, ['tipoproducto_id' => $tipoProductoId]);
    }

    /**
     * Inserta un nuevo producto.
     *
     * @param array $data Datos del producto (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID del producto a actualizar.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina un producto por su ID.
     *
     * @param int $id ID del producto.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
