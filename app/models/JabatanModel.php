<?php

namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\core\Database;

class JabatanModel{
    private $db;
    private $table = "jabatan";
    const TABLE_NAME = "jabatan";
    public function __construct(){
        $this->db = new Database();
    }
    public function getJabatan($column = ['*']){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            $sql = "SELECT {$column} FROM {$this->table} ORDER BY jabatan ASC;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
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