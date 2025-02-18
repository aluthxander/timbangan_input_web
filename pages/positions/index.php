<?php
require './pages/templates/header.php';
?>
<div class="positions">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Position & Access</h1>
            <div class="d-flex">
                <button class="btn btn-primary btn-add-positions ms-2">
                    <i class="fas fa-plus"></i> Add Position
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-positions"></table>
        </div>
    </div>
</div>
<div class="positions-form pt-5" style="display: none;">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New Positions</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="nama-position" class="form-label">Position</label>
                <input type="text" class="form-control" id="nama-position" name="nama-position">
            </div>
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered table-access"></table>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <button class="btn btn-secondary btn-back-positions me-2">
                <i class="fa fa-chevron-left"></i> Back
            </button>
            <button class="btn btn-primary btn-save-positions">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</div>
<script>
function initialTablePositions() {
    $('.table-positions').DataTable({
        ajax: "./routes/api.php?route=positions",
        columns: [
            { 
                data: null,
                title: "NO",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                className: "text-center",
                width: "5%"
            },
            { 
                data: "jabatan",
                title: "Posistion"
            },
            { 
                data: "access",
                title: "Access",
                render: function (data, type, row, meta) {
                    let el = '';
                    let el_li = '';
                    data.forEach(el => {
                        let el_span = '';
                        // looping data menu yang bisa di akses
                        if (el.read) {
                            el_span += `<span class="badge bg-primary me-1">Read</span>`;
                        }
    
                        if (el.create) {
                            el_span += `<span class="badge bg-primary me-1">Create</span>`;
                        }
    
                        if (el.update) {
                            el_span += `<span class="badge bg-primary me-1">Update</span>`;
                        }
    
                        if (el.delete) {
                            el_span += `<span class="badge bg-primary me-1">Delete</span>`;
                        }
                        
                        el_li += `<li class="list-group-item">${el.menu} : ${el_span}</li>`;
                    });

                    el = `<ul class="list-group">${el_li}</ul>`;
                    return el;
                }
            },
            {
                data: 'id',
                title: "",
                render: function (data, type, row, meta) {
                    let el = `
                        <button class="btn btn-primary btn-edit-positions" data-id="${data}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-danger btn-delete-positions" data-id="${data}"><i class="fa fa-trash"></i></button>
                    `;

                    // munculkan selain admin
                    if (row['jabatan'] == 'admin') {
                        el = '';
                    }
                    return  el;
                },
                className: "text-center",
            }
        ],
        headerCallback: function (thead, data, start, end, display) {
            $(thead).find('th').css('text-align', 'center');
            $(thead).find('th').addClass('bg-secondary-subtle');
        },
        destroy: true,
        responsive: true,
        autoWidth: false,
        searching: false, // Aktifkan pencarian
        paging: true, // Aktifkan paginasi
    });
}

function initialTableAccess() {
    $('.table-access').DataTable({
        ajax: "./routes/api.php?route=access",
        info: false,
        paging: false,
        columns: [
            { 
                data: null,
                title: "NO",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                className: "text-center",
                width: "5%"
            },
            { 
                data: "menu",
                title: "Menu"
            },
            {
                data: null,
                title: "Full Access",
                render: function (data, type, row, meta) {
                    let checked = '';
                    
                    if (row['read'] && row['create'] && row['update'] && row['delete']) {
                        checked = "checked";
                    }
                    let el = `<input class="form-check-input check-all" type="checkbox" data-menu="${row['menu']}" name="all-${row['menu']}" ${checked}>`;
                    return el;
                },
                className: "text-center"
            },
            {
                data: 'read',
                title: "Read",
                render: function (data, type, row, meta) {
                    let checked = '';
                    if (data || (row['create'] || row['update'] || row['delete'])) {
                        checked = "checked";
                    }
                    let el = `<input class="form-check-input read-only check-${row['menu']}" data-menu="${row['menu']}" type="checkbox" name="read-${row['menu']}" ${checked}>`;
                    return el;
                },
                className: "text-center"
            },
            {
                data: 'create',
                title: "Create",
                render: function (data, type, row, meta) {
                    let checked = '';
                    if (data) {
                        checked = "checked";
                    }
                    let el = `<input class="form-check-input check-crud check-${row['menu']}" type="checkbox" data-menu="${row['menu']}" name="create-${row['menu']}" ${checked}>`;
                    return el;
                },
                className: "text-center"
            },
            {
                data: 'update',
                title: "Update",
                render: function (data, type, row, meta) {
                    let checked = '';
                    if (data) {
                        checked = "checked";
                    }
                    let el = `<input class="form-check-input check-crud check-${row['menu']}" type="checkbox" data-menu="${row['menu']}" name="update-${row['menu']}" ${checked}>`;
                    return el;
                },
                className: "text-center"
            },            
            {
                data: 'delete',
                title: "Delete",
                render: function (data, type, row, meta) {
                    let checked = '';
                    if (data) {
                        checked = "checked";
                    }
                    let el = `<input class="form-check-input check-crud check-${row['menu']}" type="checkbox" data-menu="${row['menu']}" name="delete-${row['menu']}" ${checked}>`;
                    return el;
                },
                className: "text-center"
            },
        ],
        initComplete: function () {
            let check_all = $('.check-all');
            // jika check all di klik
            $('.check-all').on('click', function() {
                let menu = $(this).data('menu');
                let value = $(this).is(':checked');
                // jika checked
                if (value) {
                    $(`.check-${menu}`).prop('checked', true);
                }else{
                    $(`.check-${menu}`).prop('checked', false);
                }
            });
            // jika check read di klik
            $('.read-only').on('click', function() {
                let menu = $(this).data('menu');
                let value = $(this).is(':checked');
                // jika checked
                if (!value) {
                    $(`.check-${menu}`).prop('checked', false);
                }
            });
            
            // jika check crud diklik
            $('.check-crud').on('click', function() {
                let menu = $(this).data('menu');
                let stsRead = false;
                $(`.check-${menu}:not(.read-only)`).each(function(i, el) {
                    if ($(el).is(':checked')) {
                        stsRead = true;
                    }
                });
                // jika checked maka read akan tercheck
                if (stsRead) {
                    $(`.read-only.check-${menu}`).prop('checked', true);
                }else{
                    $(`.read-only.check-${menu}`).prop('checked', false);
                }
            });
        },
        headerCallback: function (thead, data, start, end, display) {
            $(thead).find('th').css('text-align', 'center');
            $(thead).find('th').addClass('bg-secondary-subtle');
        },
        destroy: true,
        responsive: true,
        autoWidth: false,
        searching: false, // Aktifkan pencarian
        paging: false, // Aktifkan paginasi
    });
}

// dom menggunakan jquery
$(document).ready(function() {
    initialTablePositions();
    initialTableAccess();

    $('.btn-add-positions').on('click', function() {
        initialTableAccess();
        $('#nama-position').val('');
        $('.positions').hide();
        $('.positions-form').show();
    });

    $('.btn-back-positions').on('click', function() {
        $('.positions-form').hide();
        $('.positions').show();
    });

    $('.btn-save-positions').on('click', function() {
        let check_menu = $('.form-check-input');
        let dataJson = {};

        check_menu.each(function() {
            let menu = $(this).data('menu'); // Ambil data-menu
            let name = $(this).attr('name'); // Ambil atribut name
            let access = name.split('-')[0]; // Ambil bagian sebelum "-"

            // Pastikan dataJson[menu] ada, jika belum inisialisasi sebagai array
            if (!dataJson[menu]) {
                dataJson[menu] = [];
            }
            if (access !== 'all') {
                // Tambahkan akses ke dalam array
                dataJson[menu].push({
                    access: access,  // Simpan jenis akses (read, create, update, delete)
                    value: $(this).is(':checked') // Simpan nilai checkbox (biasanya 1/0 atau true/false)
                });
            }
        });

        console.log(dataJson);
    });

});

</script>
<?php
require './pages/templates/footer.php';
?>