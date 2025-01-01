<?php

require_once __DIR__ . '/../model/PedidoModel.php';
require_once __DIR__ . '/BaseController.php';

class PedidoController extends BaseController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new PedidoModel();
    }

    /**
     * Obtiene todos los pedidos.
     */
    public function getAllPedidos() {
        try {
            $pedidos = $this->pedidoModel->getAllPedidos();
            $this->jsonResponse($pedidos);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un pedido por su ID.
     *
     * @param int $id ID del pedido.
     */
    public function getPedidoById($id) {
        if (!$id) {
            $this->errorResponse("El ID del pedido es requerido.", 400);
        }

        try {
            $pedido = $this->pedidoModel->getPedidoById($id);
            if ($pedido) {
                $this->jsonResponse($pedido);
            } else {
                $this->errorResponse("Pedido no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Inserta un nuevo pedido.
     */
    public function addPedido($data) {
        try {
            $result = $this->pedidoModel->addPedido($data);
            if ($result) {
                $this->jsonResponse("Pedido creado exitosamente.", 201);
            } else {
                $this->errorResponse("Error al crear el pedido.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un pedido existente.
     */
    public function updatePedido($id, $data) {
        try {
            $result = $this->pedidoModel->updatePedido($data, $id);
            if ($result) {
                $this->jsonResponse("Pedido actualizado exitosamente.");
            } else {
                $this->errorResponse("Error al actualizar el pedido.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina un pedido por su ID.
     */
    public function deletePedido($id) {
        try {
            $result = $this->pedidoModel->deletePedido($id);
            if ($result) {
                $this->jsonResponse("Pedido eliminado exitosamente.");
            } else {
                $this->errorResponse("Error al eliminar el pedido.", 500);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
