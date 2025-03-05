<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\UserModel;

class SecurityController{
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function index(){
        App::render('setting.security.index');
    }

    public function changePassword(){
        // ambil input
        $prevPass = App::input('old-password');
        $newPass = App::input('new-password');
        $confirmPass = App::input('confirm-password');

        // validasi password lama
        $user = $this->model->getUserByName($_SESSION['user']['username']);
        if (!password_verify($prevPass, $user['password'])) {
            App::response([
                'status' => 400,
                'message' => 'Password is incorrect'
            ]);
        }else{
            // validasi password baru
            $passwordValidation = $this->validatePassword($newPass);
            if ($passwordValidation['status']) {
                // validasi password konfirmasi
                if ($newPass == $confirmPass) {
                    $updatePassword = $this->model->update(['password' => password_hash($newPass, PASSWORD_BCRYPT)], ['id' => $user['id']]);
                    if (!$updatePassword['status']) {
                        App::response([
                            'status' => 400,
                            'message' => $updatePassword['message']
                        ]);
                    }else{
                        App::response([
                            'status' => 200,
                            'message' => 'Password has been changed'
                        ]);
                    }
                }else{
                    App::response([
                        'status' => 400,
                        'message' => 'Password confirmation is incorrect'
                    ]);
                }
            }else{
                App::response([
                    'status' => 400,
                    'message' => $passwordValidation['message']
                ]);
            }
        }
    }

    private function validatePassword($password) {
        if (strlen($password) < 8) {
            return ['status'=> false, 'message' => 'Password must be at least 8 characters'];
        }

        if (!preg_match('/[A-Z]/', $password) ) {
            return ['status'=> false, 'message' => 'Password must contain at least one uppercase letter'];
        }
        
        if (!preg_match('/[a-z]/', $password) ) {
            return ['status'=> false, 'message' => 'Password must contain at least one lowercase letter'];
        }
        
        if (!preg_match('/[0-9]/', $password) ) {
            return ['status'=> false, 'message' => 'Password must contain at least one number'];
        }
        
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) ) {
            return ['status'=> false, 'message' => 'Password must contain at least one special character'];
        }

        return ['status'=> true, 'message' => 'Password is valid'];
    }

}