<?php 

?>
<header class="header body-pd border-bottom" id="header">
    <div class="header_toggle"><i class="fas fa-bars fs-3" id="header-toggle"></i> </div>
    
    <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="fs-4 me-2">Lutfan</span>
        <div class="header_img"><i class="fa fa-user-circle fa-3x"></i></div>
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
        <li><a class="dropdown-item" href="#"><i class="fas fa-key"></i> Ganti Password</a></li>
        <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</header>

<div class="l-navbar show" id="nav-bar">
    <nav class="nav">
        <div>
            <a href="/home" class="nav_logo"> 
                <!-- <i class="fas fa-kiwi-bird nav_logo-icon"></i><span class="nav_logo-name">kiwiAdmin</span> -->
                <img src="./img/logo/apparel.png" class="logo-brand img-fluid" style="max-width: 134px;" alt="apparel logo">
            </a>
            <div class="nav_list">
                <a href="/home" class="nav_link <?= $model['path'] == 'home' ? ' active' : '' ?>"> 
                    <i class="fas fa-border-all nav_icon"></i> <span class="nav_name">Dashboard</span> 
                </a> 
                <a href="/users" class="nav_link <?= $model['path'] == 'users' ? ' active' : '' ?>"> 
                    <i class="far fa-user nav_icon"></i> <span class="nav_name">Users</span> 
                </a> 
                <a href="/items" class="nav_link <?= $model['path'] == 'items' ? ' active' : '' ?>"> 
                    <i class="fas fa-boxes nav_icon"></i> <span class="nav_name">Items</span> 
                </a> 
                <a href="/transactions" class="nav_link <?= $model['path'] == 'transactions' ? ' active' : '' ?>"> 
                    <i class="fas fa-clipboard nav_icon"></i> <span class="nav_name">Transactions</span> 
                </a> 
            </div>
        </div> 
    </nav>
</div>

<div class="d-flex flex-column justify-content-between" style="min-height: 90vh;">
<!--Container Main start-->