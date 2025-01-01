<?php

require_once __DIR__ . '/../db/db.php';

class BaseModel {
    protected $db;

    /**
     * Constructor: inicializa la conexión a la base de datos utilizando el patrón Singleton.
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Obtiene todos los registros de una tabla.
     *
     * @param string $table Nombre de la tabla.
     * @return array Lista de registros.
     */
    public function getAll($table) {
        $sql = "SELECT * FROM $table";
        return $this->db->getConnection()->query($sql)->fetchAll();
    }

    /**
     * Obtiene un registro por su ID.
     *
     * @param string $table Nombre de la tabla.
     * @param int $id ID del registro.
     * @return array|false Registro encontrado o false si no existe.
     */
    public function getById($table, $id) {
        $sql = "SELECT * FROM $table WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Inserta un registro en una tabla.
     *
     * @param string $table Nombre de la tabla.
     * @param array $data Datos a insertar (clave => valor).
     * @return bool Resultado de la operación.
     */
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Actualiza un registro en una tabla.
     *
     * @param string $table Nombre de la tabla.
     * @param array $data Datos a actualizar (clave => valor).
     * @param int $id ID del registro a actualizar.
     * @return bool Resultado de la operación.
     */
    public function update($table, $data, $id) {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $setClause WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Elimina un registro de una tabla.
     *
     * @param string $table Nombre de la tabla.
     * @param int $id ID del registro a eliminar.
     * @return bool Resultado de la operación.
     */
    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
