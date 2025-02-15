<?php
namespace Ltech\WebTimbangan\middleware;
use Ltech\WebTimbangan\config\Config;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class ApiMiddleWare {
    public static function auth() {
        $config = new Config();
        if ($config->getEnv('APP_ENV') == 'production') {
            if (!isset($_SESSION['user'])) {
                // Jika belum login, redirect ke halaman login
                http_response_code(401);
                exit('{ "status": 401, "message": "Unauthorized" }');
            }
        }
    }
}