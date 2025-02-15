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
        $data = $this->model->getTransaction();
        App::response([
            'status' => 200,
            'data' => $data
        ]);
    }
}