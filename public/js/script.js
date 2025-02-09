import BasicFunctions from './basic_method.js';


let elmementNip = document.querySelector('.pegawai #nip');
let mask;
if (elmementNip != null) {
    // masking NIP
    mask = IMask(elmementNip, {
                    mask: Number
                });
}

let elementstockbarangform = document.getElementById('stockbrg');
if (elementstockbarangform != null) {
    let masking_stockbarang = IMask(elementstockbarangform, {
        mask: Number,
        thousandsSeparator: '.',
        scale: 0, // No decimals
        signed: false // No negative numbers
    });
}

// harga satuan
let hargasatuan = document.getElementById('hrgsatuan');
// masking harga
if (hargasatuan != null) {
    let masking_hargasatuan = IMask(hargasatuan, {
        mask: 'Rp. num',
        blocks: {
            num: {
                mask: Number,
                thousandsSeparator: '.',
                radix: ',',
                mapToRadix: ['.']
            }
        }
    });
}

function inisializeTabelPegawai(){
    $('.pegawai #table-pegawai').DataTable({
        ajax: "api.php?route=pegawai&action=get",
        columns: [
            { data: 'no' },
            { data: 'nip' },
            { data: 'nama' },
            { data: 'email' },
            { data: 'jabatan' },
            { data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary edit-karyawan" data-karyawan="${data.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger hapus-karyawan" data-karyawan="${data.id}" data-nama="${data.nama}"><i class="fas fa-trash"></i></button>`;
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
                width: "20%", 
                targets: 1,
                className: "text-start"
            }, // Kolom kedua
            { 
                width: "20%", 
                targets: 2 
            }, // Kolom ketiga
            { 
                width: "25%", 
                targets: 3 
            }, // Kolom ketiga
            { 
                width: "15%", 
                targets: 4,
                className: "text-center"
            },  // Kolom keempat
            { 
                width: "15%", 
                targets: 5,
                className: "text-center"
            }  // Kolom keempat
        ],
        "autoWidth": false,
        "oLanguage" : {
            "sSearch": "Cari Pegawai: ",
            "sLengthMenu" : "_MENU_  page"
        },
        initComplete: function () {
            // ketika selesai mengload tabel maka tombol edit dan hapus di aktifkan
            $('.pegawai .edit-karyawan').click(function () {
                let id = $(this).data('karyawan');
                mask.updateValue();
                $.get('api.php?route=pegawai&action=getid&data=' + id, function (response) {
                    $(".pegawai #nip").val(response.nip);
                    $(".pegawai #name").val(response.nama);
                    $(".pegawai #email").val(response.email);
                    $(".pegawai #jabatan").val(response.jabatan);
                    $(".pegawai #form-pegawai").data('id', response.id);
                });
                $(".pegawai #btn-save").hide();
                $(".pegawai #btn-edit").show();
                $(".pegawai #modal-pegawai .modal-title").html('Edit Data Pegawai');
                $(".pegawai #modal-pegawai").modal('show');
                $(".pegawai #form-pegawai")[0].reset();
                $(".pegawai #form-pegawai")[0].classList.remove('was-validated');
                // hilangkan input password
                $(".pegawai #password").parent().hide();
                $(".pegawai #password").attr('required', false);
            });

            $('.pegawai .hapus-karyawan').click(function () {
                let data_karyawan = $(this).data('karyawan');
                let nama = $(this).data('nama');
                // cek apakah data karyawan dan nama ada
                if (data_karyawan && nama) {
                    Swal.fire({
                        title: "Hapus Data Pegawai",
                        text: "Yakin Ingin Menghapus Data Pegawai " + nama + "?",
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
                                url: 'api.php?route=pegawai&action=delete',
                                type: 'POST',
                                data: {
                                    data: data_karyawan
                                },
                                headers: {
                                    'X-CSRF-Token': csrf_token
                                },
                                success: function (response) {
                                    let icon_alert = 'error';
                                    let title_alert = 'Gagal Menghapus Data';
                                    let message = 'Data Pegawai ' + nama + ' Gagal di Hapus';
                                    if (response.status) {
                                        inisializeTabelPegawai();
                                        icon_alert = 'success';
                                        title_alert = 'Berhasil di Hapus';
                                        message = 'Data Pegawai ' + nama + ' Berhasil di Hapus';
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
        destroy: true
    });
}

function inisializeTabelUsers(){
    $('.users #table-user').DataTable({
        ajax: "api.php?route=users&action=get",
        columns: [
            { data: 'no' },
            { data: 'nama' },
            { data: 'email' },
            { data: 'alamat' },
            { data: null,
                render: function (data, type, row) {
                    return `<button class="btn btn-sm btn-primary edit-users" data-users="${data.id}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger hapus-users" data-users="${data.id}" data-nama="${data.nama}"><i class="fas fa-trash"></i></button>`;
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
                width: "20%", 
                targets: 1,
                className: "text-start"
            }, // Kolom kedua
            { 
                width: "20%", 
                targets: 2 
            }, // Kolom ketiga
            { 
                width: "25%", 
                targets: 3 
            }, // Kolom ketiga
            { 
                width: "15%", 
                targets: 4 
            }  // Kolom keempat
        ],
        "autoWidth": false,
        "oLanguage" : {
            "sSearch": "Cari Users: ",
            "sLengthMenu" : "_MENU_  page"
        },
        initComplete: function () {
            // ketika selesai mengload tabel maka tombol edit dan hapus di aktifkan
            $('.users .edit-users').click(function () {
                let id = $(this).data('users');
                $.get('api.php?route=users&action=getid&data=' + id, function (response) {
                    $(".users #name").val(response.nama);
                    $(".users #email").val(response.email);
                    $(".users #alamat").val(response.alamat);
                    $(".users #form-user").data('id', response.id);
                });
                $(".users #btn-save").hide();
                $(".users #btn-edit").show();
                $(".users #modal-user .modal-title").html('Edit Data User');
                $(".users #modal-user").modal('show');
                $(".users #form-user")[0].reset();
                $(".users #form-user")[0].classList.remove('was-validated');
                // hilangkan input password
                $(".users #password").parent().hide();
                $(".users #password").attr('required', false);
            });

            $('.users .hapus-users').click(function () {
                let data_user = $(this).data('users');
                let nama = $(this).data('nama');
                // cek apakah data karyawan dan nama ada
                if (data_user && nama) {
                    Swal.fire({
                        title: "Hapus Data User",
                        text: "Yakin Ingin Menghapus Data User " + nama + "?",
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
                                url: 'api.php?route=users&action=delete',
                                type: 'POST',
                                data: {
                                    data: data_user
                                },
                                headers: {
                                    'X-CSRF-Token': csrf_token
                                },
                                success: function (response) {
                                    let icon_alert = 'error';
                                    let title_alert = 'Gagal Menghapus Data';
                                    let message = 'Data User ' + nama + ' Gagal di Hapus';
                                    if (response.status) {
                                        inisializeTabelUsers();
                                        icon_alert = 'success';
                                        title_alert = 'Berhasil di Hapus';
                                        message = 'Data User ' + nama + ' Berhasil di Hapus';
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
        destroy: true
    });
}

function initializeTableBarang(params) {
    $('.barang #table-barang').DataTable({
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
            $('.barang .btn-edit').on('click', function () {
                let kode = $(this).data('kode');
                $('.barang #btn-save').hide();
                $('.barang #btn-update').show();
                $('.barang #modal-barang h1.modal-title').html('Edit Barang');
                $('.barang #form-barang')[0].reset();
                $.get('api.php?route=barang&action=getid&data=' + kode, function (data) {
                    let harga_satuan = 'Rp. ' + data.harga.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    let stok = data.stok.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    $('.barang #form-barang').data('kode' ,data.kode);
                    $('.barang #kodebrg').val(data.kode);
                    $('.barang #namabrg').val(data.nama);
                    masking_hargasatuan.value = harga_satuan;
                    masking_stockbarang.value = stok;
                })
            });

            // hapus barang
            $('.barang .btn-hapus').on('click', function () {
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

$(document).ready(function () {
    // inisialisasi tabel
    inisializeTabelPegawai();
    inisializeTabelUsers();
    initializeTableBarang();
    // ====================================== pegawai ============================================
    // ketika nip berubah maka update imask
    // button tambah pegawai
    $('.pegawai #btn-tambah').click(function () {
        $(".pegawai #btn-save").show();
        $(".pegawai #btn-edit").hide();
        $(".pegawai #form-pegawai")[0].reset();
        $(".pegawai #form-pegawai")[0].classList.remove('was-validated');
        $(".pegawai #modal-pegawai .modal-title").html('Tambah Pegawai');
        // hilangkan input password
        $(".pegawai #password").parent().show();
        $(".pegawai #password").attr('required', true);
        // hapus data-id
        $(".pegawai #form-pegawai").removeAttr('data-id');
    });
    // button save pegawai
    $('.pegawai #btn-save').click(function () {
        let form = $('.pegawai #form-pegawai')[0];
        let data = new FormData(form);
        // cek apakah data required telah diisi
        if (form.checkValidity() == false) {
            form.classList.add('was-validated');
            return false;
        }else{
            // mengambil csrf token
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            // mengirimkan data ke server
            $.ajax({
                url: 'api.php?route=pegawai&action=insert',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                processData: false,  // Jangan proses data (akan ditangani FormData)
                contentType: false,  // Jangan set tipe konten (akan ditangani FormData)
                dataType: 'json',
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal Menyimpan Data';
                    if (response.status) {
                        $('.pegawai #modal-pegawai').modal('hide');
                        inisializeTabelPegawai();
                        icon_alert = 'success';
                        title_alert = 'Berhasil di Simpan';
                    }
                    // tampilkan alert
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: response.message
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    });

    // btn edit pegawai
    $('.pegawai #btn-edit').click(function () {
        let form = $('.pegawai #form-pegawai')[0];
        let data = new FormData(form);
        let data_karyawan = $(".pegawai #form-pegawai").data('id');
        // cek apakah data required telah diisi
        if (form.checkValidity() == false) {
            form.classList.add('was-validated');
            return false;
        }else{
            // mengambil csrf token
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            // mengirimkan data ke server
            $.ajax({
                url: 'api.php?route=pegawai&action=update&data=' + data_karyawan,
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                processData: false,  // Jangan proses data (akan ditangani FormData)
                contentType: false,  // Jangan set tipe konten (akan ditangani FormData)
                dataType: 'json',
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal Mengedit Data';
                    if (response.status) {
                        $('.pegawai #modal-pegawai').modal('hide');
                        inisializeTabelPegawai();
                        icon_alert = 'success';
                        title_alert = 'Berhasil';
                    }
                    // tampilkan alert
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    });
    // ====================================== akhir pegawai ============================================

    // ===================================== user ============================================
    // button tambah user
    $('.users #btn-tambah').click(function () {
        $(".users #btn-save").show();
        $(".users #btn-edit").hide();
        $(".users #form-user")[0].reset();
        $(".users #form-user")[0].classList.remove('was-validated');
        $(".users #modal-user .modal-title").html('Tambah User');
        // hilangkan input password
        $(".users #password").parent().show();
        $(".users #password").attr('required', true);
        // hapus data-id
        $(".users #form-user").removeAttr('data-id');
    });

    // button save user
    $('.users #btn-save').click(function () {
        let form = $('.users #form-user')[0];
        let dataform = new FormData(form);
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return false;
        }else{
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: 'api.php?route=users&action=insert',
                type: 'POST',
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                data: dataform,
                processData: false,
                contentType: false,
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal Menambah User';
                    let message = 'User Gagal di Tambahkan';
                    if (response.status) {
                        inisializeTabelUsers();
                        icon_alert = 'success';
                        title_alert = 'Berhasil di Aktifkan';
                        message = 'User Berhasil di Aktifkan';
                        $(".users #modal-user").modal('hide');
                    }else if(response.message == '23000'){
                        message = 'Gagal menambahkan users: Email sudah digunakan.'
                    }
                    // tampilkan alert
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });

    $('.users #btn-edit').click(function () {
        let form = $('.users #form-user')[0];
        let dataForm = new FormData(form);
        let data_user = $(".users #form-user").data('id');
        // cek apakah data required telah diisi
        if (form.checkValidity() == false) {
            form.classList.add('was-validated');
            return false;
        }else{
            // mengambil csrf token
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            // mengirimkan data ke server
            $.ajax({
                url: 'api.php?route=users&action=update&data=' + data_user,
                method: 'POST',
                data: dataForm,
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                processData: false,  // Jangan proses data (akan ditangani FormData)
                contentType: false,  // Jangan set tipe konten (akan ditangani FormData)
                dataType: 'json',
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal Mengedit Data';
                    if (response.status) {
                        $('.users #modal-user').modal('hide');
                        inisializeTabelUsers();
                        icon_alert = 'success';
                        title_alert = 'Berhasil';
                    }
                    // tampilkan alert
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    });
    // ====================================== akhir user ============================================

    // ==================================== Barang ==================================================
    // button tambah barang
    $('.barang #btn-tambah').click(function () {
        $(".barang #btn-save").show();
        $(".barang #btn-update").hide();
        $(".barang #form-barang")[0].reset();
        $('.barang #modal-barang h1.modal-title').html('Tambah Barang');
    });
    // button save barang
    $('.barang #btn-save').click(function (event) {
        event.preventDefault(); // Prevent default form submission
        let form = $('.barang #form-barang')[0];
        let data = new FormData(form);
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
        } else {
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST",
                url: "api.php?route=barang&action=insert",
                data: data,
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                processData: false,
                contentType: false,
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal Menambah User';
                    let message = 'Barang Gagal di Tambahkan';
                    if (response.status) {
                        initializeTableBarang();
                        icon_alert = 'success';
                        title_alert = 'Berhasil ditambah';
                        message = 'Barang Berhasil ditambahkan';
                        $(".barang #modal-barang").modal('hide');
                    } else if(response.message === '23000'){
                        message = 'Kode Barang sudah ada, silahkan coba menggunakan kode lain';
                    }
                    // Handle success response here
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    // Handle error here
                }
            });
        }
    });

    $('.barang #btn-update').click(function (event) {
        event.preventDefault(); // Prevent default form submission
        let form = $('.barang #form-barang')[0];
        let data = new FormData(form);
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return false;
        }else{
            let csrf_token = $('meta[name="csrf-token"]').attr('content');
            let kode_awal = $('.barang #form-barang').data('kode');
            $.ajax({
                type: "POST",
                url: "api.php?route=barang&action=update&data=" + kode_awal,
                data: data,
                headers: {
                    'X-CSRF-Token': csrf_token
                },
                processData: false,
                contentType: false,
                success: function (response) {
                    let icon_alert = 'error';
                    let title_alert = 'Gagal ubah barang';
                    let message = 'Barang Gagal di Tambahkan';
                    if (response.status) {
                        initializeTableBarang();
                        icon_alert = 'success';
                        title_alert = 'Berhasil diubah';
                        message = 'Barang Berhasil diubah';
                        $(".barang #modal-barang").modal('hide');
                    }else if(response.message === '23000'){
                        message = 'Kode Barang sudah ada, silahkan coba menggunakan kode lain';
                    }
                    // Handle success response here
                    Swal.fire({
                        icon: icon_alert,
                        title: title_alert,
                        text: message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    // Handle error here
                }
            });
        }
    });
    // ==================================== akhir Barang ============================================
});