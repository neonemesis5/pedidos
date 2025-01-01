<?php

require_once __DIR__ . '/BaseModel.php';

class LocationModel extends BaseModel {
    private $table = 'location';

    /**
     * Obtiene todas las ubicaciones con `preciov > 0`.
     *
     * @return array Lista de ubicaciones.
     */
    public function getAllLocations() {
        $query = "SELECT id, nombre, preciov FROM {$this->table} WHERE preciov > 0";
        return $this->customQuery($query);
    }

    /**
     * Obtiene una ubicación por su ID.
     *
     * @param int $id ID de la ubicación.
     * @return array|false Ubicación encontrada o false si no existe.
     */
    public function getLocationById($id) {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta una nueva ubicación.
     *
     * @param array $data Datos de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function addLocation($data) {
        return $this->insert($this->table, $data);
    }

    /**
     * Actualiza una ubicación existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function updateLocation($data, $id) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina una ubicación por su ID.
     *
     * @param int $id ID de la ubicación.
     * @return bool Resultado de la operación.
     */
    public function deleteLocation($id) {
        return $this->delete($this->table, $id);
    }
}
