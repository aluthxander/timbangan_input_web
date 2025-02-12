<?php 
session_start();
require __DIR__ . '/../vendor/autoload.php';
use Ltech\WebTimbangan\middleware\loginMiddleware;
use Ltech\WebTimbangan\controllers\UserControllers;

$page = isset($_GET['page']) ? $_GET['page'] : '';
if ($page == 'home') {
    loginMiddleware::userNotLogin();
    require "./pages/home/index.php";
}elseif($page == 'users'){
    loginMiddleware::userNotLogin();
    require "./pages/users/index.php";
}elseif($page == 'logout'){
    $user = new UserControllers();
    $user->logout();
}else{
    loginMiddleware::userHasLogin();
    require "./pages/login/index.php";
}
?>