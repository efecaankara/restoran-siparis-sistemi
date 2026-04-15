<?php
include "includes/auth.php";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <p>Hoş geldin, <?php echo htmlspecialchars($_SESSION["admin_username"]); ?></p>

    <ul>
        <li><a href="urun-ekle.php">Ürün Ekle</a></li>
        <li><a href="urunler.php">Ürünleri Gör</a></li>
        <li><a href="urunleri-yonet.php">Ürünleri Yönet</a></li>
        <li><a href="siparisler-admin.php">Siparişleri Gör</a></li>
        <li><a href="logout.php">Çıkış Yap</a></li>
    </ul>
</body>
</html>