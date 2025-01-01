<?php

require_once __DIR__ . '/BaseModel.php';

class DetalleFormaPagoModel extends BaseModel {
    private $table = 'detalle_formap';

    /**
     * Obtiene todos los detalles de forma de pago.
     *
     * @return array Lista de todos los detalles de forma de pago.
     */
    public function getAllDetallesFormaPago() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene detalles de forma de pago por pedido ID.
     *
     * @param int $pedido_id ID del pedido.
     * @return array Lista de detalles de forma de pago para el pedido.
     */
    public function getDetallesByPedidoId($pedido_id) {
        return $this->getWhere($this->table, ['pedido_id' => $pedido_id]);
    }

    /**
     * Inserta un nuevo detalle de forma de pago.
     *
     * @param array $data Datos del detalle de forma de pago.
     * @return bool Resultado de la operación.
     */
    public function addDetalleFormaPago($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un detalle de forma de pago existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del detalle de forma de pago a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateDetalleFormaPago($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un detalle de forma de pago por su ID.
     *
     * @param int $id ID del detalle de forma de pago.
     * @return bool Resultado de la operación.
     */
    public function deleteDetalleFormaPago($id) {
        return $this->delete($this->table, $id);
    }
}
