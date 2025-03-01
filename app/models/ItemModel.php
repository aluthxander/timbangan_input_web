<?php
namespace Ltech\WebTimbangan\models;

use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class ItemModel {
    private $db;
    private $table = 'item';

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

    public function getItems($column = ['*'], $where = []){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
            
            // Buat query SELECT dengan column yang dipilih
            $sql = "SELECT ".implode(", ", $column)." FROM {$this->table}";
            
            // Jika ada kondisi WHERE, tambahkan ke query
            if (!empty($where)) {
                $conditions = [];
                $bindValues = [];
                foreach ($where as $key => $value) {
                    $conditions[] = "$key = :$key";
                    $bindValues[$key] = $value;
                }
                $sql .= " WHERE ".implode(" AND ", $conditions);
            }
            
            // Tambahkan ORDER BY
            $sql .= " ORDER BY created_at ASC, `code` ASC;";
            
            // Prepare query
            $stmt = $this->db->getConnection()->prepare($sql);
            
            // Bind nilai-nilai untuk kondisi WHERE
            if (!empty($bindValues)) {
                foreach ($bindValues as $key => $value) {
                    $stmt->bindValue(":$key", $value);
                }
            }
            
            // Execute query
            $stmt->execute();
            
            // Fetch hasil query
            return [
                'status'=>true,
                'sql'=>$sql,
                'message'=>'Get data success',
                'data'=>$stmt->fetchAll()
            ];
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status'=>false,
                'message'=>$this->db->mapErrorMessage($th->getCode(), $th->getMessage()),
                'data'=>[],
                'code'=>$th->getCode(),
                'sql'=>$sql
            ];
        }
    }
    public function getDescTabel(){
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

    public function getRequireInTable(){
        $descUser = $this->getDescTabel();
        // ambil nama field dan Null(jika NOT NULL) maka required
        $descWeb = array();
        foreach ($descUser as &$row) {
            $descWeb[$row['Field']] = $row['Null'] == 'NO' ? 'required' : '';
        }

        return $descWeb;
    }

    public function getItemByWeight($column = ['*'], $weight = 0){
        try{
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }

            $columns = implode(", ", $column);
            $sql = "SELECT {$columns} FROM {$this->table} 
                    WHERE weight_min <= :weight_min AND weight_max >= :weight_max
                    ORDER BY ABS(weight_max - :weight_max_abs) ASC, ABS(weight_min - :weight_min_abs) ASC;";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(":weight_min", $weight);
            $stmt->bindParam(":weight_max", $weight);
            $stmt->bindParam(":weight_max_abs", $weight);
            $stmt->bindParam(":weight_min_abs", $weight);
            $stmt->execute();
            return [
                'status'=>true,
                'sql'=>$sql,
                'message'=>'Get data success',
                'data'=>$stmt->fetchAll()
            ];
        }catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file'=>$th->getFile(), 'line'=>$th->getLine()]);
            return [
                'status'=>false,
                'message'=>$this->db->mapErrorMessage($th->getCode(), $th->getMessage()),
                'data'=>[],
                'code'=>$th->getCode(),
                'sql'=>$sql
            ];
        }
    }

    public function insert($data){
        $result = $this->db->insert($this->table, $data);
        return $result;
    }

    public function update($data, $where = []){
        $result = $this->db->update($this->table, $data, $where);
        return $result;
    }

    public function delete($where){
        $result = $this->db->delete($this->table, $where);
        return $result;
    }
}
?>