<?php

require_once 'Database.php';

class DataSource {
    private $db;

    /**
     * Constructor: inicializa la conexión a la base de datos.
     */
    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Ejecuta una consulta SQL y devuelve los resultados.
     *
     * @param string $sql Consulta SQL.
     * @param array $params Parámetros para la consulta preparada.
     * @return array|bool Resultados de la consulta o true/false para operaciones de escritura.
     */
    public function executeQuery($sql, $params = []) {
        return $this->db->query($sql, $params);
    }

    /**
     * Obtiene la conexión a la base de datos.
     *
     * @return PDO Instancia de la conexión PDO.
     */
    public function getConnection() {
        return $this->db;
    }
}
