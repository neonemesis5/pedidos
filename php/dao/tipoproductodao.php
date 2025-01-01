<?php

require_once __DIR__ . '/BaseDAO.php';

class TipoProductoDAO extends BaseDAO {
    private $table = 'tipo_producto';

    /**
     * Obtiene todos los tipos de producto.
     *
     * @return array Lista de todos los tipos de producto.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene un tipo de producto por su ID.
     *
     * @param int $id ID del tipo de producto.
     * @return array|false Tipo de producto encontrado o false si no existe.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta un nuevo tipo de producto.
     *
     * @param array $data Datos del tipo de producto (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza un tipo de producto existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID del tipo de producto a actualizar.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina un tipo de producto por su ID.
     *
     * @param int $id ID del tipo de producto.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
