<?php

require_once __DIR__ . '/BaseDAO.php';

class TasaDAO extends BaseDAO {
    private $table = 'tasa';

    /**
     * Obtiene todas las tasas.
     *
     * @return array Lista de todas las tasas.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }
    public function getCurrentRates(){
        $sql = " SELECT moneda_id1, moneda_id2, monto FROM  {$this->table} WHERE status = 'A'";
        $res=$this->executeQuery($sql);
        echo '<pre>';
        print_r($res);
        echo '</pre>';
        die;
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene una tasa por su ID.
     *
     * @param int $id ID de la tasa.
     * @return array|false Tasa encontrada o false si no existe.
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $result = $this->executeQuery($sql, ['id' => $id]);
        return $result ? $result[0] : false;
    }

    /**
     * Inserta una nueva tasa.
     *
     * @param array $data Datos de la tasa.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza una tasa existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID de la tasa.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina una tasa por su ID.
     *
     * @param int $id ID de la tasa.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
