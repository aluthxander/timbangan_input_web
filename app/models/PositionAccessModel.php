<?php
namespace Ltech\WebTimbangan\models;
use Ltech\WebTimbangan\core\Database;
use Ltech\WebTimbangan\core\App;

class PositionAccessModel{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function getPositionAccessBy($columns = ['*'], $position_id = null, $menu_id = null) {
        try {
            if (empty($this->db->getConnection())) {
                throw new \Exception('Database connection is null');
            }
    
            // Menggabungkan kolom
            if (empty($columns)) {
                $columns = ['*']; // Default jika kosong
            }
            $columnList = implode(",", $columns);
    
            // Menyusun klausa WHERE secara dinamis
            $whereClauses = [];
            $params = [];
    
            if (!empty($position_id)) {
                $whereClauses[] = "position_access.position_id = :position_id";
                $params[':position_id'] = $position_id;
            }
    
            if (!empty($menu_id)) {
                $whereClauses[] = "position_access.menu_id = :menu_id";
                $params[':menu_id'] = $menu_id;
            }
    
            // Menggabungkan WHERE jika ada kondisi
            $where = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";
    
            // Query SQL
            $sql = "SELECT {$columnList} 
                    FROM position_access
                    INNER JOIN menu_web ON menu_web.id = position_access.menu_id
                    {$where}
                    ORDER BY menu_web.menu ASC;";
            // Prepared statement untuk keamanan
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }
            $stmt->execute();
    
            return $stmt->fetchAll();
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file' => $th->getFile(), 'line' => $th->getLine()]);
            return [];
        }
    }
}