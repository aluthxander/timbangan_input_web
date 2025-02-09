<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Berbasis IoT</title>
    <link rel="stylesheet" href="./plugin/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./plugin/DataTables/datatables.css">
    <link rel="stylesheet" href="./plugin/select2/select2.min.css">
    <link rel="stylesheet" href="./plugin/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./src/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand text-white" href="index.php">Kiwi<i class="fas fa-kiwi-bird"></i><span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white <?= isset($_GET['route']) ? '' : 'active' ?>" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= isset($_GET['route']) ? 'active' : '' ?>" href="index.php?route=store">Shop</a>
                </li>
            </ul>
            <?php 
            if (isset($_GET['route'])) {?>
            <form class="d-flex ms-auto" role="search">
                <button class="btn btn-outline-warning position-relative dropdown-toggle" id="btnChart" type="button" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <ul class="dropdown-menu">
                    <div class="row px-4 py-1 text-center headershop">
                        <div class="col-3">Nama</div>
                        <div class="col-3">Harga</div>
                        <div class="col-4">Qty</div>
                        <div class="col-2">Aksi</div>
                    </div>
                    <div class="dropdown-divider top"></div>

                    <div class="dropdown-divider down"></div>
                    <div class="row px-4 py-1 total">
                        <div class="col-3 text-center">Total</div>
                        <div class="col-9 totalpembelian">Rp. 0,00</div>
                    </div>
                    <div class="dropdown-divider down"></div>
                    <div class="row px-4">
                        <form id="formCheckout" method="POST">
                            <div class="col-12 mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Nama">
                            </div>
                            <div class="col-12 mb-3">
                                <input type="email" name="email" class="form-control" placeholder="example@gmail.com">
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" name="phoneNumber" class="form-control" placeholder="No. Telp">
                            </div>
                            <div class="col-12 mb-3">
                                <button type="button" class="btn w-100 disabled" id="btnCheckout" disabled>Checkout</button>
                            </div>
                        </form>
                    </div>
                </ul>
            </form>
            <?php
            }
            ?>
        </div>
    </div>
</nav>
<script src="./plugin/Jquery/jquery-3.7.1.slim.min.js"></script>
<script src="./plugin/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
<script src="./plugin/DataTables/datatables.js"></script>
<script src="./plugin/sweetalert/sweetalert2@11.js"></script>
<script src="./plugin/select2/select2.min.js"></script>
<?php 
if (isset($_GET['route'])) {
    include "./pages/store.php";
}else{
    include "./pages/home.php";
}
?>
</body>
</html>