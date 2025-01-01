<?php

class Database {
    private static $instance = null;
    private $pdo;

    private $host = 'localhost';
    private $port = '5432';
    private $dbname = 'dbpizzas';
    private $user = 'postgres';
    private $password = '123';

    /**
     * Constructor privado para evitar la creación directa de instancias.
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Establece la conexión con la base de datos PostgreSQL.
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
     * Devuelve la instancia única de la clase Database.
     *
     * @return Database Instancia única de Database.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Devuelve la conexión PDO.
     *
     * @return PDO Instancia de PDO.
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Ejecuta una consulta SQL.
     *
     * @param string $sql Consulta SQL.
     * @param array $params Parámetros opcionales.
     * @return array|bool Resultados de la consulta o true/false para operaciones de escritura.
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            if (preg_match('/^SELECT/i', $sql)) {
                return $stmt->fetchAll();
            }
            return true;
        } catch (PDOException $e) {
            die("Error en la consulta SQL: " . $e->getMessage());
        }
    }
}
