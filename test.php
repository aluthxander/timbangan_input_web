<?php
require './vendor/autoload.php';
use Ltech\WebTimbangan\core\Database;
// password di bcript
$pass = password_hash("123456", PASSWORD_BCRYPT);
// insert 1 data ke dalam tabel user
$db = new Database;

$db->getConnection()->query('INSERT INTO users(name, email, password, jabatan, phone, username) VALUES("Lutfan", "lutfan@gmail.com", "'.$pass.'", "admin", "081234567890", "lutfan123")');

?>