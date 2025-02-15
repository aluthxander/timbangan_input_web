<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>web timbangan</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
        font-family: 'Arial', sans-serif;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #333;
    }
    .container {
        text-align: center;
        background: rgba(255, 255, 255, 0.8);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    h1 {
        font-size: 96px;
        margin-bottom: 20px;
    }
    p {
        font-size: 24px;
        margin-bottom: 30px;
    }
    a {
        text-decoration: none;
        padding: 12px 24px;
        background: #333;
        color: #fff;
        border-radius: 5px;
        transition: background 0.3s ease;
    }
    a:hover {
        background: #555;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Oops! Halaman yang Anda cari tidak ditemukan.</p>
        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Kembali ke Beranda</a>
    </div>
</body>
</html>
