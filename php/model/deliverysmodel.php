<?php

require_once __DIR__ . '/BaseModel.php';

class DeliverysModel extends BaseModel {
    private $table = 'deliverys';

    /**
     * Obtiene todos los registros de deliverys con información asociada.
     *
     * @return array Lista de registros con nombres de `location`, `pedido`, y `moneda`.
     */
    public function getAllDeliverys() {
        $query = "
            SELECT d.id, d.created_at, d.monto, d.status,
                   l.nombre AS location_nombre, 
                   p.nombre AS pedido_nombre, p.apellido AS pedido_apellido,
                   m.nombre AS moneda_nombre
            FROM deliverys d
            JOIN location l ON d.location_id = l.id
            JOIN pedido p ON d.pedido_id = p.id
            JOIN moneda m ON d.moneda_id = m.id
        ";
        return $this->customQuery($query);
    }

    /**
     * Obtiene un registro de delivery con información asociada por su ID.
     *
     * @param int $id ID del delivery.
     * @return array|false Registro encontrado o false si no existe.
     */
    public function getDeliveryById($id) {
        $query = "
            SELECT d.id, d.created_at, d.monto, d.status,
                   l.nombre AS location_nombre, 
                   p.nombre AS pedido_nombre, p.apellido AS pedido_apellido,
                   m.nombre AS moneda_nombre
            FROM deliverys d
            JOIN location l ON d.location_id = l.id
            JOIN pedido p ON d.pedido_id = p.id
            JOIN moneda m ON d.moneda_id = m.id
            WHERE d.id = :id
        ";
        return $this->customQuery($query, ['id' => $id], false);
    }

    /**
     * Inserta un nuevo registro en la tabla deliverys, validando las relaciones.
     *
     * @param array $data Datos del registro.
     * @return bool Resultado de la operación.
     */
    public function addDelivery($data) {
        // Validar existencia de las claves foráneas
        if (!$this->getById('location', $data['location_id'])) {
            throw new Exception("La ubicación con ID {$data['location_id']} no existe.");
        }
        if (!$this->getById('pedido', $data['pedido_id'])) {
            throw new Exception("El pedido con ID {$data['pedido_id']} no existe.");
        }
        if (!$this->getById('moneda', $data['moneda_id'])) {
            throw new Exception("La moneda con ID {$data['moneda_id']} no existe.");
        }

        // Validar unicidad de created_at
        $existingDelivery = $this->getWhere($this->table, ['created_at' => $data['created_at']]);
        if ($existingDelivery) {
            throw new Exception("Ya existe un registro con la fecha '{$data['created_at']}'.");
        }

        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza un registro de delivery existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del registro.
     * @return bool Resultado de la operación.
     */
    public function updateDelivery($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un registro de delivery por su ID.
     *
     * @param int $id ID del registro.
     * @return bool Resultado de la operación.
     */
    public function deleteDelivery($id) {
        return $this->delete($this->table, $id);
    }
}
