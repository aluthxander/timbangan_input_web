<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class SizeModel{
    private $db;
    private $table = 'size_item';
    public function __construct(){
        $this->db = new Database();
    }

    public function getSize($column = ['*'], $where = []) {
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
            
            $sql .= " ORDER BY size ASC;";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
            }
            
            $stmt->execute();
            return [
                'status'=>true, 
                'data'=>$stmt->fetchAll(),
                'sql'=>$sql,
                'message'=>'Success'
            ];
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status'=>false, 
                'code'=>$th->getCode(), 
                'message'=>$this->db->mapErrorMessage($th->getCode(), $th->getMessage()),
                'data'=>[],
                'sql'=>$sql
            ];
        }
    }
}