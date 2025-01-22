<?php

require_once __DIR__ . '/BaseModel.php';

class TasaModel extends BaseModel {
    private $table = 'tasa';

    /**
     * Obtiene todas las tasas.
     *
     * @return array Lista de todas las tasas.
     */
    public function getAllTasas() {
        return $this->getAll($this->table);
    }

    public function getCurrentRates(){
        return $this->customQuery("SELECT  ID,concat((SELECT NOMBRE FROM MONEDA WHERE ID=moneda_id1),'_', (SELECT NOMBRE FROM MONEDA WHERE ID=moneda_id2)) as nombre,monto  FROM tasa WHERE status = ?",array('A'));
    }
    public function getCurrentMonedasTasas(){
        return $this->customQuery('select * from monedastasa',array());
    }

    /**
     * Obtiene una tasa por su ID.
     *
     * @param int $id ID de la tasa.
     * @return array|false Tasa encontrada o false si no existe.
     */
    public function getTasaById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta una nueva tasa.
     *
     * @param array $data Datos de la tasa (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addTasa($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza una tasa existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID de la tasa a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateTasa($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina una tasa por su ID.
     *
     * @param int $id ID de la tasa.
     * @return bool Resultado de la operación.
     */
    public function deleteTasa($id) {
        return $this->delete($this->table, $id);
    }

     /**
     * Inserta una nueva tasa basada en otra existente.
     *
     * @param int $id ID de la tasa base.
     * @param float $monto Nuevo monto para la tasa.
     * @return bool Resultado de la operación.
     */
    public function insertTasa($id, $monto) {
        $res = $this->getTasaById($id);
        
        if (!$res) {
            return false; // Retorna falso si la tasa base no existe
        }
        $this->update($this->table,['status'=>'D'],$id);
        $data = [
            'moneda_id1' => $res['moneda_id1'],
            'moneda_id2' => $res['moneda_id2'],
            'fecha'      => date('Y-m-d'), // Último día del mes en formato TIMESTAMP
            'monto'      => $monto,
            'status'     => 'A'
        ];

        return $this->insert($this->table, $data);
    }
}
