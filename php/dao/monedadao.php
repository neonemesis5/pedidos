<?php

require_once __DIR__ . '/BaseDAO.php';

class MonedaDAO extends BaseDAO {
    private $table = 'moneda';

    /**
     * Obtiene todas las monedas.
     *
     * @return array Lista de todas las monedas.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene una moneda por su ID.
     *
     * @param int $id ID de la moneda.
     * @return array|false Moneda encontrada o false si no existe.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta una nueva moneda.
     *
     * @param array $data Datos de la moneda.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza una moneda existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID de la moneda.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina una moneda por su ID.
     *
     * @param int $id ID de la moneda.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
