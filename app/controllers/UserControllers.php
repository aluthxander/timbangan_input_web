<?php
namespace Ltech\WebTimbangan\controllers;
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

    public function login()
    {
        $email = $this->input('email');
        $password = $this->input('password');
        
        $user = $this->model->getUserByName($email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                // simpan session
                $_SESSION['user'] = $user;

                $this->response([
                    'status' => 200,
                    'message' => 'Login berhasil',
                    'data' => [
                        'link' => 'index.php?page=home',
                    ]
                ]);
            } else {
                $this->response([
                    'status' => 401,
                    'message' => 'Password yang anda masukan salah',
                    "errors"=> [
                        "password"=> "Password tidak cocok dengan yang terdaftar"
                    ]
                ]);
            }
        } else {
            $this->response([
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
        header('location: index.php');
    }

    public function getAllUsers(){
        $users = $this->model->getUsers();
        // ambil data yang dibutuhkan
        $users = array_map(fn($user) => array_intersect_key($user, array_flip(['id', 'username', 'email', 'jabatan', 'phone', 'name'])), $users);

        $this->response([
            'status' => 200,
            'data' => $users
        ]);
    }

    private function input($key)
    {
        return $_POST[$key] ?? json_decode(file_get_contents('php://input'), true)[$key] ?? null;
    }

    private function response($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}