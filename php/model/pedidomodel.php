<?php

require_once __DIR__ . '/BaseModel.php';

class PedidoModel extends BaseModel {
    private $table = 'pedido';

    /**
     * Obtiene todos los pedidos.
     *
     * @return array Lista de todos los pedidos.
     */
    public function getAllPedidos() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un pedido por su ID.
     *
     * @param int $id ID del pedido.
     * @return array|false Pedido encontrado o false si no existe.
     */
    public function getPedidoById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta un nuevo pedido.
     *
     * @param array $data Datos del pedido (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addPedido($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un pedido existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID del pedido a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updatePedido($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un pedido por su ID.
     *
     * @param int $id ID del pedido.
     * @return bool Resultado de la operación.
     */
    public function deletePedido($id) {
        return $this->delete($this->table, $id);
    }
}
