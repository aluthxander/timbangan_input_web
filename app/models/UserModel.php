<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;

class UserModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getUserByName($username){
        $sql = "SELECT * FROM users WHERE username = :username OR email = :email;";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute(['username' => $username, 'email' => $username]);
        return $stmt->fetch();
    }

    public function countData(){
        $sql = "SELECT COUNT(*) FROM users;";
        return  $this->db->getConnection()->query($sql)->fetchColumn();
    }

    public function getUsers(){
        $sql = "SELECT * FROM users;";
        return $this->db->getConnection()->query($sql)->fetchAll();
    }
}