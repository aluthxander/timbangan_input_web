<?php
namespace Ltech\WebTimbangan\config;

use Ltech\WebTimbangan\core\App;

class Config {
    private static $config = [];
    private static $loaded = false;

    private static function loadEnv($filePath = __DIR__ . '/../../.env') {
        if (!self::$loaded && file_exists($filePath)) {
            self::$config = parse_ini_file($filePath);
            self::$loaded = true;
        }
    }

    public static function getEnv($key, $default = null) {
        self::loadEnv();
        return self::$config[$key] ?? $default;
    }
}
