<?php

namespace Ltech\WebTimbangan\controllers;

use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\ItemModel;

class ItemController
{
    private $model;
    public function __construct()
    {
        $this->model = new ItemModel();
    }
    public function index()
    {
        $structureData =  $this->model->structureItems();
        // ambil enum size ubah menjadi array
        $data = [];
        foreach ($structureData as $key => $value) {
            if ($value['Field'] == 'size') {
                $enum = $value['Type']; // misalnya "enum('S','M','L')"
                // Ambil bagian di dalam tanda kurung
                if (preg_match("/^enum\((.*)\)$/", $enum, $matches)) {
                    // Gunakan str_getcsv untuk parsing string CSV dengan delimiter koma dan enclosure '
                    $data = str_getcsv($matches[1], ',', "'");
                }
            }
        }
        App::render('items.index', $data);
    }

    public function getAllItems()
    {
        $item = $this->model->getItems();
        App::response([
            'status' => 200,
            'message' => 'Get all items success',
            'data' => $item
        ]);
    }
}