<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class UserModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getUserByName($username){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT * FROM users WHERE username = :username OR email = :email;";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $username]);
            return $stmt->fetch();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return $th->getMessage();
        }
    }

    public function countData(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT COUNT(*) FROM users;";
            return  $this->db->getConnection()->query($sql)->fetchColumn();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getUsers(){
        try {
            $sql = "SELECT a.*,  b.jabatan
                    FROM users a
                    INNER JOIN jabatan b ON a.jabatan_id = b.id;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }
}