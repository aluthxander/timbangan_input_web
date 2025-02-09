<?php
namespace Ltech\WebTimbangan\core;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
class BasicMethod
{
    // Method untuk inisialisasi logger
    public static function Logger(){
        $logName = 'error_app';
        $logFilePath = './logs/app.log';
        $logDir = dirname($logFilePath);
        
        // Periksa apakah folder ada, jika tidak buat folder
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Buat logger baru
        $log = new Logger($logName);

        // Handler untuk semua log (tingkat DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL dan ALERT)
        $streamHandler = new StreamHandler($logFilePath, Logger::DEBUG);
        $streamHandler->setFormatter(new LineFormatter("[%datetime%] [%level_name%] %message% %context% %extra%\n", 'Y-m-d H:i:s'));
        $log->pushHandler($streamHandler);
        
        return $log;
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


}