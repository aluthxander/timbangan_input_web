<?php
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}else{
    $page = 'dashboard';
}
?>
<header class="header body-pd" id="header">
    <div class="header_toggle"><i class="fas fa-bars" id="header-toggle"></i> </div>
    <!-- <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpg" alt=""> </div> -->
</header>
<div class="l-navbar show-h" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="?route=admin" class="nav_logo"> 
                <!-- <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">kiwi</span> -->
                <i class="fas fa-kiwi-bird nav_logo-icon"></i><span class="nav_logo-name">kiwiAdmin</span>
            </a>
            <div class="nav_list">
                <a href="?route=admin" class="nav_link <?= $page == 'dashboard' ? 'active' : '' ?>"> 
                    <i class="fas fa-border-all nav_icon"></i> <span class="nav_name">Dashboard</span> 
                </a> 
                <a href="?route=admin&page=pegawai" class="nav_link <?= $page == 'pegawai' ? 'active' : '' ?>"> 
                    <i class="fas fa-user-tie nav_icon"></i> <span class="nav_name">Pegawai</span> 
                </a> 
                <a href="?route=admin&page=user" class="nav_link <?= $page == 'user' ? 'active' : '' ?>"> 
                    <i class="far fa-user nav_icon"></i> <span class="nav_name">Users</span> 
                </a> 
                <a href="?route=admin&page=barang" class="nav_link <?= $page == 'barang' ? 'active' : '' ?>"> 
                    <i class="fas fa-boxes nav_icon"></i> <span class="nav_name">Barang</span> 
                </a> 
                <!-- <a href="#" class="nav_link"> 
                    <i class="fas fa-comments nav_icon"></i> <span class="nav_name">Pesan</span> 
                </a>  -->
                <a href="?route=admin&page=transaksi" class="nav_link <?= $page == 'transaksi' ? 'active' : '' ?>"> 
                    <i class="far fa-money-bill-alt nav_icon"></i> <span class="nav_name">Transaksi</span> 
                </a> 
                <!-- <a href="#" class="nav_link">
                    <i class="fas fa-file-invoice-dollar nav_icon"></i> <span class="nav_name">Keuangan</span> 
                </a>  -->
            </div>
        </div> 
        <a href="#" class="nav_link">
            <i class="fas fa-sign-out-alt nav_icon"></i><span class="nav_name">SignOut</span>
        </a>
    </nav>
</div>

