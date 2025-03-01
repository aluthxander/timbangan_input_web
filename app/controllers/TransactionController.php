<?php

namespace Ltech\WebTimbangan\controllers;

use Ltech\WebTimbangan\core\App;
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
        $data = $this->model->getTransaction(['code_item', 'name_item', 'size_item', 'style_item', 'weight_item', 'id'])['data'];
        App::response([
            'status' => 200,
            'message' => 'Get all transaction success',
            'data' => $data
        ]);
    }
}