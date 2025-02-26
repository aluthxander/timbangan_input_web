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

    public function getDescTabel(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "DESC {$this->table};";
            return [
                'status' => true,
                'message' => 'Success',
                'data' => $this->db->getConnection()->query($sql)->fetchAll(),
                'sql' => $sql
            ];
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
                'code' => $th->getCode()
            ];
        }
    }

    public function getRequireInTable(){
        $descUser = $this->getDescTabel()['data'];
        // ambil nama field dan Null(jika NOT NULL) maka required
        $descWeb = array();
        foreach ($descUser as &$row) {
            $descWeb[$row['Field']] = $row['Null'] == 'NO' ? 'required' : '';
        }

        return $descWeb;
    }

    public function getUserByName($username){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT username, email, `password`, `name`, phone, jabatan_id FROM {$this->table} WHERE (username = :username OR email = :email) AND isdelete = 0;";
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

    public function getUsers($column = ['*'], $where = null) {
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            $jabatan = new JabatanModel();
            $tablename = $jabatan->getTable();

            $sql = "SELECT $column
                    FROM {$this->table}
                    INNER JOIN {$tablename} ON {$this->table}.jabatan_id = {$tablename}.id";

            if (!empty($where)) {
                $sql .= " WHERE ";
                $params = [];
                foreach ($where as $key => $value) {
                    // cek apakah ada . atau tidak
                    $prmsql = $key;
                    if (strpos($key, '.') !== false) {
                        $prmsql = explode('.', $key)[1];
                    }

                    $sql .= "$key = :$prmsql AND ";
                    $params[$prmsql] = $value;
                }
                $sql = rtrim($sql, " AND ");
            }

            $stmt = $this->db->getConnection()->prepare($sql);
            if (!empty($where)) {
                foreach ($where as $key => $value) {
                    $prmsql = $key;
                    if (strpos($key, '.') !== false) {
                        $prmsql = explode('.', $key)[1];
                    }
                    $stmt->bindValue(":$prmsql", $value);
                }
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            return [
                'status' => true,
                'message' => 'Success',
                'data' => $stmt->fetchAll(), // Mengembalikan jumlah baris yang terupdate
                'sql' => $sql
            ];
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status' => false,
                'code' => $th->getCode(),
                'message' => $th->getMessage(),
                'data' => [],
                'sql' => $sql
            ];
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