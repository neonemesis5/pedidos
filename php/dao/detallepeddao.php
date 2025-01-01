<?php

require_once __DIR__ . '/BaseDAO.php';

class DetallePedDAO extends BaseDAO {
    private $table = 'detalleped';

    /**
     * Obtiene todos los detalles de pedido.
     *
     * @return array Lista de todos los detalles de pedido.
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return $this->executeQuery($sql);
    }

    /**
     * Obtiene los detalles de pedido por el ID del pedido.
     *
     * @param int $pedido_id ID del pedido.
     * @return array Lista de detalles de pedido.
     */
    public function getByPedidoId($pedido_id) {
        $sql = "SELECT * FROM {$this->table} WHERE pedido_id = :pedido_id";
        return $this->executeQuery($sql, ['pedido_id' => $pedido_id]);
    }

    /**
     * Inserta un nuevo detalle de pedido.
     *
     * @param array $data Datos del detalle de pedido.
     * @return bool Resultado de la operación.
     */
    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->executeQuery($sql, $data);
    }

    /**
     * Actualiza un detalle de pedido existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del detalle de pedido.
     * @return bool Resultado de la operación.
     */
    public function update($data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE id = :id";
        $data['id'] = $id;
        return $this->executeQuery($sql, $data);
    }

    /**
     * Elimina un detalle de pedido por su ID.
     *
     * @param int $id ID del detalle de pedido.
     * @return bool Resultado de la operación.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->executeQuery($sql, ['id' => $id]);
    }
}
