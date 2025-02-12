<?php
namespace Ltech\WebTimbangan\config;

use Ltech\WebTimbangan\core\App;

class Config {
    private static $config = [];
    private static $loaded = false;

    private static function load($filePath = __DIR__ . '/../../.env') {
        if (!self::$loaded && file_exists($filePath)) {
            self::$config = parse_ini_file($filePath);
            self::$loaded = true;
        }
    }

    public static function get($key, $default = null) {
        self::load();
        return self::$config[$key] ?? $default;
    }
}
