<?php

namespace Ltech\WebTimbangan\middleware;
// session_start();
class loginMiddleware{
    public static function userNotLogin() {
        if (!isset($_SESSION['user'])) {
            // Jika belum login, redirect ke halaman login
            header("Location: /index.php");
            exit();
        }
    }

    public static function userHasLogin() {
        if (isset($_SESSION['user'])) {
            // Jika belum login, redirect ke halaman login
            header("Location: /index.php?page=home");
            exit();
        }
    }
}