<?php

require_once __DIR__ . '/../model/DetalleCompraModel.php';
require_once __DIR__ . '/../model/FacturaCompraModel.php';
require_once __DIR__ . '/../model/ProductoModel.php';
require_once __DIR__ . '/BaseController.php';

class DetalleCompraController extends BaseController
{
    private $detalleCompraModel;
    private $facturaCompraModel;
    private $productoModel;

    public function __construct()
    {
        $this->detalleCompraModel = new DetalleCompraModel();
        $this->facturaCompraModel = new FacturaCompraModel();
        $this->productoModel = new ProductoModel();
    }

    /**
     * Obtiene todos los registros de detalle de compra.
     */
    public function getAllDetallesCompra()
    {
        try {
            $detallesCompra = $this->detalleCompraModel->getAllDetallesCompra();
            $this->jsonResponse($detallesCompra);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene un detalle de compra por su ID.
     *
     * @param int $id ID del detalle de compra.
     */
    public function getDetalleCompraById($id)
    {
        if (!$id) {
            $this->errorResponse("El ID del detalle de compra es requerido.", 400);
        }

        try {
            $detalleCompra = $this->detalleCompraModel->getDetalleCompraById($id);
            if ($detalleCompra) {
                $this->jsonResponse($detalleCompra);
            } else {
                $this->errorResponse("Detalle de compra no encontrado.", 404);
            }
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Obtiene los detalles de compra relacionados con una factura.
     *
     * @param int $facturaCompraId ID de la factura de compra.
     */
    public function getDetallesByFacturaCompra($facturaCompraId,$standar=null)
    {
        // print_r($facturaCompraId);
        if (!$facturaCompraId) {
            $this->errorResponse("El ID de la factura de compra es requerido.", 400);
        }
        try {
            $detalles = $this->detalleCompraModel->getDetallesByFacturaCompra($facturaCompraId);
            if($standar!=null)
                return $detalles;
            return $this->jsonResponse($detalles);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Inserta un nuevo detalle de compra.
     *
     * @param array $data Datos del detalle de compra.
     */
    public function addDetalleCompra($data)
    {

        // Validar existencia de factura de compra y producto
        $facturaExist = $this->facturaCompraModel->getFacturaCompraById($data['factcompra_id']);
       
        $productoExist = $this->productoModel->getProductoById($data['producto_id']);
       
        if (!$facturaExist) {
            $this->errorResponse("La factura de compra no existe.", 400);
        }
        if (!$productoExist) {
            $this->errorResponse("El producto no existe.", 400);
        }

        // Validar datos del detalle de compra
        if (empty($data['qty']) || $data['qty'] <= 0) {
            $this->errorResponse("La cantidad debe ser un número positivo.", 400);
        }
        if (empty($data['precioc']) || $data['precioc'] <= 0) {
            $this->errorResponse("El precio debe ser un número positivo.", 400);
        }
        if (!in_array($data['status'], ['A', 'I'])) {  // Supongo que 'A' es activo e 'I' es inactivo
            $this->errorResponse("El estado debe ser 'A' o 'I'.", 400);
        }
       
        try {
            // Insertar detalle de compra
            // $result = $this->detalleCompraModel->addDetalleCompra($data);
            // $this->jsonResponse(['message' => 'Detalle de compra creado exitosamente', 'id' => $result]);
            return $this->detalleCompraModel->addDetalleCompra($data);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Actualiza un detalle de compra existente.
     *
     * @param int $id ID del detalle de compra.
     * @param array $data Datos actualizados.
     */
    public function updateDetalleCompra($id, $data)
    {
        if (!$id) {
            $this->errorResponse("El ID del detalle de compra es requerido.", 400);
        }

        try {
            $this->detalleCompraModel->updateDetalleCompra($data, $id);
            $this->jsonResponse(['message' => 'Detalle de compra actualizado exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Elimina un detalle de compra por su ID.
     *
     * @param int $id ID del detalle de compra.
     */
    public function deleteDetalleCompra($id)
    {
        if (!$id) {
            $this->errorResponse("El ID del detalle de compra es requerido.", 400);
        }

        try {
            $this->detalleCompraModel->deleteDetalleCompra($id);
            $this->jsonResponse(['message' => 'Detalle de compra eliminado exitosamente']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
