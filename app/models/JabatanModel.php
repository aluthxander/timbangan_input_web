<?php

namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class JabatanModel{
    private $db;
    protected $table = "jabatan";
    public function __construct(){
        $this->db = new Database();
    }

    public function getTable(){
        return $this->table;
    }
    public function getJabatan($column = ['*'], $where = []){
        try{
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
            
            $sql .= " ORDER BY jabatan ASC;";
            $stmt = $this->db->getConnection()->prepare($sql);
            if (!empty($params)) {
                $stmt->execute($params);
            } else {
                $stmt->execute();
            }
            
            return [
                'status'=>true, 
                'code'=>$stmt->errorCode(), 
                'data'=>$stmt->fetchAll(),
                'message'=>"Get data jabatan success", 
                'sql'=>$sql
            ];  
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status'=>false, 
                'code'=>$th->getCode(), 
                'data'=>[],
                'message'=>$th->getMessage(), 
                'sql'=>$sql
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