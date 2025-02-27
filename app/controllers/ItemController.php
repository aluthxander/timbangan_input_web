<?php

namespace Ltech\WebTimbangan\controllers;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\ItemModel;
use Ltech\WebTimbangan\models\SizeModel;

class ItemController
{
    private $model;
    public function __construct()
    {
        $this->model = new ItemModel();
    }
    public function index()
    {
        // ambil enum size ubah menjadi array
        $size = new SizeModel();
        $data = $size->getSize(['size'])['data'];
        $descWeb = $this->model->getRequireInTable();
        $data['desc'] = $descWeb;
        App::render('items.index', $data);
    }

    public function getAllItems()
    {
        $item = $this->model->getItems(['code', 'name', 'size', 'style', 'weight_max', 'weight_min']);
        if ($item['status']) {
            // cek akses
            $access = App::getAccesUser('items');

            $item['data'] = array_map(function ($item) use ($access) {
                $item['edit'] = $access['update_access'];
                $item['delete'] = $access['delete_access'];
                $item['accessby'] = $_SESSION['user']['jabatan_id'];
                return $item;
            }, $item['data']);
        }
        
        App::response([
            'status' => 200,
            'message' => 'Get all items success',
            'data' => $item['data']
        ]);
    }

    public function saveItem(){
        $datains['code'] = $_POST['kodebarang'] ?? null;
        $datains['name'] = $_POST['namabarang'] ?? null;
        $datains['style'] = $_POST['stylebarang'] ?? null;
        $datains['size'] = $_POST['sizebarang'] ?? null;
        $datains['weight_min'] = App::cleanAndConvertToNumber($_POST['beratmin'], 'float');
        $datains['weight_max'] = App::cleanAndConvertToNumber($_POST['beratmax'], 'float');

        // check field required is filled
        $errors = array();
        if (empty($datains['code'])) {$errors['kodebarang'] = 'Code item cannot be empty';}
        if (empty($datains['name'])) {$errors['namabarang'] = 'Name item cannot be empty';}
        if (empty($datains['style'])) {$errors['stylebarang'] = 'Style item cannot be empty';}
        if (empty($datains['size'])) {$errors['sizebarang'] = 'Size item cannot be empty';}
        if (empty($datains['weight_min'])) {$errors['beratmin'] = 'Min weight cannot be empty';}
        if (empty($datains['weight_max'])) {$errors['beratmax'] = 'Max weight cannot be empty';}

        if (!empty($errors)) {
            App::response([
                'status' => 400,
                'message' => 'Save item failed',
                'errors' => $errors
            ]);
        }

        $insertData = $this->model->insert($datains);
        
        App::response([
            'status' => 200,
            'message' => 'Save item success',
            'data' => $insertData['message']
        ]);
    }
}