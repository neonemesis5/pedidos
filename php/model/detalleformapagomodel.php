<?php

require_once __DIR__ . '/BaseModel.php';

class DetalleFormaPagoModel extends BaseModel
{
    private $table = 'detalle_formap';

    public function getAllDetallesFormaPago()
    {
        $query = "
            SELECT df.id, df.pedido_id, df.formapago_id, fp.nombre AS formapago_nombre,
                   df.moneda_id, m.nombre AS moneda_nombre, df.fecha, df.monto, df.nrobauche, df.status
            FROM detalle_formap df
            JOIN formapago fp ON df.formapago_id = fp.id
            JOIN moneda m ON df.moneda_id = m.id
        ";
        return $this->customQuery($query);
    }


    /**
     * Obtiene los detalles de forma de pago para un pedido específico con información asociada.
     *
     * @param int $pedido_id ID del pedido.
     * @return array Lista de detalles de forma de pago asociados al pedido.
     */
    public function getDetallesByPedidoId($pedido_id)
    {
        $query = "
            SELECT df.id, df.pedido_id, df.formapago_id, fp.nombre AS formapago_nombre,
                   df.moneda_id, m.nombre AS moneda_nombre, df.fecha, df.monto, df.nrobauche, df.status
            FROM detalle_formap df
            JOIN formapago fp ON df.formapago_id = fp.id
            JOIN moneda m ON df.moneda_id = m.id
            WHERE df.pedido_id = :pedido_id
        ";
        return $this->customQuery($query, ['pedido_id' => $pedido_id]);
    }

    /**
     * Inserta un nuevo detalle de forma de pago validando relaciones.
     *
     * @param array $data Datos del detalle de forma de pago.
     * @return bool Resultado de la operación.
     */
    public function addDetalleFormaPago($data)
    {
        // Validar existencia de pedido, formapago y moneda
        if (!$this->getById('pedido', $data['pedido_id'])) {
            throw new Exception("El pedido con ID {$data['pedido_id']} no existe.");
        }
        if (!$this->getById('formapago', $data['formapago_id'])) {
            throw new Exception("La forma de pago con ID {$data['formapago_id']} no existe.");
        }
        if (!$this->getById('moneda', $data['moneda_id'])) {
            throw new Exception("La moneda con ID {$data['moneda_id']} no existe.");
        }

        // Insertar el detalle
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un detalle de forma de pago existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del detalle de forma de pago a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateDetalleFormaPago($data, $id)
    {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un detalle de forma de pago por su ID.
     *
     * @param int $id ID del detalle de forma de pago.
     * @return bool Resultado de la operación.
     */
    public function deleteDetalleFormaPago($id)
    {
        return $this->delete($this->table, $id);
    }
}
