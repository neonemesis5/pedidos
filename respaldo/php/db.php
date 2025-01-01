<?php

class Database {
    private $host = 'localhost';       // Dirección del servidor
    private $port = '5432';           // Puerto de PostgreSQL
    private $dbname = 'dbpizzas'; // Nombre de la base de datos
    private $user = 'postgres';     // Usuario de la base de datos
    private $password = '123'; // Contraseña del usuario
    private $pdo = null;

    /**
     * Constructor para inicializar la conexión
     */
    public function __construct() {
        $this->connect();
    }

    /**
     * Establece la conexión con la base de datos PostgreSQL
     */
    private function connect() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Realiza una consulta SQL
     * 
     * @param string $sql La consulta SQL
     * @param array $params Parámetros para la consulta preparada
     * @return array|bool Resultados de la consulta o true/false para operaciones de escritura
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            // Verificar si la consulta es SELECT o no
            if (preg_match('/^SELECT/i', $sql)) {
                return $stmt->fetchAll();
            }
            return true; // Para INSERT, UPDATE, DELETE
        } catch (PDOException $e) {
            die("Error en la consulta SQL: " . $e->getMessage());
        }
    }

    /**
     * Cierra la conexión
     */
    public function close() {
        $this->pdo = null;
    }
}
