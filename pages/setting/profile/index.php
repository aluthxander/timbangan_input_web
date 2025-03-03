<?php
require './pages/templates/header.php';
?>
<style>
    .form-control {
        text-align: center;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: #aaa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .form-control:readonly {
        background-color: #f9f9f9;
        cursor: not-allowed;
    }
</style>
<div class="container d-flex justify-content-center align-items-center pt-2">
    <div class="card shadow-lg" style="max-width: 32rem; width: 100%;">
        <div class="card-header fs-4 fw-bold text-center text-bg-primary">
            PROFILE
        </div>
        <form id="form-profile" class="card-body px-5 pb-5">
            <input type="hidden" name="csrf_token" value="<?= $model['csrf'] ?>">
            <input type="hidden" name="username-old" value="<?= $model['data']['user']['username'] ?>">
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="username" class="fw-bold">Username</label>
                    <input type="text" class="form-control" name="username" id="username" value="<?= $model['data']['user']['username'] ?>" readonly <?= $model['data']['desc']['username'] ?>>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="name" class="fw-bold">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $model['data']['user']['name'] ?>" readonly <?= $model['data']['desc']['name'] ?>>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="email" class="fw-bold">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $model['data']['user']['email'] ?>" readonly <?= $model['data']['desc']['email'] ?>>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="phone" class="fw-bold">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $model['data']['user']['phone'] ?>" readonly <?= $model['data']['desc']['phone'] ?>>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary w-100 btn-edit-profile"><i class="fa fa-edit"></i> Edit</button>
                    <button type="button" class="btn btn-primary w-100 d-none btn-save-profile"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
let token = $('input[name="csrf_token"]').val();

$(document).ready(function() {
    $('.btn-edit-profile').click(function() {
        $('.btn-edit-profile').addClass('d-none');
        $('.btn-save-profile').removeClass('d-none');
        $('.form-control').removeAttr('readonly');
    });

    $('.btn-save-profile').click(function() {
        let form = $('#form-profile');
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
        
        $.ajax({
            url: './routes/api.php?route=profile',
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': token  // Tambahkan token ke header
            },
            data: JSON.stringify(jsonData),
            dataType: 'json',
            beforeSend: function() {
                $('.btn-save-profile').prop('disabled', true);
            },
            success: function(res) {
                if (res.status == 200) {
                    $('.btn-save-profile').prop('disabled', false);
                    $('.btn-edit-profile').removeClass('d-none');
                    $('.btn-save-profile').addClass('d-none');
                    $('.form-control').attr('readonly', true);
                    $('.form-control').removeClass('is-invalid');
                    // change username old to new username
                    $('input[name="username-old"]').val(res.username);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    });
                } else if (res.status == 400) {
                    // take all error message and show in alert
                    let errors = res.errors;
                    let message = '';
                    for (let key in errors) {
                        message += errors[key] + '<br>';
                        $(`.form-profile #${key}`).addClass('is-invalid');
                    }
                    Swal.fire({
                        icon: 'warning',
                        title: 'Failed',
                        html: message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('.btn-save-profile').prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Update Profile Failed',
                });
                $('.btn-save-profile').prop('disabled', false);
            }
        })
        // send data to api
    });
})
</script>
<?php
require './pages/templates/footer.php';
?>