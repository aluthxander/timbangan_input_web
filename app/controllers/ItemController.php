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
        $item = $this->model->getItems(['id','code', 'name', 'size', 'style', 'weight_max', 'weight_min']);
        if ($item['status']) {
            // cek akses
            $access = App::getAccesUser('items');

            $item['data'] = array_map(function ($item) use ($access) {
                $item['weight_max'] = App::number_format_decimal($item['weight_max'], 2);
                $item['weight_min'] = App::number_format_decimal($item['weight_min'], 2);
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

    public function getItem(){
        $id['id'] = $_GET['id'] ?? null;
        $item = $this->model->getItems(['id','code', 'name', 'size', 'style', 'weight_max', 'weight_min'],$id);
        if ($item['status']) {
            $item['data'][0]['weight_max'] = App::number_format_decimal($item['data'][0]['weight_max'], 2);
            $item['data'][0]['weight_min'] = App::number_format_decimal($item['data'][0]['weight_min'], 2);
            App::response([
                'status' => 200,
                'message' => 'Get item success',
                'data' => $item['data'][0]
            ]);
        }else{
            App::response([
                'status' => 400,
                'message' => 'Get item failed',
                'data' => $item['data']
            ]);
        }
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

    public function deleteItem(){
        $id['id'] = $_GET['id'] ?? null;
        $deleteData = $this->model->delete($id);
        if ($deleteData['status']) {
            App::response([
                'status' => 200,
                'message' => 'Delete item success',
                'data' => $id
            ]);
        }else{
            App::response([
                'status' => 400,
                'message' => 'Delete item failed',
                'data' => $deleteData['message']
            ]);
        }
    }

    public function updateItem(){
        $dataJson = json_decode(file_get_contents('php://input'), true);
        $where['id'] = $dataJson['id-item'] ?? null;
        $dataUpd['code'] = $dataJson['kodebarang'] ?? null;
        $dataUpd['name'] = $dataJson['namabarang'] ?? null;
        $dataUpd['style'] = $dataJson['stylebarang'] ?? null;
        $dataUpd['size'] = $dataJson['sizebarang'] ?? null;
        $dataUpd['weight_min'] = App::cleanAndConvertToNumber($dataJson['beratmin'], 'float');
        $dataUpd['weight_max'] = App::cleanAndConvertToNumber($dataJson['beratmax'], 'float');

        // check field required is filled
        $errors = array();
        if (empty($dataUpd['code'])) {$errors['kodebarang'] = 'Code item cannot be empty';}
        if (empty($dataUpd['name'])) {$errors['namabarang'] = 'Name item cannot be empty';}
        if (empty($dataUpd['style'])) {$errors['stylebarang'] = 'Style item cannot be empty';}
        if (empty($dataUpd['size'])) {$errors['sizebarang'] = 'Size item cannot be empty';}
        if (empty($dataUpd['weight_min'])) {$errors['beratmin'] = 'Min weight cannot be empty';}
        if (empty($dataUpd['weight_max'])) {$errors['beratmax'] = 'Max weight cannot be empty';}

        if (!empty($errors)) {
            App::response([
                'status' => 400,
                'message' => 'Save item failed',
                'errors' => $errors
            ]);
        }

        $updateData = $this->model->update($dataUpd, $where);

        if ($updateData['status']) {
            App::response([
                'status' => 200,
                'message' => 'Update item success',
                'data' => $updateData['message']
            ]);
        }else{
            App::response([
                'status' => 400,
                'message' => 'Update item failed',
                'data' => $updateData['message']
            ]);
        }
    }
}