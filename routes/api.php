<?php 
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require './../vendor/autoload.php';

use Ltech\WebTimbangan\core\App;

use Ltech\WebTimbangan\controllers\LoginController;
use Ltech\WebTimbangan\controllers\UserControllers;
use Ltech\WebTimbangan\controllers\ItemController;
use Ltech\WebTimbangan\controllers\HomeController;
use Ltech\WebTimbangan\controllers\PositionController;
use Ltech\WebTimbangan\controllers\TransactionController;
use Ltech\WebTimbangan\middleware\ApiMiddleWare;

$route = isset($_GET['route']) ? $_GET['route'] : '';
// cek method yang diguanakan
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($route == 'home') {
        ApiMiddleWare::auth();
        $home = new HomeController();
        $home->getDataHome();
    } elseif ($route == 'users') {
        ApiMiddleWare::auth();
        $user = new UserControllers();
        $user->getAllUsers();
    }  elseif ($route == 'items') {
        ApiMiddleWare::auth();
        $items = new ItemController();
        $items->getAllItems();
    } elseif($route == 'transaction'){
        ApiMiddleWare::auth();
        $transaction = new TransactionController();
        $transaction->getAllTransaction();
    } elseif ($route == 'positions'){
        ApiMiddleWare::auth();
        $position = new PositionController();
        $position->getPosition();
    } elseif ($route == 'access'){
        ApiMiddleWare::auth();
        $position = new PositionController();
        $position->accesPosition();
    } else {
        http_response_code(404);
        App::response([
            'status' => 'error',
            'message' => 'Route not found'
        ]);
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!App::validateCSRFToken($token)) {
        // Token tidak valid, hentikan eksekusi
        http_response_code(403);
        echo json_encode(["error" => "Invalid CSRF token"]);
        exit;
    }

    if ($route == 'login') {
        $user = new LoginController();
        $user->login();
    } elseif ($route == 'positions') {
        ApiMiddleWare::auth();
        $position = new PositionController();
        $position->savePosition();
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Route not found'
        ]);
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!App::validateCSRFToken($token)) {
        // Token tidak valid, hentikan eksekusi
        http_response_code(403);
        echo json_encode(["error" => "Invalid CSRF token"]);
        exit;
    }
    
    if ($route == 'positions') {
        ApiMiddleWare::auth();
        $position = new PositionController();
        $position->deletePosition();
    }
}elseif ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!App::validateCSRFToken($token)) {
        // Token tidak valid, hentikan eksekusi
        http_response_code(403);
        echo json_encode(["error" => "Invalid CSRF token"]);
        exit;
    }
    
    if ($route == 'positions') {
        ApiMiddleWare::auth();
        $position = new PositionController();
        $position->updatePosition();
    }
}else{
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}

?>