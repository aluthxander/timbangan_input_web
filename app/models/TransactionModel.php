<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;

class TransactionModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function countData(){
        $sql = "SELECT COUNT(*) FROM `transaction`;";
        return $this->db->getConnection()->query($sql)->fetchColumn();
    }

    public function getTransactionToday(){
        $sql = "SELECT * FROM `transaction` WHERE DATE(created_at) = CURDATE();";
        return $this->db->getConnection()->query($sql)->fetchAll();
    }
}
?>