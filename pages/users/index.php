<?php
require './pages/templates/header.php';
?>
<div class="users">
    <div class="row pt-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold">Users</h1>
            <div class="d-flex">
                <?php
                if ($_SESSION['user']['jabatan_id'] == 1) {
                    echo "<button class='btn btn-danger btn-delete-permanent'>
                            <i class='fas fa-trash'></i> Delete Permanent 
                        </button>";
                }
                ?>
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
<form class="users-form pt-5" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= $model['csrf'] ?>">
    <input type="hidden" name="id-user" id="id-user">
    <div class="row">
        <div class="col-12 d-flex justify-content-start align-items-center">
            <h1 class="fw-bold">Add New User</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="namauser" class="form-label">Name</label>
                <input type="text" class="form-control" id="namauser" name="namauser" <?= $model['data']['desc']['name'] ?>>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" <?= $model['data']['desc']['username'] ?>>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" <?= $model['data']['desc']['email'] ?>>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="phone" class="form-label">No. Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" <?= $model['data']['desc']['phone'] ?>>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="mb-3 form-password">
                <label for="password" class="form-label">Password</label>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" <?= $model['data']['desc']['password'] ?>>
                    <span class="input-group-text button-password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-3">
                <label for="jabatan" class="form-label">Position</label>
                <select name="jabatan" id="jabatan" class="form-select w-25" <?= $model['data']['desc']['jabatan_id'] ?>>
                    <option value=""></option>
                    <?php
                    foreach ($model['data']['jabatan'] as $value) {
                        $value['jabatan'] = ucfirst($value['jabatan']);
                        echo "<option value='{$value['id']}'>{$value['jabatan']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-end">
            <button type="button" class="btn btn-secondary btn-back-users me-2">
                <i class="fa fa-chevron-left"></i> Back
            </button>
            <button type="button" class="btn btn-primary btn-save-users">
                <i class="fa fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-primary btn-update-users d-none">
                <i class="fa fa-save"></i> Edit
            </button>
        </div>
    </div>
</form>
<script>
let token = $('input[name="csrf_token"]').val();

function initialTableUsers() {
    let cofigTable = {
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
            { 
                data: "id",
                title: "",
                render: function (data, type, row, meta) {
                    let el = ``;

                    if (row.accessby == 1) {
                        el += `<button class="btn btn-sm btn-primary me-2" onclick="change_password(${row.id})"><i class="fa fa-key"></i></button>`;
                    }

                    if (row.edit) {
                        el += `<button class="btn btn-sm btn-primary me-2" onclick="edit_users(${row.id})"><i class="fa fa-edit"></i></button>`;
                    }

                    if (row.delete) {
                        el += `<button class="btn btn-sm btn-danger me-2" onclick="delete_users(${row.id})"><i class="fa fa-trash"></i></button>`;
                    }

                    return el;
                },
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
    };

    $('.table-users').DataTable(cofigTable);
}

function delete_users(params) {
    Swal.fire({
        title: "Delete User",
        text: "Are you sure want to delete this user ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: () => {
            return Swal.fire({
                title: "Enter your password",
                input: "password",
                inputPlaceholder: "Enter your password",
                showCancelButton: false,
                confirmButtonText: "Submit",
                }).then((result) => {
                    // send password to server for verification
                    $.ajax({
                        'url': './routes/api.php?route=verify-status',
                        'headers': {
                            'X-CSRF-TOKEN': token
                        },
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token  // Tambahkan token ke header
                        },
                        data: {
                            password : result.value,
                            id : params
                        },
                        dataType: 'json',
                        success: function(res) {
                            // jika response 200
                            if (res.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                initialTableUsers();
                            }else if (res.status == 400) {
                                // take all error message and show in alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error(xhr){
                            console.error(xhr.responseText);
                        }
                    });
                });
        },
    });
}

function validatePassword(password) {
    let errorPass = { status: true, message: 'Password is valid' };

    if (!/[A-Z]/.test(password)) {
        errorPass =  { status: false, message: 'Password must contain at least one uppercase letter' };
    }else if (!/[a-z]/.test(password)) {
        errorPass =  { status: false, message: 'Password must contain at least one lowercase letter' };
    }else if (!/[0-9]/.test(password)) {
        errorPass =  { status: false, message: 'Password must contain at least one number' };
    }else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        errorPass =  { status: false, message: 'Password must contain at least one special character' };
    }else if (password.length < 8) {
        errorPass =  { status: false, message: 'Password must be at least 8 characters' };
    }
    
    if (!errorPass['status']) {
        $('#confirmPassword-error').removeClass('d-none').text(errorPass['message']);
        $('.swal2-confirm').prop('disabled', true);
    }else{
        $('#confirmPassword-error').addClass('d-none').text('');
        $('.swal2-confirm').prop('disabled', false);
    }
}

function change_password(params) {
    Swal.fire({
        title: "Change Password",
        text: "Are you sure want to change password this user ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        preConfirm: () => {
            return Swal.fire({
                title: "Enter your password",
                input: "password",
                inputPlaceholder: "Enter your password",
                showCancelButton: false,
                confirmButtonText: "Submit",
                }).then((result) => {
                    // send password to server for verification
                    $.ajax({
                        'url': './routes/api.php?route=verify-admin',
                        'headers': {
                            'X-CSRF-TOKEN': token
                        },
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token  // Tambahkan token ke header
                        },
                        data: {
                            password : result.value,
                            id : params
                        },
                        dataType: 'json',
                        success: function(res) {
                            // jika response 200
                            if (res.status == 200) {
                                Swal.fire({
                                    title : 'Change Password',
                                    html: `
                                        <div class="swal2-input-group">
                                            <input id="passwordChange" type="password" onkeyup="validatePassword(this.value)" placeholder="Enter your password" class="swal2-input">
                                            <input id="confirmPassword" type="password" placeholder="Confirm your password" class="swal2-input">
                                            <div id="confirmPassword-error" class="swal2-validation-message d-none"></div>
                                        </div>
                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: "Submit",
                                    showLoaderOnConfirm: true,
                                    allowOutsideClick: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        let passwordChange = document.getElementById('passwordChange').value;
                                        let confirmPassword = document.getElementById('confirmPassword').value;
                                        $.ajax({
                                            url: './routes/api.php?route=change-password',
                                            headers: {
                                                'X-CSRF-TOKEN': token
                                            },
                                            type: 'POST',
                                            data: {
                                                id : params,
                                                password : passwordChange,
                                                confirm_password : confirmPassword
                                            },
                                            dataType: 'json',
                                            success: function(res) {
                                                // jika response 200
                                                if (res.status == 200) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Success',
                                                        text: res.message,
                                                        showConfirmButton: false,
                                                        timer: 2000
                                                    });
                                                }else {
                                                    // take all error message and show in alert
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Failed',
                                                        text: res.message
                                                    });
                                                }
                                            },
                                            error(xhr){
                                                console.error(xhr.responseText);
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Failed',
                                                    text: 'Change Password User Failed'
                                                });
                                            }
                                        });
                                    }
                                });
                            }else{
                                // take all error message and show in alert
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error(xhr){
                            console.error(xhr.responseText);
                        }
                    });
                });
        },
        
    })
}

function edit_users(params) {
    $.ajax({
        url: './routes/api.php?route=user',
        type: 'GET',
        data: {
            id : params
        },
        dataType: 'json',
        success: function(res) {
            if (res.status == 200) {
                $('.users').hide();
                $('.users-form').show();
                $('.btn-save-users').addClass('d-none');
                $('.btn-update-users').removeClass('d-none');
                $('.users-form h1').text('Edit User');
                $('.users-form').removeClass('was-validated');
                $(`input.is-invalid`).removeClass('is-invalid');
                $(`select.is-invalid`).removeClass('is-invalid');
                // add d-none to password form
                $('.form-password').addClass('d-none');
                $('#password').prop('disabled', true);
                
                let data = res.data;
                // set value to form
                $('#id-user').val(params);
                $('#namauser').val(data.name);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
                $('#jabatan').val(data.jabatan_id);
                
            }else{
                console.error(res.message);
            }
        },
        error(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// dom menggunakan jquery
$(document).ready(function() {
    initialTableUsers();

    $('.button-password').on('click', function() {
        let password = document.getElementById('password');
        password.type = password.type === 'password' ? 'text' : 'password';
        // chenge icon
        $(this).find('i').toggleClass('fa-eye');
        $(this).find('i').toggleClass('fa-eye-slash');
    });

    $('.btn-add-users').on('click', function() {
        $('.users').hide();
        $('.users-form').show();
        $('.btn-save-users').removeClass('d-none');
        $('.btn-update-users').addClass('d-none');
        $('.users-form h1').text('Add New User');
        $('.users-form').removeClass('was-validated');
        $(`input.is-invalid`).removeClass('is-invalid');
        $(`select.is-invalid`).removeClass('is-invalid');
        // set value to form
        $('#id-user').val('');
        $('#namauser').val('');
        $('#username').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#jabatan').val('');

        $('.form-password').removeClass('d-none');
        $('#password').prop('disabled', false);
    });

    $('.btn-back-users').on('click', function() {
        $('.users-form').hide();
        $('.users').show();
    });

    // save user
    $('.btn-save-users').on('click', function() {
        let form = $('.users-form');
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
            url: './routes/api.php?route=users',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': token  // Tambahkan token ke header
            },
            data: JSON.stringify(jsonData),
            dataType: 'json',
            beforeSend: function() {
                $('.btn-save-users').prop('disabled', true);
            },
            success: function(res) {
                $('.btn-save-users').prop('disabled', false);
                if (res.status == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    initialTableUsers();
                    $('.users-form').hide();
                    $('.users').show();
                }else if (res.status == 400) {
                    // take all error message and show in alert
                    let errors = res.errors;
                    let message = '';
                    for (let key in errors) {
                        message += errors[key] + '<br>';
                        $(`.users-form #${key}`).addClass('is-invalid');
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
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Save User Failed',
                });
                $('.btn-save-users').prop('disabled', false);
            }
        });
    });

    // update user
    $('.btn-update-users').on('click', function() {
        let form = $('.users-form');
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
            url: './routes/api.php?route=users',
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token  // Tambahkan token ke header
            },
            data: JSON.stringify(jsonData),
            dataType: 'json',
            beforeSend: function() {
                $('.btn-update-users').prop('disabled', true);
            },
            success: function(res) {
                $('.btn-update-users').prop('disabled', false);
                if (res.status == 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    initialTableUsers();
                    $('.users-form').hide();
                    $('.users').show();
                }else if (res.status == 400) {
                    // take all error message and show in alert
                    let errors = res.errors;
                    let message = '';
                    for (let key in errors) {
                        message += errors[key] + '<br>';
                        $(`.users-form #${key}`).addClass('is-invalid');
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
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Update User Failed',
                });
                $('.btn-update-users').prop('disabled', false);
            }
        });
    });

<?php
if ($_SESSION['user']['jabatan_id'] == 1) {?>
    // delete permanent
    $('.btn-delete-permanent').on('click', function() {
        Swal.fire({
            title: "Delete Permanent User",
            text: "Delete permanently user who has been temporarily deleted ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: () => {
                return Swal.fire({
                    title: "Enter your password",
                    input: "password",
                    inputPlaceholder: "Enter your password",
                    showCancelButton: false,
                    confirmButtonText: "Submit",
                    }).then((result) => {
                        // send password to server for verification
                        $.ajax({
                            'url': './routes/api.php?route=delete-permanently',
                            'headers': {
                                'X-CSRF-TOKEN': token
                            },
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token  // Tambahkan token ke header
                            },
                            data: {
                                password : result.value,
                            },
                            dataType: 'json',
                            success: function(res) {
                                // jika response 200
                                if (res.status == 200) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }else if (res.status == 400) {
                                    // take all error message and show in alert
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            },
                            error(xhr){
                                console.error(xhr.responseText);
                            }
                        });
                    });
                    
            },
        });
    });
<?php
}
?>
});

</script>
<?php
require './pages/templates/footer.php';
?>