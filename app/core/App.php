<?php

namespace Ltech\WebTimbangan\core;
use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class App {
    private static $logger;
    public static function loadEnv() {
        $file = dirname(__DIR__, 2) . '/.env';

        if (!file_exists($file)) {
            throw new Exception('.env file not found');
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Abaikan komentar
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }

    public static function logger($type = 'info', $msg = '', $data = []) {
        if (!self::$logger) {
            $logName = 'error_web';
            $logFilePath = realpath(__DIR__) . '/../../logs/web.log';
            $logDir = dirname($logFilePath);

            // Pastikan folder log ada
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Buat instance logger hanya sekali
            self::$logger = new Logger($logName);
            $streamHandler = new StreamHandler($logFilePath, Logger::DEBUG);
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] [%level_name%] %message% %context% %extra%\n", 'Y-m-d H:i:s'));
            self::$logger->pushHandler($streamHandler);
        }

        // Tentukan level log
        $logLevels = [
            'info'    => Logger::INFO,
            'error'   => Logger::ERROR,
            'warning' => Logger::WARNING,
            'debug'   => Logger::DEBUG
        ];

        $level = $logLevels[$type] ?? Logger::INFO; // Default ke INFO jika tipe tidak dikenali

        self::$logger->log($level, $msg, $data);
    }

    // Metode untuk menghasilkan token CSRF dan menyimpannya di dalam session
    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Buat token CSRF dengan panjang 32 byte
        }
    
        return $_SESSION['csrf_token'];
    }
    
    // Metode untuk memvalidasi token CSRF
    public static function validateCSRFToken($token) {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            return true;
        }else{
            return false;
        }
    }
    
    public static function cekAksesUser($menu=[]) {
        $cek_user = false;
        foreach ($menu as $index => $allowed_user) {
            if ($_SESSION['user']['jabatan'] == $allowed_user) {
                $cek_user = true;
                break;
            }
        }
        if (!isset($_SESSION['user']) || !$cek_user) {
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Anda tidak diperbolehkan mengakses halaman ini!'
            ];
            header('location: index.php');
            exit;
        }
    }
    
    // Metode untuk membersihkan nilai string dari karakter selain angka
    public static function cleanNumString($string) {
        return preg_replace('/[^0-9]/', '', $string);
    }
    
        // Metode untuk membersihkan nilai string dari karakter selain angka dan huruf
    public static function cleanAlphanumeric($string) {
        // Hapus semua karakter kecuali angka dan huruf menggunakan regex
        return preg_replace('/[^a-zA-Z0-9]/', '', $string);
    }
    
    // Metode untuk membersihkan dan memvalidasi email
    public static function cleanAndValidateEmail($email) {
        // Sanitasi email untuk menghapus karakter yang tidak diinginkan
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Validasi format email
        if (filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            return $sanitizedEmail;
        } else {
            return false; // Email tidak valid
        }
    }
    
    public static function cleanAndConvertToNumber($inputString, $type = 'int') {
        // Hapus karakter selain angka dan titik (untuk float)
        $filteredString = preg_replace("/[^0-9.]/", "", $inputString);

        // Konversi ke float jika mengandung titik desimal, jika tidak konversi ke integer
        if ($type === 'float') {
            $number = floatval($filteredString);
        } else {
            $number = intval($filteredString);
        }
        return $number;
    }
    
    /**
     * Metode untuk mengkonversi rupiah ke float
     */
    public static function rupiahToFloat($data) : float {
        $data = str_replace(['.', 'Rp.', ' '], '', $data);
        $data = str_replace(',', '.', $data);
        return self::cleanAndConvertToNumber($data, 'float');
    }
    
    /**
     * metode untuk mengkonversi nilai ribuan ke float
     * @param string $data
     * @return float
     */
    public static function ribuanToFloat($data) : float {
        $data = str_replace('.', '', $data);
        $data = str_replace(',', '.', $data);
        return self::cleanAndConvertToNumber($data, 'float');
    }
    
    // Metode untuk memfilter password
    public static function filterPassword($password) {
        if (strlen($password) < 8) {
            return false;
        }

        $containsUppercase = preg_match('/[A-Z]/', $password);
        $containsLowercase = preg_match('/[a-z]/', $password);
        $containsNumber = preg_match('/[0-9]/', $password);
        $containsSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

        return $containsUppercase && $containsLowercase && $containsNumber && $containsSpecialChar;
    }
    
    /**
     * Metode untuk membersihkan dan memfilter kode
     */
    public static function cleanAndFilterKode($data) : string {
        $data = str_replace([' ', '"', "'"], '', $data);
        $data = strtoupper($data);
        return $data;
    }
    
    public static function convertDateFormatIdtoEn($dateString) {
        // Ubah format tanggal menggunakan fungsi date dan strtotime
        $newDateString = date('Y-m-d', strtotime($dateString));
        return $newDateString;
    }
    
    public static function convertFloat($data){
        $data = str_replace('.', '', $data);
        $data = str_replace(',', '.', $data);
        $data = floatval($data);
        return $data;
    }
    
    public static function convertDateFormatEntoId($dateString) {
        // Ubah format tanggal menggunakan fungsi date dan strtotime
        $timestamp = strtotime($dateString);
        $newDateString = date('d-F-Y', $timestamp);
        return $newDateString;
    }
    
    public static function convertDateFormatEntoId2($dateString) {
        // Ubah format tanggal menggunakan fungsi date dan strtotime
        $timestamp = strtotime($dateString);
        $newDateString = date('d-m-Y', $timestamp);
        return $newDateString;
    }

    public static function number_format_decimal($num_str, $decimals = 2) {
        return number_format($num_str, $decimals, ',', '.');
    }

    public static function render(string $view, $data=[]): void {
        $model['data'] = $data;
        $model['path'] = self::getPath();
        $view = str_replace('.', '/', $view);
        require __DIR__ . "/../../pages/{$view}.php";
    }

    public static function input($key)
    {
        return $_POST[$key] ?? json_decode(file_get_contents('php://input'), true)[$key] ?? null;
    }

    public static function response($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function getPath(): string {
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $path = explode('/', $path)[1] ?? '/';
        return $path;
    }
}
?>
