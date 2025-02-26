<?php

namespace Ltech\WebTimbangan\middleware;

class AdminMiddleWare implements Middleware {
    public function before():void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user']) && $_SESSION['user']['jabatan_id'] !== 1) {
            // user bukan admin
            // user bukan admin
            http_response_code(401);
            exit(json_encode(['status' => 401, 'message' => 'Unauthorized']));
        }
    }
} 