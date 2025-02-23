<?php
require './pages/templates/header.php';
?>
<div class="users">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Users</h1>
            <div class="d-flex">
                <?php
                foreach ($_SESSION['user']['access'] as $menu) {
                    if ($menu['menu'] == 'users' && $menu['create_access']) {
                        echo '<button class="btn btn-primary btn-add-users ms-2">
                                <i class="fas fa-plus"></i> Add User
                            </button>';
                    }
                }
                ?>
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
            <table class="table table-bordered table-users"></table>
        </div>
    </div>
</div>
<div class="users-form pt-5" style="display: none;">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New User</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="namauser" class="form-label">Nama</label>
                <input type="text" class="form-control" id="namauser" name="namauser">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="phone" class="form-label">No. Telp</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select name="jabatan" id="jabatan" class="form-select w-25">
                    <option value=""></option>
                    <?php
                    foreach ($model['data'] as $value) {
                        echo "<option value='{$value['id']}'>{$value['jabatan']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <button class="btn btn-secondary btn-back-users me-2">
                <i class="fa fa-chevron-left"></i> Back
            </button>
            <button class="btn btn-primary btn-save-users">
                <i class="fa fa-save"></i> Save
            </button>
        </div>
    </div>
</div>
<script>
function initialTableUsers() {
    $('.table-users').DataTable({
        ajax: "./routes/api.php?route=users",
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
                data: "name",
                title: "Name" 
            },
            { 
                data: "username",
                title: "Username"
            },
            { 
                data: "email",
                title: "Email"
            },
            { 
                data: "jabatan",
                title: "Posistion",
                className: "text-center"
            },
            { 
                data: "phone",
                title: "Phone",
                className: "text-center"
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

let beratMin = document.getElementById('phone');
// input dari kanan ke kiri
let beratMinMask = new IMask(beratMin, {
    mask: Number,
    scale: 0,
    padFractionalZeros: true
});
// dom menggunakan jquery
$(document).ready(function() {
    initialTableUsers();

    $('.btn-add-users').on('click', function() {
        $('.users').hide();
        $('.users-form').show();
    });

    $('.btn-back-users').on('click', function() {
        $('.users-form').hide();
        $('.users').show();
    });
});

</script>
<?php
require './pages/templates/footer.php';
?>