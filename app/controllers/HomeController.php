<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\TransactionModel;
use Ltech\WebTimbangan\models\UserModel;
use Ltech\WebTimbangan\models\ItemModel;

class HomeController
{
    private $modelTransaction;
    private $modelUser;
    private $modelItem;
    public function __construct()
    {
        $this->modelTransaction = new TransactionModel();
        $this->modelUser = new UserModel();
        $this->modelItem = new ItemModel();
    }

    public function index()
    {
        App::render('home.index');
    }

    public function getDataHome(){
        $data['count_transaction'] = $this->modelTransaction->countData();
        $data['count_user'] = $this->modelUser->countData();
        $data['count_item'] = $this->modelItem->countData();
        $data['transaction_today'] = $this->modelTransaction->getTransactionToday();
        foreach ($data['transaction_today'] as $key => &$row) {
            $row['weight_item'] = App::number_format_decimal($row['weight_item']);
        }
        $this->response([
            'status' => 200,
            'message' => 'Berhasil mengambil data transaksi',
            'data' => $data
        ]);
    }

    private function response($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}