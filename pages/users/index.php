<?php
require './pages/templates/header.php';
?>
<div class="users">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Users</h1>
            <div class="d-flex">
                <button class="btn btn-primary btn-add-user">
                    <i class="fas fa-plus"></i> Add User
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
            <table class="table table-bordered table-users"></table>
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
// dom menggunakan jquery
$(document).ready(function() {
    initialTableUsers();
});

</script>
<?php
require './pages/templates/footer.php';
?>