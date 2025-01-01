<?php

require_once __DIR__ . '/BaseModel.php';

class FormaPagoModel extends BaseModel {
    private $table = 'formapago';

    /**
     * Obtiene todas las formas de pago.
     *
     * @return array Lista de todas las formas de pago.
     */
    public function getAllFormasPago() {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene una forma de pago por su ID.
     *
     * @param int $id ID de la forma de pago.
     * @return array|false Forma de pago encontrada o false si no existe.
     */
    public function getFormaPagoById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta una nueva forma de pago.
     *
     * @param array $data Datos de la forma de pago (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function addFormaPago($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza una forma de pago existente.
     *
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID de la forma de pago a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateFormaPago($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina una forma de pago por su ID.
     *
     * @param int $id ID de la forma de pago.
     * @return bool Resultado de la operación.
     */
    public function deleteFormaPago($id) {
        return $this->delete($this->table, $id);
    }
}
