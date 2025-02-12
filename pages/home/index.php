<?php
require './pages/templates/header.php';
?>
<body id="body-pd" class="body-pd">
    <header class="header body-pd border-bottom" id="header">
        <div class="header_toggle"><i class="fas fa-bars fs-3" id="header-toggle"></i> </div>
        
        <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="fs-4 me-2">Lutfan</span>
            <div class="header_img"><i class="fa fa-user-circle fa-3x"></i></div>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-key"></i> Ganti Password</a></li>
            <li><a class="dropdown-item" href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </header>

    <div class="l-navbar show" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo"> 
                    <i class="fas fa-kiwi-bird nav_logo-icon"></i><span class="nav_logo-name">kiwiAdmin</span>
                </a>
                <div class="nav_list">
                    <a href="#" class="nav_link active"> 
                        <i class="fas fa-border-all nav_icon"></i> <span class="nav_name">Dashboard</span> 
                    </a> 
                    <a href="#" class="nav_link"> 
                        <i class="far fa-user nav_icon"></i> <span class="nav_name">Users</span> 
                    </a> 
                    <a href="#" class="nav_link"> 
                        <i class="fas fa-boxes nav_icon"></i> <span class="nav_name">Items</span> 
                    </a> 
                    <a href="#" class="nav_link"> 
                        <i class="fas fa-clipboard nav_icon"></i> <span class="nav_name">Transactions</span> 
                    </a> 
                </div>
            </div> 
        </nav>
    </div>
    
    <div class="d-flex flex-column justify-content-between" style="min-height: 90vh;">
        <!--Container Main start-->
        <div class="dashboard-home">
            <div class="row pt-5">
                <div class="col-12">
                    <h1 class="fw-bold text-white">Dashboard</h1>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-md-4 mb-3">
                    <div class="card shadow border-0 w-auto" style="width: 18rem; height: 12rem;">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title align-middle" style="line-height: 38px;">Item</h3>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <div class="box bg-success p-2 rounded-1">
                                        <i class="fas fa-box fs-4 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="fw-bold">18</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow border-0 w-auto" style="width: 18rem; height: 12rem;">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="row">
                                <div class="col-8">
                                    <h3 class="card-title align-middle" style="line-height: 38px;">Transaction</h3>
                                </div>
                                <div class="col-4 d-flex justify-content-end align-items-center">
                                    <div class="box bg-warning p-2 rounded-1">
                                        <i class="fas fa-clipboard fs-4 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="fw-bold">34</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow border-0 w-auto" style="width: 18rem; height: 12rem;">
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title align-middle" style="line-height: 38px;">Users</h3>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <div class="box bg-danger p-2 rounded-1">
                                        <i class="fas fa-users fs-4 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="fw-bold">18</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow border-0">
                    <div class="card-header bg-transparent py-4">
                        Inputan transaksi hari ini
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-transaction-today"></table>
                    </div>
                </div>
            </div>
        </div>
        <!--Container Main end-->
        <footer class="w-100 text-center border-top">
            <div class="fw-bold py-2">Copyright &copy; 2025 Ltech</div>
        </footer>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) =>{
        const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if(toggle && nav && bodypd && headerpd){
            toggle.addEventListener('click', ()=>{
                // show navbar
                nav.classList.toggle('show2')
                // change icon
                toggle.classList.toggle('bx-x')
                // add padding to body
                bodypd.classList.toggle('body-pd')
                // add padding to header
                headerpd.classList.toggle('body-pd')
            })
        }
    }

    showNavbar('header-toggle','nav-bar','body-pd','header')

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink(){
        if(linkColor){
            linkColor.forEach(l=> l.classList.remove('active'))
            this.classList.add('active')
        }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink));
    // Your code to run since DOM is loaded and ready
});

// dom menggunakan jquery
$(document).ready(function() {
    let dataJson = [
        {
            "id": 1,
            "name": "Laptop",
            "qty": 2,
            "total": 200000
        },
        {
            "id": 2,
            "name": "Mouse",
            "qty": 3,
            "total": 30000
        },
        {
            "id": 3,
            "name": "Keyboard",
            "qty": 1,
            "total": 50000
        },
        {
            "id": 4,
            "name": "Monitor",
            "qty": 4,
            "total": 400000
        },
        {
            "id": 5,
            "name": "Headset",
            "qty": 5,
            "total": 50000
        },
        {
            "id": 6,
            "name": "Speaker",
            "qty": 2,
            "total": 100000
        }
    ];

    $('.table-transaction-today').DataTable({
        data: dataJson, // Data dari JSON
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'name', title: 'Nama Produk' },
            { data: 'qty', title: 'Jumlah' },
            { 
                data: 'total', 
                title: 'Total Harga',
                render: function (data, type, row) {
                    return 'Rp ' + data.toLocaleString('id-ID');
                }
            }
        ],
        paging: true, // Aktifkan pagination
        searching: true, // Aktifkan pencarian
        ordering: true, // Aktifkan sorting
        lengthMenu: [5, 10, 25, 50], // Pilihan jumlah data per halaman
        pageLength: 5, // Default jumlah data per halaman
        responsive: true,
        autoWidth: false,
        // language: {
        //     search: "Cari:", // Ubah teks pencarian
        //     lengthMenu: "Tampilkan _MENU_ data per halaman",
        //     info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
        //     paginate: {
        //         first: "Awal",
        //         last: "Akhir",
        //         next: "Selanjutnya",
        //         previous: "Sebelumnya"
        //     }
        // }
    });
});

</script>
</body>
<?php
require './pages/templates/footer.php';
?>