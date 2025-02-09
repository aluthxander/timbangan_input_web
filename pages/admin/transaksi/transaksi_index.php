<?php include "./pages/admin/navbar_admin.php"?>
<div class="transaksi">
    <div class="row">
        <div class="col-12 text-center fs-1 fw-bold">
            Daftar Transaksi
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="table-transaksi">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No Transaksi</th>
                        <th class="text-center">Nama Pembeli</th>
                        <th class="text-center">Email Pembeli</th>
                        <th class="text-center">Alamat Pembeli</th>
                        <th class="text-center">Total harga</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>XNSJCUSIYD</td>
                        <td>jhon doe</td>
                        <td>Doe@gmail.com</td>
                        <td>Bali, shcgas,1241</td>
                        <td>Bali, shcgas,1241</td>
                        <td>Bali, shcgas,1241</td>
                        <td>Bali, shcgas,1241</td>
                        <td>Bali, shcgas,1241</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger" onclick="delete_user(2, 'Jhon Doe')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>ADKDJIANE</td>
                        <td>Sarah</td>
                        <td>Sarah@gamil.com</td>
                        <td>Semarang, JHXIA,q7141</td>
                        <td>Semarang, JHXIA,q7141</td>
                        <td>Semarang, JHXIA,q7141</td>
                        <td>Semarang, JHXIA,q7141</td>
                        <td>Semarang, JHXIA,q7141</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger" onclick="delete_user(1, 'sarah')"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "./pages/templates/js.php";?>


<script>
function initializeTableTransaksi(params) {
    $('.transaksi #table-transaksi').DataTable({
        ajax: "api.php?route=barang&action=get",
        bDestroy: true,
        columns: [
            { data: 'no' },
            { data: 'kode' },
            { data: 'nama' },
            { data: 'harga' },
            { data: 'stok' },
            { data: 'total_dibeli' },
            { data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary btn-edit" data-kode="${data.kode}" data-bs-toggle="modal" data-bs-target="#modal-barang"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-hapus" data-kode="${data.kode}" data-nama="${data.nama}"><i class="fas fa-trash"></i></button>`;
                }
            }
        ],
        columnDefs: [
            { 
                width: "5%", 
                targets: 0,
                className: "text-center"
            }, // Kolom pertama
            { 
                width: "14%", 
                targets: 1,
                className: "text-start"
            }, // Kolom kedua
            { 
                width: "25%", 
                targets: 2 
            }, // Kolom ketiga
            { 
                width: "20%", 
                targets: 3 
            }, // Kolom ketiga
            { 
                width: "13%", 
                targets: 4 ,
                className: "text-center"
            },  // Kolom keempat
            {
                width: "13%",
                targets: 5,
                className: "text-center"
            },
            { 
                width: "10%", 
                targets: 6,
                className: "text-center"
            },  // Kolom kelima
        ],
        initComplete: function () {
            $('.transaksi .btn-edit').on('click', function () {
                let kode = $(this).data('kode');
                $('.transaksi #btn-save').hide();
                $('.transaksi #btn-update').show();
                $('.transaksi #modal-barang h1.modal-title').html('Edit Barang');
                $('.transaksi #form-barang')[0].reset();
                $.get('api.php?route=barang&action=getid&data=' + kode, function (data) {
                    let harga_satuan = 'Rp. ' + data.harga.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let stok = data.stok.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    $('.transaksi #form-barang').data('kode' ,data.kode);
                    $('.transaksi #kodebrg').val(data.kode);
                    $('.transaksi #namabrg').val(data.nama);
                    masking_hargasatuan.value = harga_satuan;
                    masking_stockbarang.value = stok;
                })
            });

            // hapus barang
            $('.transaksi .btn-hapus').on('click', function () {
                let kode = $(this).data('kode');
                let nama = $(this).data('nama');
                if (kode && nama) {
                    Swal.fire({
                        title: "Hapus Data Barang",
                        text: "Yakin Ingin Menghapus Data " + nama + "?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#FFC107",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak"
                        }).then((result) => {
                        if (result.isConfirmed) {
                            let csrf_token = $('meta[name="csrf-token"]').attr('content');
                            $.ajax({
                                url: 'api.php?route=barang&action=delete',
                                type: 'POST',
                                data: {
                                    data: kode
                                },
                                headers: {
                                    'X-CSRF-Token': csrf_token
                                },
                                success: function (response) {
                                    let icon_alert = 'error';
                                    let title_alert = 'Gagal Menghapus Data';
                                    let message = 'Data ' + nama + ' Gagal di Hapus';
                                    if (response.status) {
                                        initializeTableBarang();
                                        icon_alert = 'success';
                                        title_alert = 'Berhasil di Hapus';
                                        message = 'Data ' + nama + ' Berhasil di Hapus';
                                    }
                                    // tampilkan alert
                                    Swal.fire({
                                        icon: icon_alert,
                                        title: title_alert,
                                        text: message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            });
                        }
                    });
                } else {
                    console.error("One or more required elements are missing");
                }
            });
        },
        "autoWidth": false,
        "oLanguage" : {
            "sSearch": "Cari barang: ",
            "sLengthMenu" : "_MENU_  page"
        }
    });
}

</script>