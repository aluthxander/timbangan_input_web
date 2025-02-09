<?php include "./pages/admin/navbar_admin.php"?>

<div class="height-100 dashboard-home">
    <div class="row pt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-bg-success">
                    Users
                </div>
                <div class="card-body row">
                    <div class="col-6">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="col-6">
                        <div class="jml">
                            56
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-bg-danger">
                    Barang
                </div>
                <div class="card-body row">
                    <div class="col-6">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="col-6">
                        <div class="jml">
                            77
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header text-bg-secondary">
                    Transaksi
                </div>
                <div class="card-body row">
                    <div class="col-6 text-center">
                        <i class="fas fa-money-bill"></i>
                    </div>
                    <div class="col-6 text-center">
                        <div class="jml">
                            55
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "./pages/templates/js.php";
?>
<script type="module">
import BasicFunctions from './src/js/basic_method.js';

console.log(BasicFunctions.getParameterByName('route'));
</script>