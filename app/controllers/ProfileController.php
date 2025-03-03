<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\UserModel;

class ProfileController{
    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function index(){
        // ambil data user
        $username = $_SESSION['user']['username'];
        $user = $this->model->getUsers(['name', 'username', 'email', 'phone'], ['username' => $username]);
        $data = [];
        if ($user['status']) {
            $data['user'] = $user['data'][0];
        }else{
            $data['user'] = [];
        }

        $descWeb = $this->model->getRequireInTable();
        $data['desc'] = $descWeb;
        App::render('setting.profile.index', $data);
    }

    public function updateProfile(){
        $dataJson = json_decode(file_get_contents('php://input'), true);
        $usenamrOld = $dataJson['username-old'] ?? '';

        // get id user by old username
        $condition = ['username' => $usenamrOld];
        $id = $this->model->getUsers(['users.id'], $condition);
        if ($id['status']) {
            $id = $id['data'][0]['id'];
        } else {
            $id = 0;
        }

        $errors = array();
        // check field is empty or incorrect
        if (empty($dataJson['name'])) {$errors['name'] = 'Name cannot be empty';}
        $checkUsername = $this->validateUsername($dataJson['username'], $id);
        if (!$checkUsername['status']) {$errors['username'] = $checkUsername['message'];}
        $checkEmail = $this->cleanAndValidateEmail($dataJson['email'], $id);
        if (!$checkEmail['status']) {$errors['email'] = $checkEmail['message'];}
        if (empty($dataJson['phone'])) {$errors['phone'] = 'Phone cannot be empty';}

        // if errors not empty return error
        if (!empty($errors)) {
            App::response([
                'status' => 400,
                'message' => 'Validation Error',
                'errors' => $errors
            ]);
        }
        
        // update profile
        $dataUpd = [
            'name' => $dataJson['name'],
            'username' => $dataJson['username'],
            'email' => $dataJson['email'],
            'phone' => $dataJson['phone']
        ];
        $updUser = $this->model->update($dataUpd, ['id' => $id]);
        if ($updUser['status']) {
            // update session
            $_SESSION['user']['username'] = $dataJson['username'];
            $_SESSION['user']['name'] = $dataJson['name'];
            $_SESSION['user']['email'] = $dataJson['email'];
            $_SESSION['user']['phone'] = $dataJson['phone'];

            App::response([
                'status' => 200,
                'message' => 'Update profile success',
                'username' => $dataJson['username'],
            ]);
        } else {
            App::response([
                'status' => 400,
                'message' => $updUser['message']
            ]);
        }
    }

    private function cleanAndValidateEmail($email, $id) {
        // Sanitasi email untuk menghapus karakter yang tidak diinginkan
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        // Validasi format email
        if (filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            // check email already exist
            $user = $this->model->getUserExcept(['email' => $sanitizedEmail], ['id' => $id]);
            if (!empty($user['data'])) {
                return [ 'status' => false, 'message' => 'Email already exist'];
            }else{
                return [ 'status' => true, 'message' => 'Email is valid'];
            }
        } else {
            return [ 'status' => false, 'message' => 'Invalid email format']; // Email tidak valid
        }
    }

    private function validateUsername($username, $id) {
        // check username is empty
        if (empty($username)) {return ['status' => false,'message' => 'username cannot be empty'];}
        // check username already exist
        $user = $this->model->getUserExcept(['username' => $username], ['id' => $id]);
        if (!empty($user['data'])) {return ['status' => false,'message' => 'username already exist'];}

        return ['status' => true];
    }
}