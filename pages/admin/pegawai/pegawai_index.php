<?php include "./pages/admin/navbar_admin.php"?>

<!--Container Main start-->
<div class="pegawai">
    <div class="row">
        <div class="col-12 text-center fs-1 fw-bold">
            Daftar Pegawai
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="button" id="btn-tambah" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal-pegawai"><i class="fas fa-plus me-2"></i>Tambah</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered" id="table-pegawai">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">NIP</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-pegawai" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Pegawai</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-pegawai" class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="example@gmail.com" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="form-select" placeholder="Jabatan" required>
                                <option></option>
                                <option>Manager</option>
                                <option>Staff</option>
                                <option>Programmer</option>
                                <option>Admin</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" name="nip" id="nip" placeholder="Nomor Induk Pegawai" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning" id="btn-save">Tambah Pegawai</button>
                    <button type="button" class="btn btn-warning" id="btn-edit" style="display: none;">Edit Pegawai</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./pages/templates/js.php";?>
