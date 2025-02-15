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

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            $this->handleError("Database Connection Failed: " . $e->getMessage());
            $this->connection = null;
        }
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
}