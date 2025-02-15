<?php 
session_start();
require __DIR__ . '/../vendor/autoload.php';
use Ltech\WebTimbangan\core\Router;
use Ltech\WebTimbangan\controllers\LoginController;
use Ltech\WebTimbangan\controllers\HomeController;
use Ltech\WebTimbangan\controllers\UserControllers;
use Ltech\WebTimbangan\controllers\ItemController;
use Ltech\WebTimbangan\controllers\TransactionController;
use Ltech\WebTimbangan\middleware\AuthMiddleware;
use Ltech\WebTimbangan\middleware\GuestMiddleware;


Router::add('GET', '/', LoginController::class, 'index', [GuestMiddleware::class]);
Router::add('GET', '/logout', LoginController::class, 'logout');
Router::add('GET', '/home', HomeController::class, 'index', [AuthMiddleware::class]);
Router::add('GET', '/users', UserControllers::class, 'index', [AuthMiddleware::class]);
Router::add('GET', '/items', ItemController::class, 'index', [AuthMiddleware::class]);
Router::add('GET', '/transactions', TransactionController::class, 'index', [AuthMiddleware::class]);

Router::run();
?>