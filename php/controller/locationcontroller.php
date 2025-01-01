<?php

require_once __DIR__ . '/../model/LocationModel.php';
require_once __DIR__ . '/BaseController.php';

class LocationController extends BaseController {
    private $locationModel;

    public function __construct() {
        $this->locationModel = new LocationModel();
    }

    /**
     * Obtiene todas las ubicaciones con `preciov > 0`.
     */
    public function getAllLocations() {
        try {
            $locations = $this->locationModel->getAllLocations();
            $this->jsonResponse($locations);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene una ubicación por su ID.
     *
     * @param int $id ID de la ubicación.
     */
    public function getLocationById($id) {
        if (!$id) {
            $this->errorResponse("El ID de la ubicación es requerido.", 400);
        }

        try {
            $location = $this->locationModel->getLocationById($id);
            if ($location) {
                $this->jsonResponse($location);
            } else {
                $this->errorResponse("Ubicación no encontrada.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
