<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class UserModel {
    private $db;
    private $table = 'users';

    public function __construct(){
        $this->db = new Database();
    }

    public function getUserByName($username){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT username, email, `password`, `name`, phone, jabatan_id FROM {$this->table} WHERE username = :username OR email = :email;";
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
            $sql = "SELECT COUNT(*) FROM {$this->table};";
            return  $this->db->getConnection()->query($sql)->fetchColumn();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getUsers(){
        try {
            $jabatan = new JabatanModel();
            $tablename = $jabatan->getTable();

            $sql = "SELECT a.*,  b.jabatan
                    FROM {$this->table} a
                    INNER JOIN {$tablename} b ON a.jabatan_id = b.id;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function insert($data) {
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    public function update($data, $where) {
        $result = $this->db->update($this->table, $data, $where);
        return $result;
    }

    public function delete($where){
        $result = $this->db->delete($this->table, $where);
        return $result;
    }
}