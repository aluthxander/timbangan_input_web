<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\JabatanModel;
use Ltech\WebTimbangan\models\MenuModel;
use Ltech\WebTimbangan\models\PositionAccessModel;

class PositionController{
    private $model;

    public function __construct(){
        $this->model = new JabatanModel();
    }

    public function index(){
        App::render('positions.index');
    }

    public function getPosition(){
        $jabatan = $this->model->getJabatan(['id', 'jabatan']);
        // ambil semua akses berdasarkan jabatan
        $akses = new PositionAccessModel();
        foreach ($jabatan as &$value) {
            $value['access'] = $akses->getPositionAccessBy(['`menu`', '`read`', '`create`', '`update`', '`delete`'], $value['id']);
        }
        // ubah data json
        App::response([
            'status' => 200,
            'data' => $jabatan
        ]);
    }

    public function accesPosition(){
        $menu = new MenuModel();
        $menu = $menu->getMenu(['id','menu']);
        $menu = array_filter($menu, function($menu){
            return $menu['menu'] !== 'Positions & Access' && $menu['menu'] !== 'Home';
        });

        // Reset array keys agar indeks tetap berurutan
        $filteredMenu = array_values($menu);

        $filteredMenu = array_map(function($menu){
            $menu['read'] = false;
            $menu['create'] = false;
            $menu['update'] = false;
            $menu['delete'] = false;
            return $menu;
        }, $menu);

        $menu = array_values($filteredMenu);
        App::response([
            'status' => 200,
            'data' => $menu
        ]);
    }
}