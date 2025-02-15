<?php
require './vendor/autoload.php';
use Ltech\WebTimbangan\core\Database;
// password di bcript
$pass = password_hash("123456", PASSWORD_BCRYPT);
// insert 1 data ke dalam tabel user
$db = new Database;
// menu
$db->getConnection()->query('INSERT INTO menu_web(menu) VALUES("Home"), ("Transactions"), ("Items"), ("Users")');
// jabatan
$akses = json_encode([
    [
        "menu" => "Home",
        "readonly" => true
    ],
    [
        "menu" => "Transactions",
        "readonly" => false
    ],
    [
        "menu" => "Items",
        "readonly" => false
    ],
    [
        "menu" => "Users",
        "readonly" => false
    ]
]);


$sql = "INSERT INTO jabatan(jabatan, akses) VALUES(:jabatan, :akses)";
$stmt = $db->getConnection()->prepare($sql);
$jabatan  = "admin";
$stmt->bindParam(":jabatan", $jabatan, PDO::PARAM_STR);
$stmt->bindParam(":akses", $akses, PDO::PARAM_STR);
$stmt->execute();
// users
$db->getConnection()->query('INSERT INTO users(name, email, password, jabatan_id, phone, username) VALUES("Lutfan", "lutfan@gmail.com", "'.$pass.'", "1", "081234567890", "lutfan123")');
// size
$db->getConnection()->query('INSERT INTO size_item(size) VALUES("S"), ("M"), ("L"), ("XL"), ("XXL")');
// item
$db->getConnection()->query('INSERT INTO item(code, name, style, size, weight_min, weight_max) VALUES("123456", "Baju Lutfan", "Lutfan", "S", 1.0000, 2.0000)');

// transaction
$db->getConnection()->query('INSERT INTO transaction(no_invoice, code_item, name_item, weight_item, style_item, size_item) VALUES ("123456", "123456", "Baju Lutfan", 1.5000, "Lutfan", "S")');
?>