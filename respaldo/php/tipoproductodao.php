<?php

require_once 'db.php';
require_once 'tipoproductomodel.php';

class TipoProductoDAO {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $sql = "SELECT * FROM tipo_producto";
        return $this->db->query($sql);
    }
}
