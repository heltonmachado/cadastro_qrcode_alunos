<?php

class UserModel {
    public static function getAllUsers() {
        $db = getConnection();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
