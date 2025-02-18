<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\JabatanModel;

// mmengecek session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Ltech\WebTimbangan\models\UserModel;

class UserControllers
{
    private $model;
    private $modelJab;
    public function __construct()
    {
        $this->model = new UserModel();
        $this->modelJab = new JabatanModel();
    }

    public function index(){
        $dataJab = $this->modelJab->getJabatan();
        // ambil data yang dibutuhkan
        $dataJab = array_map(fn($dataJab) => array_intersect_key($dataJab, array_flip(['id', 'jabatan'])), $dataJab);

        App::render('users.index', $dataJab);
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