<?php
require './pages/templates/header.php';
?>
<div class="items">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Items</h1>
            <div class="d-flex">
                <?php
                foreach ($_SESSION['user']['access'] as $menu) {
                    if ($menu['menu'] == 'items' && $menu['create_access']) {
                        echo '<button class="btn btn-primary btn-add-items ms-2">
                                <i class="fas fa-plus"></i> Add Item
                            </button>';
                    }
                }
                ?>
                <button class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <!-- <button class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-filter"></i> Filter
                </button> -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-items"></table>
        </div>
    </div>
</div>
<form class="items-form pt-5" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $model['csrf'] ?>">
    <input type="hidden" name="id-item" id="id-item">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New Item</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="kodebarang" class="form-label">Item Code</label>
                <input type="text" class="form-control" id="kodebarang" name="kodebarang" <?= $model['data']['desc']['code'] ?>>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="namabarang" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="namabarang" name="namabarang" <?= $model['data']['desc']['name'] ?>>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="stylebarang" class="form-label">Style</label>
                <input type="text" class="form-control" id="stylebarang" name="stylebarang" <?= $model['data']['desc']['style'] ?>>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="sizebarang" class="form-label">Size</label>
                <select name="sizebarang" id="sizebarang" class="form-select w-25" <?= $model['data']['desc']['size'] ?>>
                    <?php
                    foreach ($model['data'] as $value) {
                        echo "<option>{$value['size']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="beratmin" class="form-label">Min. Weight</label>
                <input type="text" class="form-control" placeholder="0.0000" id="beratmin" name="beratmin" <?= $model['data']['desc']['weight_min'] ?>>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="beratmax" class="form-label">Max Weight</label>
                <input type="text" class="form-control" placeholder="0.0000" id="beratmax" name="beratmax" <?= $model['data']['desc']['weight_max'] ?>>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <button type="button" class="btn btn-secondary btn-back-items me-2">
                <i class="fa fa-chevron-left"></i> Back
            </button>
            <button type="button" class="btn btn-primary btn-save-items">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</form>
<script>
let token = $('input[name="csrf_token"]').val();

function initialTableItems() {
    $('.table-items').DataTable({
        ajax: "./routes/api.php?route=items",
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
                data: 'code',
                title: 'Item Code',
                render: function (data, type, row, meta) {
                    return `<div class="text-start">${data}</div>`
                }
            },
            {
                data: 'name',
                title: 'Item Name',
            },
            {
                data: 'style',
                title: 'Style'
            },
            {
                data: 'size',
                title: 'Size',
                className: 'text-center'
            },
            {
                data: 'weight_min',
                title: 'Min Weight',
                className: 'text-center'
            },
            {
                data: 'weight_max',
                title: 'Max Weight',
                className: 'text-center'
            },
            {
                data: null,
                title: '',
                className: 'text-center',
                render: function (data, type, row, meta) {
                    let el = '';
                    if (row.edit) {
                        el += `<button class="btn btn-sm btn-primary me-2" onclick="edit_items(${row.id})"><i class="fa fa-edit"></i></button>`;
                    }

                    if (row.delete) {
                        el += `<button class="btn btn-sm btn-danger me-2" onclick="delete_items(${row.id})"><i class="fa fa-trash"></i></button>`;
                    }

                    return el;
                }
            },
        ],
        headerCallback: function (thead, data, start, end, display) {
            $(thead).find('th').css('text-align', 'center');
            $(thead).find('th').addClass('bg-secondary-subtle');
        },
        destroy: true,
        responsive: true,
        autoWidth: false,
    });
}

let beratMin = document.getElementById('beratmin');
let beratMax = document.getElementById('beratmax');
// input dari kanan ke kiri
let beratMinMask = new IMask(beratMin, {
    mask: Number,
    scale: 4,
    signed: false,
    thousandsSeparator: '.'
});
let beratMaxMask = new IMask(beratMax, {
    mask: Number,
    scale: 4,
    signed: false,
    thousandsSeparator: '.'
});
// dom menggunakan jquery
$(document).ready(function() {
    initialTableItems();

    $('.btn-add-items').on('click', function() {
        $('.items').hide();
        $('.items-form').show();
        $('.items-form .row h1').text('Add New Item');
        $('.items-form').removeClass('was-validated');
        $(`input.is-invalid`).removeClass('is-invalid');
        $(`select.is-invalid`).removeClass('is-invalid');
        // set value to form
        $('#kodebarang').val('');
        $('#namabarang').val('');
        $('#stylebarang').val('');
        $('#sizebarang').val('');
        $('#beratmin').val('');
        $('#beratmax').val('');
    });

    $('.btn-back-items').on('click', function() {
        $('.items-form').hide();
        $('.items').show();
    });

    $('.btn-save-items').on('click', function() {
        let form = $('.items-form');
        // check field required is filled
        if (!form.get(0).checkValidity()) {
            form.addClass('was-validated');
            return false;
        }
        // take all value and key from form to json
        let data = form.serializeArray();
        let jsonData = {};
        $.each(data, function(index, value) {
            jsonData[value.name] = value.value;
        });

        // send data to api
        $.ajax({
            url: "./routes/api.php?route=items",
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: "JSON",
            data: jsonData,
            beforeSend: function() {
                $('.btn-save-items').prop('disabled', true);
                $('.btn-save-items').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            },
            success: function(res) {
                $('.btn-save-items').prop('disabled', false);
                $('.btn-save-items').html('<i class="fa fa-save"></i> Save');
                if (res.status == 200) {
                    $('.items-form').hide();
                    $('.items').show();
                    initialTableItems();
                }else if (res.status == 400) {
                    // take all error message and show in alert
                    let errors = res.errors;
                    let message = '';
                    for (let key in errors) {
                        message += errors[key] + '<br>';
                        $(`.items-form #${key}`).addClass('is-invalid');
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Failed',
                        html: message
                    });
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: res.message,
                    });
                }
            },
            error: function(response) {
                console.log(response);
                $('.btn-save-items').prop('disabled', false);
                $('.btn-save-items').html('<i class="fa fa-save"></i> Save');
            }
        });
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>