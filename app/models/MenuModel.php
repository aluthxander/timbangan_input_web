<?php

namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class MenuModel {
    private $db;
    private $table = 'menu_web';

    public function __construct(){
        $this->db = new Database();
    }

    public function getMenu($column = ['*']) {
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            $sql = "SELECT {$column} FROM {$this->table} ORDER BY menu ASC;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getMenuBy($columns = ['*'], $where = null) {
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $columnList = implode(",", $columns);
    
            // Build the WHERE clause dynamically
            $conditions = [];
            $bindValues = [];
            if (!empty($where)) {
                foreach ($where as $key => $value) {
                    $conditions[] = "$key = :$key";
                    $bindValues[$key] = $value;
                }
                $whereClause = "WHERE " . implode(" AND ", $conditions);
            } else {
                $whereClause = "";
            }
    
            $sql = "SELECT {$columnList} FROM {$this->table} {$whereClause};";
    
            // Prepare the statement
            $stmt = $this->db->getConnection()->prepare($sql);
    
            // Bind the values
            foreach ($bindValues as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            // Execute the statement
            $stmt->execute();
    
            // Fetch the results
            return $stmt->fetchAll();
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