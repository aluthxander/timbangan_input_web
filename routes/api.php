<?php 
error_reporting(E_ALL);

require './../vendor/autoload.php';

use Ltech\WebTimbangan\controllers\LoginController;
use Ltech\WebTimbangan\controllers\UserControllers;
use Ltech\WebTimbangan\controllers\ItemController;
use Ltech\WebTimbangan\controllers\HomeController;
use Ltech\WebTimbangan\controllers\PositionController;
use Ltech\WebTimbangan\controllers\TransactionController;
use Ltech\WebTimbangan\middleware\ApiMiddleWare;

$route = isset($_GET['route']) ? $_GET['route'] : '';

if ($route == 'login') {
    $user = new LoginController();
    $user->login();
} elseif ($route == 'home') {
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
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found'
    ]);
}
?>