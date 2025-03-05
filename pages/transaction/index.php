<?php
require './pages/templates/header.php';
?>
<div class="transaction">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Transaction</h1>
            <div class="d-flex">
                <?php
                foreach ($_SESSION['user']['access'] as $menu) {
                    if ($menu['menu'] == 'transactions' && $menu['create_access']) {
                        echo '<button class="btn btn-primary btn-add-transaction">
                                <i class="fas fa-plus"></i> Add Transaction
                            </button>';
                    }
                }
                ?>
                <button class="btn btn-outline-secondary btn-export ms-2">
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
            <table class="table table-bordered table-transaction"></table>
        </div>
    </div>
</div>
<div class="transaction-form pt-5" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $model['csrf'] ?>">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New Transaction</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <label for="weight" class="form-label">Weight</label>
            <input type="text" class="form-control" id="weight" name="weight" readonly>
        </div>
        <div class="col-sm-6 d-flex align-items-end">
            <button type="button" class="btn btn-primary btn-calculation me-2">Calculation</button>
            <button type="button" class="btn btn-secondary btn-back-transaction"><i class="fa fa-chevron-left"></i> Back</button>
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered table-transaction-detail"></table>
    </div>
</div>
<script>
let token = $('input[name="csrf_token"]').val();

function initialTableTransaction() {
    $('.table-transaction').DataTable({
        ajax: "./routes/api.php?route=transaction",
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
                data: 'code_item', 
                title: 'Item Code',
                render: function (data, type, row, meta) {
                    return `<div class="text-start">${data}</div>`
                }
            },
            { 
                data: 'name_item', 
                title: 'Item Name',
            },
            { 
                data: 'style_item', 
                title: 'Style' 
            },
            { 
                data: 'size_item', 
                title: 'Size',
                className: 'text-center'
            },
            { 
                data: 'weight_item', 
                title: 'Weight',
                className: 'text-center'
            },
            { 
                data: 'id', 
                title: null,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    if (row.delete) {
                        return `<button class="btn btn-sm btn-danger btn-delete-transaction" onclick="deleteTransaction(${data})"><i class="fas fa-trash"></i></button>`;
                    }else{
                        return '';
                    }
                }
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
}

async function exportExcel() {
	let thead1 = $(".table-transaction thead tr th").map(function() {
                    let text = $(this).text().trim(); // Ambil teks dan hapus spasi berlebih
					if (text != '') {
                        return text;
                    }
				}).get();
    
	// Tambahkan Header ke Excel
	// ubah array2 menjadi excel
	let workbook = new ExcelJS.Workbook();
	let worksheet = workbook.addWorksheet('Transaction');

	let baris1 = 1;
	let indexHdr = 1;
	// cek apakah tanggalawal sama dengan tanggal akhir
    // merge cell
    worksheet.mergeCells(baris1, indexHdr, baris1, indexHdr + thead1.length - 1);
    worksheet.getCell(baris1, indexHdr).value = "TRANSACTION REPORT"; // Rata tengah
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
	let table = $('.table-transaction').DataTable();
    let data = table.data('all');
    let dataArray = data.toArray();
    
    dataArray.forEach((row, index) => {
        worksheet.addRow([
            index + 1,
            row.code_item,
            row.name_item,
            row.style_item,
            row.size_item,
            row.weight_item
        ]);
    });

	// menentukan ukuran kolom
	worksheet.getColumn(1).width = 5; // Kolom A
	worksheet.getColumn(1).alignment = { horizontal: "center", vertical: "middle" }; // Kolom A
	worksheet.getColumn(2).width = 15; // Kolom A
	worksheet.getColumn(3).width = 35; // Kolom A
	worksheet.getColumn(4).width = 15; // Kolom A
	worksheet.getColumn(5).width = 15; // Kolom A
    worksheet.getColumn(5).alignment = { horizontal: "center", vertical: "middle" }; // Kolom A
	worksheet.getColumn(6).width = 15; // Kolom A
    worksheet.getColumn(6).alignment = { horizontal: "center", vertical: "middle" }; // Kolom A
	
	// Simpan sebagai Blob
	let buffer = await workbook.xlsx.writeBuffer();
	let blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });

	// Buat Link untuk Download
	let link = document.createElement("a");
	link.href = URL.createObjectURL(blob);
	link.download = "transaction_report.xlsx";
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function itemCheckByWeight(params){
    $('.table-transaction-detail').DataTable({
        ajax: '/routes/api.php?route=transaction-item&weight='+params,
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
                data: null,
                title: null,
                render: function (data, type, row, meta) {
                    let json = encodeURIComponent(JSON.stringify(row));
                    return `<button type="button" class="btn btn-sm btn-primary" onclick="itemCheck('${json}')"><i class="fa fa-check"></i></button>`;
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
            }
        ],
        headerCallback: function (thead, data, start, end, display) {
            $(thead).find('th').css('text-align', 'center');
            $(thead).find('th').addClass('bg-secondary-subtle');
        },
        destroy: true,
        responsive: true,
        autoWidth: false,
        displayLength: 5
    });
}

function itemCheck(data) {
    let jsonData = JSON.parse(decodeURIComponent(data));
    let weight = $('#weight').val();
    jsonData['weight'] = weight;
    
    $.ajax({
        url: '/routes/api.php?route=transaction',
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': token
        },
        data: jsonData,
        success: function(res) {
            console.log(res);
            if (res.status == 200) {
                $('.transaction-form').hide();
                $('.transaction').show();
                initialTableTransaction();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message
                });
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: res.message
                });
            }
        },
        error: function(xhr, status, err) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: "internal server error"
            })
        }
    });
}

function deleteTransaction(id) {
    Swal.fire({
        title: 'Delete Transaction',
        text: "Are you sure want to delete this transaction?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/routes/api.php?route=transaction',
                method: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': token
                },
                data: JSON.stringify({id: id}),
                success: function(res) {
                    if (res.status == 200) {
                        initialTableTransaction();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: res.message
                        });
                    }
                },
                error: function(xhr, status, err) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: "internal server error"
                    })
                }
            });
        }
    });
}
// dom menggunakan jquery
$(document).ready(function() {
    initialTableTransaction();

    $('.btn-add-transaction').on('click', function() {
        $('.transaction').hide();
        $('.transaction-form').show();
        $('#weight').val('');
        itemCheckByWeight('');
    });

    $('.btn-back-transaction').on('click', function() {
        $('.transaction-form').hide();
        $('.transaction').show();
    });

    $('.btn-calculation').on('click', function() {
        $.ajax({
            url: '/routes/api.php?route=timbangan',
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.btn-calculation').prop('disabled', true);
                $('.btn-calculation').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading...');
            },
            complete: function(res) {
                $('.btn-calculation').prop('disabled', false);
                $('.btn-calculation').html('Calculation');
                let response = res.responseText;
                $('#weight').val(response);
                itemCheckByWeight(response);
            }
        })
    });

    $('.btn-export').on('click', function() {
        exportExcel();
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>