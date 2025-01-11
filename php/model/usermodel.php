<?php

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {
    private $table = 'usuarios';

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username AND status = 'A'";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT); // Hash de la contraseña
        return $this->insert($this->table, $data);
    }
}
