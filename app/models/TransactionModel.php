<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class TransactionModel {
    private $db;
    private $table = 'transaction';

    public function __construct(){
        $this->db = new Database();
    }

    public function countData(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT COUNT(*) FROM {$this->table};";
            return $this->db->getConnection()->query($sql)->fetchColumn();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getTransactionToday(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT * FROM {$this->table} WHERE DATE(created_at) = CURDATE();";
            return $this->db->getConnection()->query($sql)->fetchAll();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getTransaction($column = ['*'], $where = [])
    {
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            
            $sql = "SELECT {$column} FROM {$this->table}";
            
            if (!empty($where)) {
                $conditions = [];
                $params = [];
                foreach ($where as $key => $value) {
                    $conditions[] = "$key = :$key";
                    $params[$key] = $value;
                }
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $sql .= " ORDER BY created_at DESC;";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
            }
            
            $stmt->execute();
            return [
                'status'=>true, 
                'code'=>$stmt->errorCode(), 
                'data'=>$stmt->fetchAll(),
                'sql'=>$sql,
                'message'=>'Success'
            ];
    
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status'=>false, 
                'code'=>$th->getCode(), 
                'data'=>[],
                'sql'=>$sql,
                'message'=>$th->getMessage()
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
?>