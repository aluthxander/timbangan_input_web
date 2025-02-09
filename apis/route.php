<?php
session_start();
require "./vendor/autoload.php";
include "./config/koneksi.php";
require "./config/BasicMethod.php";

$route = isset($_GET['route']) ? $_GET['route'] : '';
header("Content-Type: application/json");
if ($route == 'pegawai') {
    require './apis/pegawai/pegawai_ajax.php';
}elseif($route == 'users'){
    require './apis/users/users_ajax.php';
}elseif($route == 'barang'){
    require './apis/barang/barang_ajax.php';
}else{
    echo "Page not found";
}
?>