<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
// mmengecek session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Ltech\WebTimbangan\models\UserModel;

class UserControllers
{
    private $model;
    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index(){
        App::render('users.index');
    }

    public function getAllUsers(){
        $users = $this->model->getUsers();
        // ambil data yang dibutuhkan
        $users = array_map(fn($user) => array_intersect_key($user, array_flip(['id', 'username', 'email', 'jabatan', 'phone', 'name'])), $users);

        App::response([
            'status' => 200,
            'data' => $users
        ]);
    }


}