<?php

require_once __DIR__ . '/BaseModel.php';

class MonedaModel extends BaseModel {
    private $table = 'moneda';

    /**
     * Obtiene todas las monedas.
     *
     * @return array Lista de todas las monedas.
     */
    public function getAllMonedas() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene una moneda por su ID.
     *
     * @param int $id ID de la moneda.
     * @return array|false Moneda encontrada o false si no existe.
     */
    public function getMonedaById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta una nueva moneda.
     *
     * @param array $data Datos de la moneda (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addMoneda($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza una moneda existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID de la moneda a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateMoneda($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina una moneda por su ID.
     *
     * @param int $id ID de la moneda.
     * @return bool Resultado de la operación.
     */
    public function deleteMoneda($id) {
        return $this->delete($this->table, $id);
    }
    public function getAllmonedas2()
    {
        $sql = "
            select id,concat(id,'-',nombre) as nombre from moneda where id<>?
        ";

        return $this->customQuery($sql, [1]);
    }
}
