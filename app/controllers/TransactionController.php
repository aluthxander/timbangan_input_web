<?php

namespace Ltech\WebTimbangan\controllers;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\config\Config;
use Ltech\WebTimbangan\models\ItemModel;
use Ltech\WebTimbangan\models\TransactionModel;

class TransactionController
{
    private $model;

    public function __construct()
    {
        $this->model = new TransactionModel();
    }
    
    public function index()
    {
        App::render('transaction.index');
    }

    public function getAllTransaction()
    {
        $result = $this->model->getTransaction(['code_item', 'name_item', 'size_item', 'style_item', 'weight_item', 'id']);
        if (!isset($result['data'])) {
            App::response([
                'status' => 500,
                'message' => 'Error fetching transaction data',
            ]);
            return;
        }
        $data = $result['data'];
    
        // cek akses
        $access = App::getAccesUser('transactions');
        
        $data = array_map(function ($transaction) use ($access) {
            $transaction['delete'] = $access['delete_access'];
            $transaction['accessby'] = $_SESSION['user']['jabatan_id'];
            return $transaction;
        }, $data);
    
        App::response([
            'status' => 200,
            'message' => 'Get all transaction success',
            'data' => $data
        ]);
    }

    public function itemCheckByWeight(){
        $weight = $_GET['weight'] ?? 0;
        $itemModel = new ItemModel();
        $items = $itemModel->getItemByWeight(['id', 'name', 'code', 'style', 'size'], $weight);
        App::response([
            'status' => 200,
            'message' => 'Get transaction success',
            'data' => $items['data']
        ]);
    }

    public function getResultTimbangan(){
        $config = new Config();
        if ($config->getEnv('APP_ENV') == 'production') {
            require_once __DIR__ . '/../core/getHasilTimbang.php';
        } else {
            echo random_int(10, 350) / 10;
        }
        exit;
    }

    public function saveTransaction(){
        $dataIns['code_item'] = $_POST['code'] ?? null;
        $dataIns['name_item'] = $_POST['name'] ?? null;
        $dataIns['style_item'] = $_POST['style'] ?? null;
        $dataIns['size_item'] = $_POST['size'] ?? null;
        $dataIns['weight_item'] = empty($_POST['weight']) ? 0 : floatval($_POST['weight']);

        $insertResult = $this->model->insert($dataIns);
        if ($insertResult['status']) {
            App::response([
                'status' => 200,
                'message' => 'Save transaction success',
            ]);
        }else{
            App::response([
                'status' => 400,
                'message' => 'Save transaction failed',
            ]);
        }
    }

    public function deleteTransaction() {
        $dataJson = json_decode(file_get_contents('php://input'), true);
        $dataDel['id'] = $dataJson['id'] ?? null;

        $deleteResult = $this->model->delete($dataDel);
        if ($deleteResult['status']) {
            App::response([
                'status' => 200,
                'message' => 'Delete transaction success',
            ]);
        }else{
            App::response([
                'status' => 400,
                'message' => 'Delete transaction failed',
            ]);
        }
    }
}