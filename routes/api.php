<?php 
error_reporting(E_ALL);

require './../vendor/autoload.php';
use Ltech\WebTimbangan\controllers\UserControllers;
use Ltech\WebTimbangan\controllers\HomeController;
use Ltech\WebTimbangan\middleware\ApiMiddleWare;

$route = isset($_GET['route']) ? $_GET['route'] : '';

if ($route == 'login') {
    $user = new UserControllers();
    $user->login();
} elseif ($route == 'home') {
    ApiMiddleWare::auth();
    $home = new HomeController();
    $home->index();
} elseif ($route == 'users') {
    ApiMiddleWare::auth();
    $user = new UserControllers();
    $user->getAllUsers();
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found'
    ]);
}
?>