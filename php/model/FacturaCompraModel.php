<?php

require_once __DIR__ . '/BaseModel.php';

class FacturaCompraModel extends BaseModel
{
    private $table = 'factura_compra';

    /**
     * Obtiene todas las facturas de compra.
     *
     * @return array Lista de todas las facturas.
     */
    public function getAllFacturasCompra($status = null, $fecha = null)
    {
        $params[] =!is_null($status)? $status:'A';
        if(!is_null($fecha))
            $params[] = $fecha;
    
        $q = "SELECT fac.id, pro.nombre as nomprov, mon.nombre as nommoneda ,fac.nrofactura, fac.fecha, fac.total,fac.status
               FROM factura_compra fac
               JOIN moneda mon ON mon.id = fac.moneda_id
               JOIN proveedor pro ON pro.id = fac.proveedor_id
               WHERE fac.status=? ".($fecha!==null?' and fac.fecha::date >= ?':'');
        return $this->customQuery($q, $params);
    }

    /**
     * Obtiene una factura de compra por su ID.
     *
     * @param int $id ID de la factura.
     * @return array|false Factura encontrada o false si no existe.
     */
    public function getFacturaCompraById($id)
    {
        return $this->getById($this->table, $id);
    }

    /**
     * Inserta una nueva factura de compra.
     *
     * @param array $data Datos de la factura (clave => valor).
     * @return int ID de la factura creada.
     */
    public function addFacturaCompra($data)
    {
        // return $this->insert($this->table, $data);
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
     * Actualiza una factura de compra existente.
     *
     * @param array $data Datos actualizados (clave => valor).
     * @param int $id ID de la factura a actualizar.
     * @return bool Resultado de la operación.
     */
    public function updateFacturaCompra($data, $id)
    {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Elimina una factura de compra por su ID.
     *
     * @param int $id ID de la factura.
     * @return bool Resultado de la operación.
     */
    public function deleteFacturaCompra($id)
    {
        return $this->delete($this->table, $id);
    }
}
