<?php

namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class MenuModel {
    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function getMenu($column = ['*']) {
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $column = implode(",", $column);
            $sql = "SELECT {$column} FROM menu_web ORDER BY menu ASC;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }
}