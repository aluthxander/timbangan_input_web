<?php
require './pages/templates/header.php';
?>

<div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <img src="../../../img/logo/apparel.png" alt="apparel" class="img-fluid" style="max-width: 400px;">
        </div>
    </div>
    <form class="card w-50" style="max-width: 400px;" method="POST" action="{{ route('login.authenticate') }}">
        <div class="card-body">
            <h4 class="card-title text-center mb-5 mt-4">Login</h4>
            <div class="form-floating mb-4">
                <input type="email" name="txtemail" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
                <!-- <div id="emailHelp" class="form-text ms-2 text-danger">
                    Email salah
                </div> -->
            </div>
            <div class="form-floating mb-4 position-relative">
                <input type="password" name="txtpassword" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <i class="fa fa-eye position-absolute end-0 top-50 translate-middle-y me-3 text-secondary btn-eye" style="cursor: pointer;"></i>
                <!-- <div id="emailHelp" class="form-text ms-2 text-danger">
                    password salah
                </div> -->
            </div>
            <div class="row mb-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
require './pages/templates/footer.php';
?>