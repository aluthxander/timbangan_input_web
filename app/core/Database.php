<?php
namespace Ltech\WebTimbangan\core;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\config\Config;
use PDO;
use PDOException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Database{
    private $connection;
    private $logger;

    public function __construct(){
        App::loadEnv();

        // Setup Monolog
        $this->logger = new Logger('database');
        $logFile = __DIR__ . "/../logs/db_error.log";
        $this->logger->pushHandler(new StreamHandler($logFile, Logger::ERROR));

        $host = getenv('DB_HOST') ?? '127.0.0.1';
        $user = getenv('DB_USER') ?? 'root';
        $pass = getenv('DB_PASS');
        $port = getenv('DB_PORT') ?? '3306';
        $dbs = getenv('DB_NAME');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbs;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->connection = new PDO($dsn, $user, $pass, $options);
        // try {
        // } catch (PDOException $e) {
        //     $this->handleError("Database Connection Failed: " . $e->getMessage());
        //     $this->connection = null;
        // }
    }

    public function getConnection(){
        return $this->connection;
    }

    private function handleError($message) {
        if (Config::getEnv('APP_ENV') == 'production') {
            $this->logger->error($message); // Simpan error ke log dengan Monolog
        } else {
            $this->logger->error($message); // Simpan error ke log dengan Monolog
            die("<b>Database Error:</b> $message");
        }
    }

    // Begin Transaction
    public function beginTransaction() {
        $this->connection->beginTransaction();
    }

    // Commit Transaction
    public function commit() {
        $this->connection->commit();
    }

    // Rollback Transaction
    public function rollBack() {
        $this->connection->rollBack();
    }
    // insert data
    public function insert( $table, $data) {
        $sql = '';
        try {
            // Menyiapkan kolom dan nilai yang akan dimasukkan
            $columns = implode(", ", array_keys($data));  // Menyusun nama kolom
            $values = ":" . implode(", :", array_keys($data));  // Menyusun placeholder untuk nilai

            // Query SQL untuk melakukan insert
            $sql = "INSERT INTO {$table} ($columns) VALUES ($values)";

            // Persiapkan query
            $stmt = $this->getConnection()->prepare($sql);

            // Binding data ke placeholder
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            // Eksekusi query
            if ($stmt->execute()) {
                return [
                    'status'=>true, 
                    'id'=>$this->getConnection()->lastInsertId(), 
                    'message'=>'Success',
                    'sql'=>$sql
                ];  // Mengembalikan ID terakhir yang diinsert
            } else {
                return [
                    'status'=>false, 
                    'code'=>$stmt->errorCode(), 
                    'message'=>$this->mapErrorMessage($stmt->errorCode(), $stmt->errorInfo()), 
                    'sql'=>$sql
                ];  // Mengembalikan false jika gagal
            }

        } catch (PDOException $e) {
            // Menangani error jika terjadi kesalahan pada eksekusi query
            App::logger('error', $e->getMessage(), ['sql' => $sql, 'file' => $e->getFile(), 'line' => $e->getLine()]);
            return [
                'status'=>false, 
                'message'=>$this->mapErrorMessage($e->getCode(), $e->getMessage()), 
                'code'=>$e->getCode(), 
                'sql'=>$sql
            ];
        }
    }

    /**
     * Pemetaan kode error SQL ke pesan yang lebih mudah dipahami
     */
    public function mapErrorMessage($errorCode, $defaultMessage = '') {
        $errorMessages = [
            '23000' => 'Data yang dimasukkan sudah ada atau melanggar aturan unik.',
            '22001' => 'Data yang dimasukkan terlalu panjang untuk salah satu kolom.',
            '42S22' => 'Kolom yang dimasukkan tidak ditemukan dalam tabel.',
            'HY000' => 'Kesalahan umum dalam query SQL.',
            '42000' => 'Syntax error dalam query SQL.',
            '28000' => 'Kredensial database tidak valid atau izin tidak cukup.',
            'HY001' => 'Memori tidak cukup untuk menyimpan data.'
        ];

        return $errorMessages[$errorCode] ?? "Terjadi kesalahan: " . $defaultMessage;
    }

    // update data
    public function update($table, $data, $where = []) {
        try {
            // Pastikan data tidak kosong
            if (empty($data)) {
                return [
                    'status' => false,
                    'message' => 'Data yang akan diupdate tidak boleh kosong'
                ];
            }
    
            // Menyusun bagian SET dari query dengan placeholder
            $setPart = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
    
            // Jika $where tidak kosong, susun bagian WHERE, jika kosong update semua data
            $wherePart = empty($where) ? "" : " WHERE " . implode(" AND ", array_map(fn($key) => "$key = :where_$key", array_keys($where)));
    
            // Membuat query update
            $sql = "UPDATE {$table} SET $setPart$wherePart";
    
            // Persiapkan query
            $stmt = $this->getConnection()->prepare($sql);
    
            // Binding nilai untuk kolom yang diupdate
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
    
            // Binding nilai untuk kondisi WHERE (jika ada)
            if (!empty($where)) {
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":where_$key", $value);
                }
            }
    
            // Eksekusi query
            if ($stmt->execute()) {
                return [
                    'status' => true,
                    'message' => 'Success',
                    'affected_rows' => $stmt->rowCount(), // Mengembalikan jumlah baris yang terupdate
                    'sql' => $sql
                ];
            } else {
                return [
                    'status' => false,
                    'code' => $stmt->errorCode(),
                    'message' => $this->mapErrorMessage($stmt->errorCode(), $stmt->errorInfo()),
                    'sql' => $sql
                ];
            }
    
        } catch (PDOException $e) {
            // Menangani error jika terjadi kesalahan pada eksekusi query
            App::logger('error', $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return [
                'status' => false,
                'message' => $this->mapErrorMessage($e->getCode(), $e->getMessage()),
                'code' => $e->getCode(),
                'sql' => $sql
            ];
        }
    }

    // delete data
    public function delete($table, $where = []) {
        try {
            // Jika $where tidak kosong, susun bagian WHERE, jika kosong delete semua data
            $wherePart = empty($where) ? "" : " WHERE " . implode(" AND ", array_map(fn($key) => "$key = :where_$key", array_keys($where)));
            
            // Query SQL untuk melakukan delete
            $sql = "DELETE FROM {$table}{$wherePart}";

            // Persiapkan query
            $stmt = $this->getConnection()->prepare($sql);

            // Binding data ke placeholder
            if (!empty($where)) {
                foreach ($where as $key => $value) {
                    $stmt->bindValue(":where_$key", $value);
                }
            }

            // Eksekusi query
            if ($stmt->execute()) {
                return [
                    'status'=>true, 
                    'message'=>'Success',
                    'sql'=>$sql
                ];  
            } else {
                return [
                    'status'=>false, 
                    'code'=>$stmt->errorCode(), 
                    'message'=>$this->mapErrorMessage($stmt->errorCode(), $stmt->errorInfo()), 
                    'sql'=>$sql
                ];  
            }

        } catch (PDOException $e) {
            // Menangani error jika terjadi kesalahan pada eksekusi query
            App::logger('error', $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            return [
                'status'=>false, 
                'message'=>$this->mapErrorMessage($e->getCode(), $e->getMessage()), 
                'code'=>$e->getCode(), 
                'sql'=>$sql
            ];
        }
    }

    // select data dengan join
}