<?php

require_once __DIR__ . '/BaseDAO.php';

class LocationDAO extends BaseDAO {
    private $table = 'location';

    /**
     * Obtiene todas las ubicaciones con `preciov > 0`.
     *
     * @return array Lista de ubicaciones.
     */
    public function getAll() {
        $query = "SELECT id, nombre, preciov FROM {$this->table} WHERE preciov > 0";
        return $this->executeQuery($query);
    }

    /**
     * Obtiene una ubicación por su ID.
     *
     * @param int $id ID de la ubicación.
     * @return array|false Ubicación encontrada o false si no existe.
     */
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($query, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta una nueva ubicación.
     *
     * @param array $data Datos de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($query, $data);
    }

    /**
     * Actualiza una ubicación existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($query, $data);
    }

    /**
     * Elimina una ubicación por su ID.
     *
     * @param int $id ID de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($query, ['id' => $id]);
    }
}
