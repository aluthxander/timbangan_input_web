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
        $dataJab = $this->modelJab->getJabatan(['id', 'jabatan'])['data'];
        // ambil nama field dan Null(jika NOT NULL) maka required
        $descWeb = $this->model->getRequireInTable();
        // hilangkan untuk jabatan admin
        $data['jabatan'] = array_filter($dataJab, fn($jabatan) => $jabatan['jabatan'] != 'admin');
        $data['desc'] = $descWeb;
        App::render('users.index', $data);
    }

    public function getAllUsers(){
        $users = $this->model->getUsers(['users.id', 'username', 'email', 'jabatan.jabatan', 'phone', 'name'], ['isdelete' => 0]);
        // cek akses
        $access = App::getAccesUser('users');
    
        $users['data'] = array_map(function ($user) use ($access) {
            if ($user['jabatan'] != 'admin') {
                $user['edit'] = $access['update_access'];
                $user['delete'] = $access['delete_access'];
                $user['accessby'] = $_SESSION['user']['jabatan_id'];
                return $user;
            }
        }, $users['data']);
    
        // Reset array keys agar indeks tetap berurutan
        $users['data'] = array_values(array_filter($users['data']));
    
        // ambil data yang dibutuhkan
        App::response([
            'status' => 200,
            'data' => $users['data']
        ]);
    }

    public function saveUser() {
        $dataJson = json_decode(file_get_contents('php://input'), true);
        // check field is empty or incorrect
        $errors = array();
        if(empty($dataJson['namauser'])){$errors['namauser'] = 'Name Cannot be empty';}
        // check username
        $validateUsername = $this->validateUsername($dataJson['username']);
        if ($validateUsername['status'] == false) {$errors['username'] = $validateUsername['message'];}
        // check email
        $validateEmail = $this->cleanAndValidateEmail($dataJson['email']);
        if ($validateEmail['status'] == false) {$errors['email'] = $validateEmail['message'];}
        // check phone
        if (empty(App::cleanNumString($dataJson['phone']))) {$errors['phone'] = 'Phone Cannot be empty';}
        // check password
        $validatePassword = $this->validatePassword($dataJson['password']);  
        if ($validatePassword['status'] == false) {$errors['password'] = $validatePassword['message'];}
        // check jabatan
        if (empty($dataJson['jabatan'])) {$errors['jabatan'] = 'Position Cannot be empty';}
        // if errors not empty return error
        if (!empty($errors)) {
            App::response([
                'status' => 400,
                'message' => 'Validation Error',
                'errors' => $errors
            ]);
        }

        $dataIns['name'] = htmlspecialchars($dataJson['namauser'], ENT_QUOTES, 'UTF-8');
        $dataIns['username'] = htmlspecialchars($dataJson['username'], ENT_QUOTES, 'UTF-8');
        $dataIns['email'] = $dataJson['email'];
        $dataIns['phone'] = App::cleanNumString($dataJson['phone']);
        $dataIns['password'] = password_hash($dataJson['password'], PASSWORD_BCRYPT);
        $dataIns['jabatan_id'] = App::cleanNumString($dataJson['jabatan']);
        try{
            $dataIns = $this->model->insert($dataIns);
            if (!$dataIns['status']){throw new \Exception($dataIns['message']);}
            App::response([
                'status' => 200,
                'message' => 'Save user success'
            ]);
        } catch (\Exception $e) {
            App::logger('error', $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            App::response([
                'status' => 400,
                'message' => 'Save user failed'
            ]);
        }
        
        App::response([
            'status' => 200,
            'data' =>$dataIns,
        ]);
    }

    public function verifyUserAccess($method = '') {
        // cek user password
        $user = $this->model->getUserByName($_SESSION['user']['username']);
        if (!password_verify(App::input('password'), $user['password'])) {
            App::response([
                'status' => 400,
                'message' => 'Password is incorrect'
            ]);
        }else{
            if ($method == 'permanently') {
                $this->deletePermanently();
            }else{
                $this->softDeleteUser(App::input('id'));
            }
        }
    }

    public function varifyAdminAccess(){
        $adminPass = $this->model->getUsers(['password'], ['jabatan_id' => 1])['data'][0];
        if (!password_verify(App::input('password'), $adminPass['password'])) {
            App::response([
                'status' => 400,
                'message' => 'Password is incorrect'
            ]);
        }else{
            $_SESSION['isAdmin'] = true;
            App::response([
                'status' => 200,
                'message' => 'Password is correct'
            ]);
        }
    }

    public function changePassword(){
        $id = App::input('id');
        $passwordValidation = $this->validatePassword(App::input('password'));
        $passwordConfirm = App::input('confirm_password');
        if ($passwordValidation['status']) {
            if (App::input('password') == $passwordConfirm) {
                $updatePassword = $this->model->update(['password' => password_hash(App::input('password'), PASSWORD_BCRYPT)], ['id' => $id]);
                if (!$updatePassword['status']) {
                    App::response([
                        'status' => 400,
                        'message' => $updatePassword['message']
                    ]);
                }else{
                    App::response([
                        'status' => 200,
                        'message' => 'Change password success'
                    ]);
                }
            }else{
                App::response([
                    'status' => 400,
                    'message' => 'Password not match'
                ]);
            }
        }else{
            App::response([
                'status' => 400,
                'message' => $passwordValidation['message']
            ]);
        }
    }

    public function getUser(){
        $id = $_GET['id'] ?? '';
        $user = $this->model->getUsers(['name', 'username', 'email', 'phone', 'users.jabatan_id'], ['users.id' => $id])['data'][0];
        App::response([
            'status' => 200,
            'message' => 'Get user success',
            'data' => $user
        ]);
    }

    public function updateUser(){
        $dataJson = json_decode(file_get_contents('php://input'), true);
        $errors = array();
        // check field is empty or incorrect
        $errors = array();
        if(empty($dataJson['namauser'])){$errors['namauser'] = 'Name Cannot be empty';}
        // check username
        $validateUsername = $this->validateUsername($dataJson['username'], false);
        if ($validateUsername['status'] == false) {$errors['username'] = $validateUsername['message'];}
        // check email
        $validateEmail = $this->cleanAndValidateEmail($dataJson['email'], false);
        if ($validateEmail['status'] == false) {$errors['email'] = $validateEmail['message'];}
        // check phone
        if (empty(App::cleanNumString($dataJson['phone']))) {$errors['phone'] = 'Phone Cannot be empty';}
        // check jabatan
        if (empty($dataJson['jabatan'])) {$errors['jabatan'] = 'Position Cannot be empty';}

        // if errors not empty return error
        if (!empty($errors)) {
            App::response([
                'status' => 400,
                'message' => 'Validation Error',
                'errors' => $errors
            ]);
        }

        $dataUpd['name'] = htmlspecialchars($dataJson['namauser'], ENT_QUOTES, 'UTF-8');
        $dataUpd['username'] = htmlspecialchars($dataJson['username'], ENT_QUOTES, 'UTF-8');
        $dataUpd['email'] = $dataJson['email'];
        $dataUpd['phone'] = App::cleanNumString($dataJson['phone']);
        $dataUpd['jabatan_id'] = App::cleanNumString($dataJson['jabatan']);

        $updateUser = $this->model->update($dataUpd, ['id' => App::cleanAndConvertToNumber($dataJson['id-user'])]);
        if (!$updateUser['status']) {
            App::response([
                'status' => 400,
                'message' => $updateUser['message']
            ]);
        }else{
            App::response([
                'status' => 200,
                'message' => 'Update user success'
            ]);
        }
    }

    private function softDeleteUser($id) {
        $updateUser = $this->model->update(['isdelete' => 1, 'modified'=> $_SESSION['user']['username'], 'updated_at' => date('Y-m-d H:i:s')], ['id' => $id]);
        if (!$updateUser['status']) {
            App::response([
                'status' => 400,
                'message' => $updateUser['message']
            ]);
        }else{
            App::response([
                'status' => 200,
                'message' => 'Delete user success'
            ]);
        }
    }

    private function deletePermanently() {
        $deleteUser = $this->model->delete(['isdelete' => 1]);
        if (!$deleteUser['status']) {
            App::response([
                'status' => 400,
                'message' => $deleteUser['message']
            ]);
        }else{
            App::response([
                'status' => 200,
                'message' => 'Delete user permanently success'
            ]);
        }
    }

    private function validateUsername($username, $isexist = true) {
        // check username is empty
        if (empty($username)) {return ['status' => false,'message' => 'username cannot be empty'];}
        // check username already exist
        if ($isexist) {
            $user = $this->model->getUserByName($username);
            if (!empty($user)) {return ['status' => false,'message' => 'username already exist'];}
        }

        return ['status' => true];
    }

    // Metode untuk memfilter password
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

    // Metode untuk membersihkan dan memvalidasi email
    private function cleanAndValidateEmail($email, $isexist = true) {
        // Sanitasi email untuk menghapus karakter yang tidak diinginkan
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Validasi format email
        if (filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            // check email already exist
            $user = $this->model->getUserByName($sanitizedEmail);
            if (!empty($user) && $isexist) {
                return [ 'status' => false, 'message' => 'Email already exist'];
            }else{
                return [ 'status' => true, 'message' => 'Email is valid'];
            }
        } else {
            return [ 'status' => false, 'message' => 'Invalid email format']; // Email tidak valid
        }
    }
}