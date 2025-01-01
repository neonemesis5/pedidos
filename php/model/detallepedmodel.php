<?php

require_once __DIR__ . '/BaseModel.php';

class DetallePedModel extends BaseModel {
    private $table = 'detalleped';

    /**
     * Obtiene todos los detalles de pedido.
     *
     * @return array Lista de todos los detalles de pedido.
     */
    public function getAllDetalles() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene los detalles de pedido por el ID del pedido.
     *
     * @param int $pedido_id ID del pedido.
     * @return array|false Lista de detalles de pedido o false si no existen.
     */
    public function getDetallesByPedidoId($pedido_id) {
        return $this->getWhere($this->table, ['pedido_id' => $pedido_id]);
    }

    /**
     * Inserta un nuevo detalle de pedido.
     *
     * @param array $data Datos del detalle de pedido (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addDetalle($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un detalle de pedido existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID del detalle de pedido a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateDetalle($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un detalle de pedido por su ID.
     *
     * @param int $id ID del detalle de pedido.
     * @return bool Resultado de la operación.
     */
    public function deleteDetalle($id) {
        return $this->delete($this->table, $id);
    }
}
