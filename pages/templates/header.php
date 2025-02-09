<?php
$csrf_token = BasicMethod::generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $csrf_token; ?>">
    <title>Kiwi Admin</title>
    <link rel="stylesheet" href="./plugin/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="./plugin/DataTables/datatables.css">
    <link rel="stylesheet" href="./plugin/select2/select2.min.css">
    <link rel="stylesheet" href="./plugin/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="./src/css/admin_navbar.min.css">
    <link rel="stylesheet" href="./src/css/style.css">
</head>
<?php
    $attr = "";
    if (isset($_GET['route'])) {
        $attr = $_GET['route'] == 'admin' ? ' id="body-pd" class="body-pd"' : '';
    }
?>
<body <?= $attr ?>>