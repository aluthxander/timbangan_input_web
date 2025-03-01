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
                <button class="btn btn-outline-secondary ms-2 btn-export">
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
            <button type="button" class="btn btn-primary btn-update-items d-none">
                <i class="fa fa-edit"></i> Edit
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

function delete_items(params) {
    Swal.fire({
        icon: 'warning',
        title: 'Delete Item',
        text: "Are you sure want to delete this item ?",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "./routes/api.php?route=items&id=" + params,
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                dataType: "JSON",
                success: function(res) {
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message
                        });

                        initialTableItems();
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: res.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Something went wrong',
                    });
                }
            });
        }
    });
}

function edit_items(params) {
    $.ajax({
        url: "./routes/api.php?route=item&id=" + params,
        method: "GET",
        dataType: "JSON",
        success: function(res) {
            if (res.status == 200) {
                $('.items').hide();
                $('.items-form').show();
                $('.items-form .row h1').text('Edit Item');
                $('.btn-save-items').addClass('d-none');
                $('.btn-update-items').removeClass('d-none');
                $('.items-form').removeClass('was-validated');
                $(`input.is-invalid`).removeClass('is-invalid');
                $(`select.is-invalid`).removeClass('is-invalid');
                // set value to form
                $('#id-item').val(res.data.id);
                $('#kodebarang').val(res.data.code);
                $('#namabarang').val(res.data.name);
                $('#stylebarang').val(res.data.style);
                $('#sizebarang').val(res.data.size);
                $('#beratmin').val(res.data.weight_min);
                $('#beratmax').val(res.data.weight_max);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Something went wrong',
            });
        }
    });
}

async function exportExcel() {
	let thead1 = $(".table-items thead tr th").map(function() {
                    let text = $(this).text().trim(); // Ambil teks dan hapus spasi berlebih
					if (text != '') {
                        return text;
                    }
				}).get();
    
	// Tambahkan Header ke Excel
	// ubah array2 menjadi excel
	let workbook = new ExcelJS.Workbook();
	let worksheet = workbook.addWorksheet('Item');

	let baris1 = 1;
	let indexHdr = 1;
	// cek apakah tanggalawal sama dengan tanggal akhir
    // merge cell
    worksheet.mergeCells(baris1, indexHdr, baris1, indexHdr + thead1.length - 1);
    worksheet.getCell(baris1, indexHdr).value = "ITEMS REPORT"; // Rata tengah
    baris1++;
	// looping untuk membuat merge header
	thead1.forEach(function (value, index) {
		// jika index = index.length (mengambil index terakhir) maka colspan
        worksheet.getCell(baris1, indexHdr).value = value; // Rata tengah
        worksheet.getCell(baris1, indexHdr).alignment = { horizontal: "center", vertical: "middle" }; // Rata tengah
		indexHdr++;
	});
	// memasukan header ke 2
	
	// Buat Style Header
	let rowHeader = [baris1];
	rowHeader.forEach((rowNumber) => {
		worksheet.getRow(rowNumber).eachCell((cell) => {
			cell.font = { bold: true };
			cell.fill = {
				type: 'pattern',
				pattern: 'solid',
				fgColor: { argb: '4B8DF8' } // Warna Kuning
			};
			// ubah warna text menjadi putih
			cell.font = { color: { argb: 'FFFFFF' } };
	
			// Tambahkan border pada setiap sel header
			cell.border = {
				top: { style: 'thin' },
				left: { style: 'thin' },
				bottom: { style: 'thin' },
				right: { style: 'thin' }
			};
		});
	});

	// ambil data dari datatable
	let table = $('.table-items').DataTable();
    let data = table.data('all');
    let dataArray = data.toArray();
    
    dataArray.forEach(row => {
        worksheet.addRow([
            row.id,
            row.code,
            row.name,
            row.style,
            row.size,
            row.weight_min,
            row.weight_max
        ]);
    });

	// menentukan ukuran kolom
	worksheet.getColumn(1).width = 5; // Kolom A
	worksheet.getColumn(1).alignment = { horizontal: "center", vertical: "middle" }; // Kolom A
	worksheet.getColumn(2).width = 35; // Kolom A
	worksheet.getColumn(3).width = 15; // Kolom A
	worksheet.getColumn(4).width = 15; // Kolom A
	worksheet.getColumn(5).width = 18; // Kolom A
	worksheet.getColumn(6).width = 18; // Kolom A
	worksheet.getColumn(7).width = 18; // Kolom A
	
	// Simpan sebagai Blob
	let buffer = await workbook.xlsx.writeBuffer();
	let blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

	// Buat Link untuk Download
	let link = document.createElement("a");
	link.href = URL.createObjectURL(blob);
	link.download = "item_report.xlsx";
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
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
        $('.btn-save-items').removeClass('d-none');
        $('.btn-update-items').addClass('d-none');
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
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('.btn-save-items').prop('disabled', false);
                $('.btn-save-items').html('<i class="fa fa-save"></i> Save');
            }
        });
    });

    $('.btn-update-items').on('click', function() {
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
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: "JSON",
            data: JSON.stringify(jsonData),
            beforeSend: function() {
                $('.btn-update-items').prop('disabled', true);
                $('.btn-update-items').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            },
            success: function(res) {
                $('.btn-update-items').prop('disabled', false);
                $('.btn-update-items').html('<i class="fa fa-edit"></i> Edit');
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
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('.btn-update-items').prop('disabled', false);
                $('.btn-update-items').html('<i class="fa fa-edit"></i> Edit');
            }
        });
    });

    $('.btn-export').on('click', function() {
        exportExcel();
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>