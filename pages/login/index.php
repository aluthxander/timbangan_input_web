<?php
require './pages/templates/header.php';
?>
<body>
<div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <img src="../../../img/logo/apparel.png" alt="apparel" class="img-fluid" style="max-width: 400px;">
        </div>
    </div>
    <form class="card w-50" style="max-width: 400px;" method="POST" action="{{ route('login.authenticate') }}">
        <div class="card-body">
            <h4 class="card-title text-center mb-5 mt-4">Login</h4>
            <?php
            if (isset($_SESSION['alert'])) {
                echo "<div class='alert alert-{$_SESSION['alert']['type']} alert-dismissible fade show' role='alert'>
                        {$_SESSION['alert']['msg']}
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                unset($_SESSION['alert']);
            }
            ?>
            
            <div class="form-floating mb-4 email-form">
                <input type="text" name="txtemail" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email atau Username</label>
            </div>
            <div class="form-floating mb-4 position-relative password-form">
                <input type="password" name="txtpassword" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <i class="fa fa-eye position-absolute end-0 top-50 translate-middle-y me-3 text-secondary btn-eye" style="cursor: pointer;"></i>
            </div>
            <div class="row mb-5">
                <div class="col-12">
                    <button type="button" class="btn btn-primary w-100 btn-submit-login">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
// Show Password
let eye_password = document.querySelector('.btn-eye');
let password = document.querySelector('input[name="txtpassword"]');
eye_password.addEventListener('click', function() {
    password.type = password.type === 'password' ? 'text' : 'password';
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

document.querySelector('.btn-submit-login').addEventListener('click', function() {
    let email = document.querySelector('input[name="txtemail"]').value;
    let password = document.querySelector('input[name="txtpassword"]').value;
    
    if (email === '' || password === '') {
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: 'Email dan Password tidak boleh kosong!'
        });
        return;
    }
    this.disabled = true;

    fetch('./routes/api.php?route=login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); // Mengonversi response ke JSON
    })
    .then(res => {
        this.disabled = false;
        const invalidFeedback = document.querySelector('.password-form .invalid-feedback');
        if (invalidFeedback) {
            invalidFeedback.remove();
        }
        document.querySelector('input[name="txtpassword"]').classList.remove('is-invalid');
        document.querySelector('input[name="txtemail"]').classList.remove('is-invalid');

        const invalidFeedbackEmail = document.querySelector('.email-form .invalid-feedback-email');
        if (invalidFeedbackEmail) {
            invalidFeedbackEmail.remove();
        }

        if (res.status == 200) {
            window.location.href = res.data.link;
        }else if(res.status == 401){
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('form-text', 'ms-2', 'text-danger', 'invalid-feedback');
            errorMessage.textContent = res.message;

            document.querySelector('.password-form').appendChild(errorMessage);
            document.querySelector('input[name="txtpassword"]').classList.add('is-invalid');
        }else if(res.status == 404){
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('form-text', 'ms-2', 'text-danger', 'invalid-feedback-email');
            errorMessage.textContent = res.message;
            document.querySelector('.email-form').appendChild(errorMessage);
            document.querySelector('input[name="txtemail"]').classList.add('is-invalid');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });
});

</script>
</body>
<?php
require './pages/templates/footer.php';
?>