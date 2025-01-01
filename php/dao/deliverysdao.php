<?php

require_once __DIR__ . '/BaseDAO.php';

class DeliverysDAO extends BaseDAO {
    private $table = 'deliverys';

    /**
     * Obtiene todos los registros de deliverys.
     *
     * @return array Lista de registros.
     */
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        return $this->executeQuery($query);
    }

    /**
     * Obtiene un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return array|false Registro encontrado o false si no existe.
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($query, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta un nuevo registro.
     *
     * @param array $data Datos del registro.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($query, $data);
    }

    /**
     * Actualiza un registro existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del registro.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($query, $data);
    }

    /**
     * Elimina un registro por su ID.
     *
     * @param int $id ID del registro.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id]);
    }
}
