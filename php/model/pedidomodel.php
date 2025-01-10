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
    // public function addPedido($data) {
    //     return $this->insert($this->table, $data);
    // }
    public function addPedido($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
    
        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $result = $stmt->execute($data);
    
            if ($result) {
                $lastInsertId = $this->db->getConnection()->lastInsertId();
    
                // Log del ID insertado
                file_put_contents("debug.log", "addPedido - ID insertado: $lastInsertId\n", FILE_APPEND);
    
                return $lastInsertId;
            }
    
            return false;
        } catch (PDOException $e) {
            file_put_contents("debug.log", "addPedido - Error SQL: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Error al insertar el pedido: " . $e->getMessage());
        }
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
    public function getLastPedido(){
        return $this->customQuery('select id from pedido order by id desc limit ?',[1]);
    }
    public function getTotalPedido($id){
        return $this->customQuery('select total from pedido where id=?',[$id]);
    }
}
