<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;

class ItemModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function countData(){
        $sql = "SELECT COUNT(*) FROM item;";
        return $this->db->getConnection()->query($sql)->fetchColumn();
    }
}
?>