<?php
namespace Ltech\WebTimbangan\controllers;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\PositionAccessModel;
use Ltech\WebTimbangan\models\UserModel;

class LoginController
{

    private $model;
    private $modelAccess;
    public function __construct()
    {
        $this->model = new UserModel();
        $this->modelAccess = new PositionAccessModel();
    }
    public function index()
    {
        App::render('login.index');
    }

    public function login()
    {
        $email = App::input('email');
        $password = App::input('password');
        // hapus session kemudian generate ulang
        
        $user = $this->model->getUserByName($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // simpan session
                unset($_SESSION['csrf_token']);
                unset($user['password']);
                // ambil user acces
                $access = $this->modelAccess->getPositionAccessBy(['menu_web.id', 'jabatan', 'menu', 'menu_web.link', 'menu_web.icon', 'read_access','create_access','update_access','delete_access'], $user['jabatan_id']);
                $access = array_filter($access, fn($item) => !in_array($item['id'], [1]));
                $user['access'] = $access;
                $_SESSION['user'] = $user;
                // simpan acces user
                App::response([
                    'status' => 200,
                    'message' => 'Login berhasil',
                    'data' => [
                        'link' => '/home',
                    ]
                ]);
            } else {
                App::response([
                    'status' => 401,
                    'message' => 'Password yang anda masukan salah',
                    "errors"=> [
                        "password"=> "Password tidak cocok dengan yang terdaftar"
                    ]
                ]);
            }
        } else {
            App::response([
                "status"=>404,
                "message"=>"Email atau Username tidak ditemukan!"
            ]);
        }
    }

    public function logout(){
        session_unset();
        $_SESSION['alert'] = [
            'type' => 'success',
            'msg' => 'Logout berhasil'
        ];
        header('location: /');
    }


}