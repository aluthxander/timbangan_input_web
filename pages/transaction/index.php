<?php
require './pages/templates/header.php';
?>
<div class="transaction">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Transaction</h1>
            <div class="d-flex">
                <button class="btn btn-primary btn-add-transaction">
                    <i class="fas fa-plus"></i> Add Transaction
                </button>
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
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New Transaction</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="kodebarang" class="form-label">Item Code</label>
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
                <label for="stylebarang" class="form-label">Style</label>
                <input type="text" class="form-control" id="stylebarang" name="stylebarang">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="sizebarang" class="form-label">Size</label>
                <select name="sizebarang" id="sizebarang" class="form-select w-25">
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

// dom menggunakan jquery
$(document).ready(function() {
    initialTableTransaction();

    $('.btn-add-items').on('click', function() {
        $('.items').hide();
        $('.items-form').show();
    });

    $('.btn-back-items').on('click', function() {
        $('.items-form').hide();
        $('.items').show();
    });

    $('.btn-export').on('click', function() {
        exportExcel();
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>