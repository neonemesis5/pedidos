<?php

require_once __DIR__ . '/BaseModel.php';

class DetalleCompraModel extends BaseModel
{
    private $table = 'detallecompra';

    /**
     * Obtiene todos los detalles de compra.
     *
     * @return array Lista de todos los detalles de compra.
     */
    public function getAllDetallesCompra()
    {
        return $this->getAll($this->table);
    }

    /**
     * Obtiene un detalle de compra por su ID.
     *
     * @param int $id ID del detalle de compra.
     * @return array|false Detalle de compra encontrado o false si no existe.
     */
    public function getDetalleCompraById($id)
    {
        return $this->getById($this->table, $id);
    }

    /**
     * Obtiene los detalles de compra relacionados con una factura.
     *
     * @param int $facturaCompraId ID de la factura de compra.
     * @return array Lista de detalles relacionados con la factura.
     */
    public function getDetallesByFacturaCompra($facturaCompraId)
    {
        $query = "
            SELECT 
                dc.id, 
                p.nombre AS producto_nombre, 
                dc.qty, 
                dc.precioc, 
                (dc.qty * dc.precioc) AS total 
            FROM detallecompra dc
            JOIN producto p ON dc.producto_id = p.id
            WHERE dc.factura_compra_id = ?
        ";
        return $this->customQuery($query, [$facturaCompraId]);
    }

    /**
     * Inserta un nuevo detalle de compra.
     *
     * @param array $data Datos del detalle de compra.
     * @return int ID del detalle de compra creado.
     */
    public function addDetalleCompra($data)
    {
        file_put_contents("debug.log", "addDetalle--Datos recibidos: " . print_r($data, true) . "\n", FILE_APPEND);
        $query = "INSERT INTO detallecompra (producto_id, factcompra_id, qty, precioc, status) 
        VALUES ( :producto_id, :factcompra_id, :qty, :precioc, :status)";
        try {
            $stmt = $this->db->getConnection()->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            file_put_contents("debug.log", "addDetalle--Error SQL: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Error al insertar detalle: " . $e->getMessage());
        }
    }

    /**
     * Actualiza un detalle de compra existente.
     *
     * @param array $data Datos a actualizar.
     * @param int $id ID del detalle de compra.
     * @return bool Resultado de la operación.
     */
    public function updateDetalleCompra($data, $id)
    {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina un detalle de compra por su ID.
     *
     * @param int $id ID del detalle de compra.
     * @return bool Resultado de la operación.
     */
    public function deleteDetalleCompra($id)
    {
        return $this->delete($this->table, $id);
    }
    // Validar si una factura de compra existe
    public function validateFacturaCompra($facturaCompraId)
    {
        $result = $this->customQuery("SELECT 1 FROM factura_compra WHERE id = ?", [$facturaCompraId]);
        return !empty($result);
    }

    // Validar si un producto existe
    public function validateProducto($productoId)
    {
        $result = $this->customQuery("SELECT 1 FROM producto WHERE id = ?", [$productoId]);
        return !empty($result);
    }
}
