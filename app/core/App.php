<?php

namespace Ltech\WebTimbangan\core;

class App {
    public static function loadEnv($file = '../.env') {
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
}
?>
