<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Ltech\WebTimbangan\core\App;

class Database{
    private $connection;

    public function __construct(){
        App::loadEnv();

        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $port = getenv('DB_PORT');
        $dbs = getenv('DB_NAME');

        $dsn = "mysql:host=$host;port=$port;dbname=$dbs;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}

$database = new Database();
$conn = $database->getConnection();
# Konek ke Web Server Lokal
// $myHost = ;
// $myUser = "root";
// $myPass = "MKH&579";
// $myPort = 3306;
// $myDbs = "timbangan_db";

// try {
//     # Konek ke Web Server Lokal menggunakan PDO
//     $dsn = "mysql:host=$myHost;port=$myPort;dbname=$myDbs;charset=utf8mb4";
//     $options = [
//         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_EMULATE_PREPARES   => false,
//     ];
//     $conn = new PDO($dsn, $myUser, $myPass, $options);

//     // Set the PDO error mode to exception

// } catch (PDOException $e) {
//     echo "Failed Connection: " . $e->getMessage();
// }