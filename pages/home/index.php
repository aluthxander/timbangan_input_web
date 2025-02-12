<?php
require './pages/templates/header.php';
?>
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
                            <h1 class="fw-bold count-item">0</h1>
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
                            <h1 class="fw-bold count-transaction">0</h1>
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
                            <h1 class="fw-bold count-users">0</h1>
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
<script>
// dom menggunakan jquery
$(document).ready(function() {
    $.ajax({
        url: "./routes/api.php?route=home",
        method: "GET",
        dataType: "json",
        success: function(res) {
            // Lakukan sesuatu dengan data yang diterima
            if (res.status == 200) {
                let data = res.data;
                $('.count-item').text(data.count_item);
                $('.count-transaction').text(data.count_transaction);
                $('.count-users').text(data.count_user);
                $('.table-transaction-today').DataTable({
                    data: data.transaction_today, // Data dari JSON
                    columns: [
                        { 
                            data: null, 
                            title: 'NO',
                            render: function (data, type, row, meta) {
                                return meta.row + 1;
                            } 
                        },
                        { 
                            data: 'no_invoice', 
                            title: 'Nomor Invoice' 
                        },
                        { 
                            data: 'code_item', 
                            title: 'Kode Barang' 
                        },
                        { 
                            data: 'name_item', 
                            title: 'Nama Barang',
                        },
                        { 
                            data: 'style_item', 
                            title: 'Style Barang' 
                        },
                        { 
                            data: 'size_item', 
                            title: 'Size Barang',
                            className: 'text-center'
                        },
                        { 
                            data: 'weight_item', 
                            title: 'Berat Barang',
                            className: 'text-center'
                        }
                    ],
                    paging: true, // Aktifkan pagination
                    searching: true, // Aktifkan pencarian
                    ordering: true, // Aktifkan sorting
                    lengthMenu: [5, 10, 25, 50], // Pilihan jumlah data per halaman
                    pageLength: 5, // Default jumlah data per halaman
                    responsive: true,
                    autoWidth: false,
                    headerCallback: function (thead, data, start, end, display) {
                        $(thead).find('th').css('text-align', 'center');
                        $(thead).find('th').addClass('bg-secondary-subtle');
                    },
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
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>