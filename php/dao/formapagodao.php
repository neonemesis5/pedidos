<?php

require_once __DIR__ . '/BaseDAO.php';

class FormaPagoDAO extends BaseDAO {
    private $table = 'formapago';

    /**
     * Obtiene todas las formas de pago.
     *
     * @return array Lista de todas las formas de pago.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene una forma de pago por su ID.
     *
     * @param int $id ID de la forma de pago.
     * @return array|false Forma de pago encontrada o false si no existe.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta una nueva forma de pago.
     *
     * @param array $data Datos de la forma de pago.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza una forma de pago existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID de la forma de pago.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina una forma de pago por su ID.
     *
     * @param int $id ID de la forma de pago.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
