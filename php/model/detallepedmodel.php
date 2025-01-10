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
     * Inserta un nuevo detalle de pedido, validando las relaciones y actualizando el pedido.
     *
     * @param array $data Datos del detalle de pedido.
     * @return bool Resultado de la operación.
     */
    public function addDetalle($data) {
        file_put_contents("debug.log", "addDetalle--Datos recibidos: " . print_r($data, true) . "\n", FILE_APPEND);
        $query = "INSERT INTO detalleped (pedido_id, producto_id, qty, preciov, status) 
                  VALUES (:pedido_id, :producto_id, :qty, :preciov, :status)";
        try {
            $stmt = $this->db->getConnection()->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            file_put_contents("debug.log", "addDetalle--Error SQL: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Error al insertar detalle: " . $e->getMessage());
        }
    }
    
    /**
     * Actualiza el total del pedido basado en los detalles.
     *
     * @param int $pedido_id ID del pedido.
     */
    private function updatePedidoTotal($pedido_id) {
        $query = "SELECT SUM(qty * preciov) AS total FROM {$this->table} WHERE pedido_id = :pedido_id AND status = 'A'";
        $result = $this->customQuery($query, ['pedido_id' => $pedido_id], false);

        $total = $result['total'] ?? 0;
        $this->update('pedido', ['total' => $total], $pedido_id);
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

    public function getDetallePedido($idPedido){
        $query = "SELECT pro.nombre, det.preciov, det.qty, det.status 
                  FROM detalleped det 
                  JOIN producto pro ON pro.id = det.producto_id 
                  WHERE det.pedido_id = :id_Pedido";
        $result = $this->customQuery($query, ['id_Pedido' => $idPedido]);
        return $result;
    }
    
}
