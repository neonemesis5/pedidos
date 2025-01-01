<?php

require_once __DIR__ . '/../db/db.php';

class BaseDAO {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function executeQuery($sql, $params = []) {
        return $this->db->getConnection()->prepare($sql)->execute($params);
    }
}
