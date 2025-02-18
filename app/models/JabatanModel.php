<?php

namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class JabatanModel{
    private $db;
    public function __construct(){
        $this->db = new Database();
    }
    public function getJabatan($column = ['*']){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            $sql = "SELECT {$column} FROM jabatan ORDER BY jabatan ASC;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }
}