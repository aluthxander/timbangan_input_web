<?php 
error_reporting(E_ALL);

require './../vendor/autoload.php';
use Ltech\WebTimbangan\controllers\UserControllers;

$route = isset($_GET['route']) ? $_GET['route'] : '';

if ($route == 'login') {
    $user = new UserControllers();
    $user->login();
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found'
    ]);
}
?>