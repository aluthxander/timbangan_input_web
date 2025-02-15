<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;

class TransactionModel {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function countData(){
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            $sql = "SELECT COUNT(*) FROM `transaction`;";
            return $this->db->getConnection()->query($sql)->fetchColumn();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [];
        }
    }

    public function getTransactionToday(){
        $sql = "SELECT * FROM `transaction` WHERE DATE(created_at) = CURDATE();";
        return $this->db->getConnection()->query($sql)->fetchAll();
    }

    public function getTransaction(){
        $sql = "SELECT * FROM `transaction` ORDER BY created_at DESC;";
        return $this->db->getConnection()->query($sql)->fetchAll();
    }
}
?>