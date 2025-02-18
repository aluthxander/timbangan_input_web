<?php
namespace Ltech\WebTimbangan\controllers;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\UserModel;

class LoginController
{

    private $model;
    public function __construct()
    {
        $this->model = new UserModel();
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
        unset($_SESSION['csrf_token']);
        
        $user = $this->model->getUserByName($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // simpan session
                $_SESSION['user'] = $user;

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