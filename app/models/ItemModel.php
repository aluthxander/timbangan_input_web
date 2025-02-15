<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class ItemModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    /**
     * method menghitung jumlah data item keseluruhan
     */
    public function countData(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT COUNT(*) FROM item;";
            
            return $this->db->getConnection()->query($sql)->fetchColumn();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getItems(){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT `name`, `code`, `id`, `size`, `style`, `weight_max`, `weight_min` FROM item ORDER BY created_at ASC, `code` ASC;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function structureItems(){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "DESC item;";
            return $this->db->getConnection()->query($sql)->fetchAll();
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }
}
?>