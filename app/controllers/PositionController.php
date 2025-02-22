<?php
namespace Ltech\WebTimbangan\controllers;
use Ltech\WebTimbangan\core\App;
use Ltech\WebTimbangan\models\MenuModel;
use Ltech\WebTimbangan\models\JabatanModel;
use Ltech\WebTimbangan\models\PositionAccessModel;
use Monolog\Logger;
use PHPUnit\Util\Json;

class PositionController{
    private $model;
    private $modelAccess;

    public function __construct(){
        $this->model = new JabatanModel();
        $this->modelAccess = new PositionAccessModel();
    }

    public function index(){
        App::render('positions.index');
    }

    public function getPosition(){
        $data = [];
        if (isset($_GET['id'])) {
            $jabatan = $this->model->getJabatan(['id', 'jabatan'], ['id' => $_GET['id']]);
            $data = $jabatan['data'][0];
        }else{
            $jabatan = $this->model->getJabatan(['id', 'jabatan']);
            // ambil semua akses berdasarkan jabatan
            $akses = new PositionAccessModel();
            foreach ($jabatan['data'] as &$value) {
                $value['access'] = $akses->getPositionAccessBy(['menu', 'read_access', 'create_access', 'update_access', 'delete_access'], $value['id']);
            }
            $data = $jabatan['data'];
            // ubah data json
        }
        App::response([
            'status' => 200,
            "message" => "get position success",
            'data' => $data
        ]);
    }

    public function accesPosition(){
        if (isset($_GET['position'])) {
            $access = new PositionAccessModel();
            $access = $access->getPositionAccessBy(['menu', 'read_access', 'create_access', 'update_access', 'delete_access'], $_GET['position']);
            // Rename the columns
            $access = array_map(function($item) {
                return [
                    'menu' => $item['menu'],
                    'read' => $item['read_access'],
                    'create' => $item['create_access'],
                    'update' => $item['update_access'],
                    'delete' => $item['delete_access'],
                ];
            }, $access);

            App::response([
                'status' => 200,
                'message' => 'get access position success',
                'data' => $access
            ]);
        }else{
            $menu = new MenuModel();
            $menu = $menu->getMenu(['id','menu']);
            $menu = array_filter($menu, function($menu){
                return $menu['id'] !== 5 && $menu['id'] !== 1;
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
                'message' => 'get access position success',
                'data' => $menu
            ]);
        }
    }

    public function savePosition(){
        $position = App::input('position');
        $dataJson = json_decode(App::input('data'), true);

        // Validasi input
        if (empty($position) || empty($dataJson)) {
            App::response([
                'status' => 400,
                'message' => 'input cannot be empty'
            ]);
            return;
        }

        try {
            // insert jabatan
            $jabatanIns = $this->model->insert(['jabatan' => $position]);
            if (!$jabatanIns['status']) {
                throw new \Exception($jabatanIns['message']);
            }
            
            $menu = new MenuModel();
            $data = [];
            foreach($dataJson as $key => &$menuItem){
                // ambil id menu
                $dataIns = [];
                $id_menu = $menu->getMenuBy(['id'], ['menu' => $key])[0]['id'];
                $dataIns['position_id'] =  $jabatanIns['id'];
                $dataIns['menu_id'] = $id_menu;
                foreach($menuItem as $i => &$value){
                    $value['value'] = $value['value'] == 'true' ? 1 : 0;
                    $dataIns[$value['access'].'_access'] = $value['value'];
                }
                $accessIns = $this->modelAccess->insert($dataIns);
                if (!$accessIns['status']) {
                    $this->model->delete(['id' => $jabatanIns['id']]);
                    $this->modelAccess->delete(['position_id' => $jabatanIns['id']]);
                    throw new \Exception($accessIns['message']);
                }
                $data[] = $dataIns;
            }

            // Response
            App::response([
                'status' => 200,
                'message' => 'Add position success'
            ]);
            // insert 
        }catch (\Exception $e) {
            // delete jabatan dan akses jika gagal
            App::logger('error', $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            App::response([
                'status' => 500,
                'message' => 'Add position failed'
            ]);
        }
    }

    public function deletePosition(){
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (empty($id)) {
            App::response([
                'status' => 400,
                'message' => 'Position cannot be empty'
            ]);
            exit;
        }

        try {
            $jabatan = $this->model->delete(['id' => $id]);
            if (!$jabatan['status']) {
                throw new \Exception($jabatan['message']);
            }
            $access = $this->modelAccess->delete(['position_id' => $id]);
            if (!$access['status']) {
                throw new \Exception($access['message']);
            }
            App::response([
                'status' => 200,
                'message' => 'Delete position success'
            ]);
        } catch (\Exception $e) {
            App::logger('error', $e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
            App::response([
                'status' => 500,
                'message' => 'Delete position failed'
            ]);
        }
    }

    public function updatePosition(){
        $dataJson = json_decode(file_get_contents('php://input'), true);
        // check position and menu access
        if (empty($dataJson['position']) || empty($dataJson['menu'])) {
            App::response([
                'status' => 400,
                'message' => 'input cannot be empty'
            ]);
            return;
        }

        $id = $dataJson['id'];
        $menus = $dataJson['menu'];
        $position = $dataJson['position'];
        $menu = new MenuModel();

        // update
        try {
            $jabatan = $this->model->update(['jabatan' => $position], ['id' => $id]);
            if (!$jabatan['status']) {
                throw new \Exception($jabatan['message']);
            }
            // delete old access
            $access = $this->modelAccess->delete(['position_id' => $id]);
            if (!$access['status']) {
                throw new \Exception($access['message']);
            }

            // insert new access
            foreach($menus as $key => &$menuItem){
                // ambil id menu
                $dataIns = [];
                $id_menu = $menu->getMenuBy(['id'], ['menu' => $key])[0]['id'];
                $dataIns['position_id'] =  $id;
                $dataIns['menu_id'] = $id_menu;
                foreach($menuItem as $i => &$value){
                    $value['value'] = $value['value'] == 'true' ? 1 : 0;
                    $dataIns[$value['access'].'_access'] = $value['value'];
                }
                $accessIns = $this->modelAccess->insert($dataIns);
                if (!$accessIns['status']) {
                    throw new \Exception($accessIns['message']);
                }
            }

            App::response([
                'status' => 200,
                'message' => 'Update position success'
            ]);
        } catch (\Throwable $th) {
            App::logger('error', $th->getMessage(), ['file' => $th->getFile(), 'line' => $th->getLine()]);
            App::response([
                'status' => 500,
                'message' => 'Update position failed'
            ]);
        }
    }
}