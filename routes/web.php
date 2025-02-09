<?php 
session_start();

$page = isset($_GET['page']) ? $_GET['page'] : '';
if ($page == 'home') {
    # code...
}else{
    require "./pages/login/index.php";
}
?>