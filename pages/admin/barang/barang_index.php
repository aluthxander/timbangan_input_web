<?php include "./pages/admin/navbar_admin.php"?>
<!--Container Main start-->
<div class="barang">
    <div class="row">
        <div class="col-12 text-center fs-1 fw-bold">
            Daftar Stock Barang
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="button" id="btn-tambah" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal-barang"><i class="fas fa-plus me-2"></i>Tambah</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="table-barang">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Harga Satuan</th>
                        <th class="text-center">Stok Barang</th>
                        <th class="text-center">Jumlah Terbeli</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-barang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-barang" class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="kodebrg" class="form-label">Kode Barang</label>
                            <input type="text" name="kodebrg" class="form-control" id="kodebrg" placeholder="Kode Barang" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="namabrg" class="form-label">Nama Barang</label>
                            <input type="text" name="namabrg" class="form-control" id="namabrg" placeholder="Nama Barang" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="hrgsatuan" class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control" name="hrgsatuan" id="hrgsatuan" placeholder="Rp. 0,00" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="stockbrg" class="form-label">Stock</label>
                            <input type="text" name="stockbrg" class="form-control" id="stockbrg" placeholder="Stock Barang" required>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="btn-save">Tambah Barang</button>
                    <button type="button" class="btn btn-warning" id="btn-update" style="display: none;">Edit Barang</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Container Main end-->
<?php include "./pages/templates/js.php";?>
