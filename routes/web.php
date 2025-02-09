<?php 
session_start();
require "./config/BasicMethod.php";

$route = isset($_GET['route']) ? $_GET['route'] : 'home';
$page = isset($_GET['page']) ? $_GET['page'] : '';
if ($route == 'admin') {
    switch ($page) {
        case 'pegawai':
            include "./pages/admin/pegawai/pegawai_index.php";
            break;
        case 'user':
            include "./pages/admin/users/users_index.php";
            break;
        case 'barang':
            include "./pages/admin/barang/barang_index.php";
            break;
        case 'transaksi':
            include "./pages/admin/transaksi/transaksi_index.php";
            break;
        default:
            include "./pages/admin/dashboard/dashboard.php";
            break;
    }
}else{
    include "./pages/users/home.php";
}
?>