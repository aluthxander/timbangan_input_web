<?php
namespace Ltech\WebTimbangan\middleware;

class AuthMiddleware implements Middleware {
    
    function before(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }
    }
}