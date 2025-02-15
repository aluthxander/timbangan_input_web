<?php
require './pages/templates/header.php';
?>
<div class="items">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Items</h1>
            <div class="d-flex">
                <button class="btn btn-primary btn-add-items">
                    <i class="fas fa-plus"></i> Add Item
                </button>
                <button class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <button class="btn btn-outline-secondary ms-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-items"></table>
        </div>
    </div>
</div>
<div class="items-form pt-5" style="display: none;">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New Item</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="kodebarang" class="form-label">Kode Barang</label>
                <input type="text" class="form-control" id="kodebarang" name="kodebarang">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="namabarang" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="namabarang" name="namabarang">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="stylebarang" class="form-label">Styke</label>
                <input type="text" class="form-control" id="stylebarang" name="stylebarang">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="sizebarang" class="form-label">Size</label>
                <select name="sizebarang" id="sizebarang" class="form-select w-25">
                    <?php
                    foreach ($model['data'] as $value) {
                        echo "<option>{$value}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="beratmin" class="form-label">Berat Min.</label>
                <input type="text" class="form-control" placeholder="0.0000" id="beratmin" name="beratmin">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="beratmax" class="form-label">Berat Max</label>
                <input type="text" class="form-control" placeholder="0.0000" id="beratmax" name="beratmax">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <button class="btn btn-secondary btn-back-items me-2">
                <i class="fa fa-chevron-left"></i> Back
            </button>
            <button class="btn btn-primary btn-save-items">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</div>
<script>
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
                title: 'Kode Barang',
                render: function (data, type, row, meta) {
                    return `<div class="text-start">${data}</div>`
                }
            },
            {
                data: 'name',
                title: 'Nama Barang'
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
                title: 'Berat Min',
                className: 'text-center'
            },
            {
                data: 'weight_max',
                title: 'Berat Max',
                className: 'text-center'
            }
        ],
        headerCallback: function (thead, data, start, end, display) {
            $(thead).find('th').css('text-align', 'center');
            $(thead).find('th').addClass('bg-secondary-subtle');
        },
        destroy: true,
        responsive: true,
        autoWidth: false,
    });
    /*
    {
    "name": "Baju Lutfan",
    "code": "123456",
    "id": 1,
    "size": "S",
    "style": "Lutfan",
    "weight_max": 2,
    "weight_min": 1
}
    */
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
    });

    $('.btn-back-items').on('click', function() {
        $('.items-form').hide();
        $('.items').show();
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>