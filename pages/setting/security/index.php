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
<div class="container d-flex justify-content-center align-items-center pt-5">
    <div class="card shadow-lg" style="max-width: 32rem; width: 100%;">
        <div class="card-header fs-4 fw-bold text-center text-bg-primary">
            CHANGE PASSWORD
        </div>
        <form id="form-profile" class="card-body px-5 pb-5">
            <input type="hidden" name="csrf_token" value="<?= $model['csrf'] ?>">
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="username" class="fw-bold">Previous Password</label>
                    <input type="password" class="form-control" name="old-password" id="old-password" required>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="username" class="fw-bold">New Password</label>
                    <input type="password" class="form-control" name="new-password" id="new-password" required>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <label for="name" class="fw-bold">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm-password" id="confirm-password" required>
                </div>
            </div>
            <div class="row text-center mt-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary w-100 btn-save-security"><i class="fa fa-save"></i> Change Password</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
let token = $('input[name="csrf_token"]').val();

$(document).ready(function() {

    $('.btn-save-security').click(function() {
        Swal.fire({
            title : 'Change Password',
            text : 'Are you sure want to change password?',
            icon : 'question',
            showCancelButton : true,
            confirmButtonText : 'Yes',
            cancelButtonText : 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                // input old password
                $.ajax({
                    url : './routes/api.php?route=security',
                    headers : {
                        'X-CSRF-TOKEN' : token
                    },
                    type : 'POST',
                    data : {
                        "old-password" : $('#old-password').val(),
                        "new-password" : $('#new-password').val(),
                        "confirm-password" : $('#confirm-password').val(),
                    },
                    beforeSend : function() {
                        $('.btn-save-security').prop('disabled', true);
                    },
                    dataType : 'json',
                    success : function(response) {
                        console.log(response);
                        
                        $('.btn-save-security').prop('disabled', false);
                        if (response.status == 'success') {
                            Swal.fire({
                                title : 'Success',
                                text : response.message,
                                icon : 'success',
                                showConfirmButton : false,
                                timer : 2000
                            });
                        } else {
                            Swal.fire({
                                title : 'Failed',
                                text : response.message,
                                icon : 'error',
                                showConfirmButton : false,
                                timer : 2000
                            });
                        }
                    },
                    error : function(xhr, status, error) {
                        console.error(xhr.responseText);
                        $('.btn-save-security').prop('disabled', false);
                        Swal.fire({
                            icon : 'error',
                            title : 'Failed',
                            text : 'Change Password Failed',
                        });
                        $('.btn-save-security').prop('disabled', false);
                    }
                });
            }
        })
    });
})
</script>
<?php
require './pages/templates/footer.php';
?>